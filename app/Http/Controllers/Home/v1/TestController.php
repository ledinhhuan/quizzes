<?php

namespace App\Http\Controllers\Home\v1;

use App\Models\Question;
use App\Models\Topic;
use App\Repositories\Interfaces\AnswerRepository;
use App\Repositories\Interfaces\QuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    protected $questionRepository;
    protected $answerRepository;

    public function __construct(QuestionRepository $questionRepository, AnswerRepository $answerRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    public function showQuizz ($id) {
        try {
            $questions = $this->questionRepository->randomOrder([
                'topic_id' => $id,
                'status' => Topic::IS_ENABLED
            ]);
            return $this->responseSuccessNoMess($questions);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->responseError('test.message.not_found');
        }
    }

    public function doQuizz (Request $request)
    {
        try {
            $questions = $request->input('questions.*');
            if (is_array($questions) && count($questions) == Question::NUM_OF_QUESTION) {

            }
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->responseError('test.message.quizz_error');
        }
    }
}
