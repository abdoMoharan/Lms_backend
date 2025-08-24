<?php

namespace App\Http\Requests\Api\EducationalStage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class EducationalStageRequest extends ApiRequest
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
                "{$locale}.title" => "Title " . Lang::get($locale),
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
                "{$locale}.title" => 'nullable',
            ]);
        }
        $req = array_merge($req, [
            'status' => 'nullable',
        ]);


        return $req;
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Check if both ar.title and en.title are empty
            if (empty($this->ar['title']) && empty($this->en['title'])) {
                $validator->errors()->add('locales', __('At least one title must be provided in Arabic or English'));
            }
        });
    }
    public function getData()
    {
        $data = $this->validated();
        $data['status'] = isset($data['status']) ? true : false;
        if ($this->isMethod('POST')) {
            $data['created_by'] = Auth::user()->id;
        } else {
            $data['updated_by'] = Auth::user()->id;
        }
        // Translate automatically from Arabic to other languages
        foreach (config('translatable.locales') as $locale) {
            if ($locale !== 'ar' && empty($data[$locale]['title'])) {
                $data[$locale]['title'] = $this->translateAutomatically($data['ar']['title'], $locale);
            }
        }
        // Automatic translation from English to Arabic if Arabic is empty
        if (empty($data['ar']['title']) && !empty($data['en']['title'])) {
            $data['ar']['title'] = $this->translateAutomatically($data['en']['title'], 'ar');
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
