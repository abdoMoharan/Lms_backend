<?php
namespace App\Http\Requests\Api\Course;

use App\Http\Requests\Base\ApiRequest;
use App\Models\CourseTranslation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class CourseRequest extends ApiRequest
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
                "{$locale}.name" => "name " . ucfirst($locale),
                "{$locale}.description" => "description " . ucfirst($locale),
            ]);
        }
        return $attr;
    }

    public function rules()
    {
        $rules = [];

        // التحقق من الترجمة لكل لغة
        foreach (config('translatable.locales') as $locale) {
            $rules = array_merge($rules, [
                "{$locale}.name" => 'nullable',
                "{$locale}.description" => 'nullable',
                "{$locale}.slug" => "name" . Lang::get($locale),
            ]);
        }
        $rules = array_merge($rules, [
            'subject_id' => 'required|exists:subjects,id',
            'status'     => 'nullable|in:1,0',
            'day_count'  => 'required|integer',
            'stage_id'                => 'required|exists:educational_stages,id',
            'grade_id'                => 'required|exists:grades,id',
            'semesters'               => 'array',
            'semesters.*.id' => 'required|exists:semesters,id',
        ]);

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // تحقق إذا كان الاسم بالعربية أو الإنجليزية مفقود
            if (empty($this->ar['name']) && empty($this->en['name'])) {
                $validator->errors()->add('locales', __('At least one name must be provided in Arabic or English'));
            }
        });
    }

    public function getData()
    {
        $data = $this->validated();
        foreach (config('translatable.locales') as $locale) {
            if (empty($data[$locale]['slug']) && ! empty($data[$locale]['name'])) {
                $data[$locale]['slug'] = $this->generateUniqueSlug($data[$locale]['name'], $locale);
            }
        }
        // الترجمة التلقائية من العربية للغات أخرى
        foreach (config('translatable.locales') as $locale) {
            if ($locale !== 'ar' && empty($data[$locale]['name'])) {
                $data[$locale]['name'] = $this->translateAutomatically($data['ar']['name'], $locale);
            }
            if ($locale !== 'ar' && empty($data[$locale]['description'])) {
                $data[$locale]['description'] = $this->translateAutomatically($data['ar']['description'], $locale);
            }
        }
        // الترجمة التلقائية من الإنجليزية للعربية إذا كانت العربية فارغة
        if (empty($data['ar']['name']) && ! empty($data['en']['name'])) {
            $data['ar']['name'] = $this->translateAutomatically($data['en']['name'], 'ar');
        }
        if (empty($data['ar']['description']) && ! empty($data['en']['description'])) {
            $data['ar']['description'] = $this->translateAutomatically($data['en']['description'], 'ar');
        }
        return $data;
    }
    private function generateUniqueSlug($text, $locale)
    {
        // توليد slug باستخدام Str::slug
        $slug = Str::slug($text);

        // التحقق من وجود slug مكرر في قاعدة البيانات
        $existingSlug = CourseTranslation::where('locale', $locale)
            ->where('slug', $slug)
            ->exists();

        // إذا كان الـ slug مكررًا، أضف رقماً لتفادي التكرار
        $counter = 1;
        while ($existingSlug) {
            $slug         = Str::slug($text) . '-' . $counter;
            $existingSlug = CourseTranslation::where('locale', $locale)
                ->where('slug', $slug)
                ->exists();
            $counter++;
        }

        return $slug;
    }
    public function translateAutomatically($text, $locale)
    {
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
