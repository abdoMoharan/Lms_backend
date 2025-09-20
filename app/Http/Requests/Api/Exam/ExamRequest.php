<?php
namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Base\ApiRequest;
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

    public function rules()
    {
        $rules = [];
        foreach (config('translatable.locales') as $locale) {
            $rules = array_merge($rules, [
                "{$locale}.name" => 'nullable',
                "{$locale}.description" => 'nullable',
            ]);
        }

        // قواعد البيانات الأساسية للامتحان والأسئلة والإجابات
        $rules = array_merge($rules, [
            'course_id'                            => 'required|exists:courses,id',
            'teacher_id'                           => 'required|exists:users,id',
            'time'                                 => 'required',
            'start_date'                           => 'required|date',
            'end_date'                             => 'required|date',
            'total'                                => 'required',
            'questions'                            => 'required|array',                    // الأسئلة كمصفوفة
            'questions.*.question_type_id'         => 'required|exists:question_types,id', // نوع السؤال
            'questions.*.name'                     => 'required|string',                   // نص السؤال
            'questions.*.answers'                  => 'required|array',                    // مجموعة الإجابات
            'questions.*.answers.*.name'           => 'required|string',                   // نص الإجابة
            'questions.*.answers.*.correct_answer' => 'required|in:1,0',                  // الإجابة الصحيحة
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
        // الترجمة التلقائية من العربية للغات الأخرى
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

    public function translateAutomatically($text, $locale)
    {
        // تجنب إرسال نص فارغ للترجمة
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

        return $text; // إذا فشلت الترجمة، سيتم إرجاع النص الأصلي
    }
}
