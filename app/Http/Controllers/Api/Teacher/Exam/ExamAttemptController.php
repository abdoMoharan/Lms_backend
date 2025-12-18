<?php
namespace App\Http\Controllers\Api\Teacher\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamAttemptController extends Controller
{
    public function start($local,Request $request, Exam $exam)
    {
        $user = $request->user();

        // تقدر تمنع تكرار المحاولة هنا لو حابب
        $attempt = ExamAttempt::create([
            'user_id'    => $user->id,
            'exam_id'    => $exam->id,
            'started_at' => now(),
        ]);

        $exam->load(['questions.options']);

        return response()->json([
            'attempt_id' => $attempt->id,
            'exam'       => $exam,
        ]);
    }


    public function submit($local,Request $request, Exam $exam)
    {
        $data = $request->validate([
            'attempt_id'            => 'required|exists:exam_attempts,id',
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.option_id'   => 'required|integer',
        ]);


        $attempt = ExamAttempt::where('id', $data['attempt_id'])
            ->where('exam_id', $exam->id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        $score = 0;

        DB::beginTransaction();

        try {
            foreach ($data['answers'] as $answerData) {
                $questionId = $answerData['question_id'];
                $optionId   = $answerData['option_id'];

                $question = Question::where('exam_id', $exam->id)
                    ->where('id', $questionId)
                    ->firstOrFail();

                $option = Option::where('id', $optionId)
                    ->where('question_id', $questionId)
                    ->firstOrFail();

                $isCorrect = $option->is_correct;

                if ($isCorrect) {
                    $score += $question->mark;
                }

                ExamAnswer::updateOrCreate(
                    [
                        'exam_attempt_id' => $attempt->id,
                        'question_id'     => $questionId,
                    ],
                    [
                        'option_id'  => $optionId,
                        'is_correct' => $isCorrect,
                    ]
                );
            }

            $attempt->update([
                'score'       => $score,
                'finished_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'attempt_id'  => $attempt->id,
                'score'       => $score,
                'total_marks' => $exam->total_marks,
                'percentage'  => $exam->total_marks > 0
                    ? round(($score / $exam->total_marks) * 100, 2)
                    : null,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error submitting exam'], 500);
        }
    }
}
