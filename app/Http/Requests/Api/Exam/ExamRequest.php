<?php
namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

class ExamRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function attributes()
    {
        $attr = [];
        foreach (config('translatable.locales') as $locale) {
            $attr = array_merge($attr, [
                "{$locale}.name" => "name" . Lang::get($locale),
                "{$locale}.description" => "description" . Lang::get($locale),
            ]);
        }
        return $attr;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $req = [];
        foreach (config('translatable.locales') as $locale) {
            $req = array_merge($req, [
                "{$locale}.name" => 'nullable',
                "{$locale}.description" => 'nullable',
            ]);
        }
        $req = array_merge($req, [
            'course_id' =>'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'time' => 'required',
            'start_date' => 'required|date',
            'end_date'=>'required|date',
            'total' => 'required',
        ]);

        return $req;
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Check if both ar.name and en.name are empty
            if (empty($this->ar['name']) && empty($this->en['name'])) {
                $validator->errors()->add('locales', __('At least one name must be provided in Arabic or English'));
            }
        });
    }
    public function getData()
    {
        $data = $this->validated();
        // Translate automatically from Arabic to other languages
        foreach (config('translatable.locales') as $locale) {
            if ($locale !== 'ar' && empty($data[$locale]['name'])) {
                $data[$locale]['name'] = $this->translateAutomatically($data['ar']['name'], $locale);
            }
            if ($locale !== 'ar' && empty($data[$locale]['description'])) {
                $data[$locale]['description'] = $this->translateAutomatically($data['ar']['description'], $locale);
            }
        }
        // Automatic translation from English to Arabic if Arabic is empty
        if (empty($data['ar']['name']) && ! empty($data['en']['name'])) {
            $data['ar']['name'] = $this->translateAutomatically($data['en']['name'], 'ar');
        }
        if (empty($data['ar']['description']) && ! empty($data['en']['description'])) {
            $data['ar']['description'] = $this->translateAutomatically($data['en']['description'], 'ar');
        }
        return $data;
    }

    public function translateAutomatically($text, $locale)
    {
        // Avoid sending empty text for translation
        if (empty($text)) {
            return '';
        }
        $sourceLang = $locale === 'ar' ? 'en' : 'ar';

        $response = Http::get('https://api.mymemory.translated.net/get', [
            'q'        => $text,
            'langpair' => "{$sourceLang}|{$locale}",
        ]);

        if ($response->successful() && isset($response->json()['responseData']['translatedText'])) {
            return $response->json()['responseData']['translatedText'];
        }

        return $text; // Return the original text if translation fails
    }
}
