<?php
namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Base\ApiRequest;
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
                "{$locale}.name" => "name " . ucfirst($locale),
                "{$locale}.description" => "description " . ucfirst($locale),
                "questions.*.name.{$locale}" => "question name " . ucfirst($locale), // السؤال
                "questions.*.answers.*.name.{$locale}" => "answer name " . ucfirst($locale), // الإجابة
            ]);
        }
        return $attr;
    }

    public function rules()
    {
        $rules = [];

        // التحقق من الترجمة لكل لغة
        foreach (config('translatable.locales') as $locale) {
            $rules["questions.*.name.{$locale}"] = 'required|string'; // الترجمة للسؤال
            $rules["questions.*.answers.*.name.{$locale}"] = 'required|string'; // الترجمة للإجابة
        }

        $rules = array_merge($rules, [
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'time' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total' => 'required',
            'questions' => 'required|array',  // الأسئلة كمصفوفة
            'questions.*.question_type_id' => 'required|exists:question_types,id',  // نوع السؤال
            'questions.*.name' => 'required|string',  // نص السؤال
            'questions.*.answers' => 'required|array',  // مجموعة الإجابات
            'questions.*.answers.*.name' => 'required|string',  // نص الإجابة
            'questions.*.answers.*.correct_answer' => 'required|boolean',  // الإجابة الصحيحة
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

    public function translateAutomatically($text, $locale)
    {
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
