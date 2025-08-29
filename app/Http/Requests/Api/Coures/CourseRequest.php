<?php

namespace App\Http\Requests\Api\Coures;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;
use App\Http\Requests\Base\ApiRequest;
class CourseRequest extends  ApiRequest
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
                "{$locale}.desorption" => 'nullable',
            ]);
        }
        $req = array_merge($req, [
            'status' => 'nullable|in:1,0',
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
            if (empty($this->ar['desorption']) && empty($this->en['desorption'])) {
                $validator->errors()->add('locales', __('At least one desorption must be provided in Arabic or English'));
            }
        });
    }
    public function getData()
    {
        $data = $this->validated();
        if ($this->isMethod('POST')) {
            $data['created_by'] = Auth::user()->id;
        } else {
            $data['updated_by'] = Auth::user()->id;
        }
        // Translate automatically from Arabic to other languages
        foreach (config('translatable.locales') as $locale) {
            if ($locale !== 'ar' && empty($data[$locale]['name'])) {
                $data[$locale]['name'] = $this->translateAutomatically($data['ar']['name'], $locale);
            }
            if ($locale !== 'ar' && empty($data[$locale]['desorption'])) {
                $data[$locale]['desorption'] = $this->translateAutomatically($data['ar']['desorption'], $locale);
            }
        }
        // Automatic translation from English to Arabic if Arabic is empty
        if (empty($data['ar']['name']) && !empty($data['en']['name'])) {
            $data['ar']['name'] = $this->translateAutomatically($data['en']['name'], 'ar');
        }
        if (empty($data['ar']['desorption']) && !empty($data['en']['desorption'])) {
            $data['ar']['desorption'] = $this->translateAutomatically($data['en']['desorption'], 'ar');
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
            'q' => $text,
            'langpair' => "{$sourceLang}|{$locale}",
        ]);

        if ($response->successful() && isset($response->json()['responseData']['translatedText'])) {
            return $response->json()['responseData']['translatedText'];
        }

        return $text; // Return the original text if translation fails
    }
}
