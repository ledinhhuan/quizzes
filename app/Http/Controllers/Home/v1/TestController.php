<?php

namespace App\Http\Controllers\Home\v1;

use App\Http\Requests\Tests\CreateTestRequest;
use App\Models\Question;
use App\Models\Topic;
use App\Repositories\Interfaces\AnswerRepository;
use App\Repositories\Interfaces\QuestionRepository;
use App\Repositories\Interfaces\TestAnswerRepository;
use App\Repositories\Interfaces\TestRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    protected $questionRepository;
    protected $answerRepository;
    protected $testAnswerRepository;
    protected $testRepository;

    public function __construct(QuestionRepository $questionRepository,
                                AnswerRepository $answerRepository,
                                TestAnswerRepository $testAnswerRepository,
                                TestRepository $testRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->testAnswerRepository = $testAnswerRepository;
        $this->testRepository = $testRepository;
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

    public function doQuizz (CreateTestRequest $request)
    {
        try {
            $questions = $request->input('questions.*');
            if (is_null($questions)) {
                if (count($questions) != Question::NUM_OF_QUESTION) {
                    return $this->responseError('test.quizz.not_enough_question');
                }
            }
            $test = $this->testRepository->create([
               'user_id' =>currentUserLogin()->id,
               ''
            ]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->responseError('test.message.quizz_error');
        }
    }
}
