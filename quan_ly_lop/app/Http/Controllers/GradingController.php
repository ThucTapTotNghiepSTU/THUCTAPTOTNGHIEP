<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradingController extends Controller
{

    public function gradeSubmission(Request $request, $submission_id)
    {
        $submission = Submission::findOrFail($submission_id);
        $answers = Answer::where('submission_id', $submission_id)->get();

        $totalScore = 0;
        $maxScore = 10;
        $gradedAnswers = [];

        foreach ($answers as $answer) {
            $question = Question::where('title', $answer->question_title)
                ->where('content', $answer->question_content)
                ->first();

            if (!$question) {
                continue;
            }

            if ($question->type === 'Trắc nghiệm') {
                // Automatic grading for multiple choice
                $correctOption = Options::where('question_id', $question->question_id)
                    ->where('is_correct', true)
                    ->first();

                $isCorrect = $correctOption &&
                    strtolower(trim($answer->question_answer)) ===
                    strtolower(trim($correctOption->option_text));

                $score = $isCorrect ? $maxScore / count($answers) : 0;
                $totalScore += $score;

                $gradedAnswers[] = [
                    'question' => $answer->question_title,
                    'student_answer' => $answer->question_answer,
                    'correct_answer' => $correctOption ? $correctOption->option_text : null,
                    'score' => $score,
                    'is_correct' => $isCorrect
                ];
            } else {

                $gradedAnswers[] = [
                    'question' => $answer->question_title,
                    'student_answer' => $answer->question_answer,
                    'score' => null
                ];
            }
        }


        $submission->temporary_score = $totalScore;
        $submission->save();

        return response()->json([
            'submission' => $submission,
            'graded_answers' => $gradedAnswers,
            'total_score' => $totalScore,
            'max_score' => $maxScore
        ]);
    }


    public function updateManualGrades(Request $request, $submission_id)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.question_title' => 'required|string',
            'grades.*.score' => 'required|numeric|min:0|max:10'
        ]);

        $submission = Submission::findOrFail($submission_id);
        $totalScore = $submission->temporary_score ?? 0;
        $essayCount = 0;

        foreach ($request->grades as $grade) {
            $answer = Answer::where('submission_id', $submission_id)
                ->where('question_title', $grade['question_title'])
                ->first();

            if ($answer) {
                $question = Question::where('title', $answer->question_title)
                    ->where('content', $answer->question_content)
                    ->first();

                if ($question && $question->type === 'Tự luận') {
                    $essayCount++;
                    $totalScore += $grade['score'];
                }
            }
        }


        if ($essayCount > 0) {
            $totalScore = $totalScore / ($essayCount + 1); // Average of multiple choice and essay scores
        }

        $submission->temporary_score = $totalScore;
        $submission->save();

        return response()->json([
            'message' => 'Grades updated successfully',
            'submission' => $submission
        ]);
    }
}
