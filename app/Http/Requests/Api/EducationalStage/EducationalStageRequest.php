<?php
namespace App\Http\Requests\Api\EducationalStage;

use App\Http\Requests\Base\ApiRequest;
use App\Models\EducationalStageTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

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
                "{$locale}.name" => "name" . Lang::get($locale),
                "{$locale}.slug" => "name" . Lang::get($locale),
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
                 "{$locale}.slug" => "name" . Lang::get($locale),
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
        });
    }
    // public function getData()
    // {
    //     $data = $this->validated();
    //     // توليد slug ديناميكيًا
    //     foreach (config('translatable.locales') as $locale) {
    //         if (empty($data[$locale]['slug']) && ! empty($data[$locale]['name'])) {
    //             $data[$locale]['slug'] = Str::slug($data[$locale]['name']);
    //         }
    //     }
    //     if ($this->isMethod('POST')) {
    //         $data['created_by'] = Auth::user()->id;
    //     } else {
    //         $data['updated_by'] = Auth::user()->id;
    //     }
    //     // Translate automatically from Arabic to other languages
    //     foreach (config('translatable.locales') as $locale) {
    //         if ($locale !== 'ar' && empty($data[$locale]['name'])) {
    //             $data[$locale]['name'] = $this->translateAutomatically($data['ar']['name'], $locale);
    //         }
    //     }
    //     // Automatic translation from English to Arabic if Arabic is empty
    //     if (empty($data['ar']['name']) && ! empty($data['en']['name'])) {
    //         $data['ar']['name'] = $this->translateAutomatically($data['en']['name'], 'ar');
    //     }
    //     return $data;
    // }
    public function getData()
    {
        $data = $this->validated();

        // توليد slug ديناميكيًا والتأكد من أنه غير مكرر
        foreach (config('translatable.locales') as $locale) {
            if (empty($data[$locale]['slug']) && ! empty($data[$locale]['name'])) {
                $data[$locale]['slug'] = $this->generateUniqueSlug($data[$locale]['name'], $locale);
            }
        }

        if ($this->isMethod('POST')) {
            $data['created_by'] = Auth::user()->id;
        } else {
            $data['updated_by'] = Auth::user()->id;
        }

        // الترجمة التلقائية من العربية إلى لغات أخرى
        foreach (config('translatable.locales') as $locale) {
            if ($locale !== 'ar' && empty($data[$locale]['name'])) {
                $data[$locale]['name'] = $this->translateAutomatically($data['ar']['name'], $locale);
            }
        }

        // الترجمة التلقائية من الإنجليزية إلى العربية إذا كانت العربية فارغة
        if (empty($data['ar']['name']) && ! empty($data['en']['name'])) {
            $data['ar']['name'] = $this->translateAutomatically($data['en']['name'], 'ar');
        }

        return $data;
    }

/**
 * Generate a unique slug for the given text and locale.
 */
    private function generateUniqueSlug($text, $locale)
    {
        // توليد slug باستخدام Str::slug
        $slug = Str::slug($text);

        // التحقق من وجود slug مكرر في قاعدة البيانات
        $existingSlug = EducationalStageTranslation::where('locale', $locale)
            ->where('slug', $slug)
            ->exists();

        // إذا كان الـ slug مكررًا، أضف رقماً لتفادي التكرار
        $counter = 1;
        while ($existingSlug) {
            $slug         = Str::slug($text) . '-' . $counter;
            $existingSlug = EducationalStageTranslation::where('locale', $locale)
                ->where('slug', $slug)
                ->exists();
            $counter++;
        }

        return $slug;
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
