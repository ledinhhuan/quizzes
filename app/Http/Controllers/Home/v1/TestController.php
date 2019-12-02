<?php

namespace App\Http\Controllers\Home\v1;

use App\Http\Requests\Tests\CreateTestRequest;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\Topic;
use App\Repositories\Interfaces\AnswerRepository;
use App\Repositories\Interfaces\QuestionRepository;
use App\Repositories\Interfaces\TestAnswerRepository;
use App\Repositories\Interfaces\TestRepository;
use App\Services\ChartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    protected $questionRepository;
    protected $answerRepository;
    protected $testAnswerRepository;
    protected $testRepository;
    protected $chartService;

    public function __construct(QuestionRepository $questionRepository,
                                AnswerRepository $answerRepository,
                                TestAnswerRepository $testAnswerRepository,
                                TestRepository $testRepository,
                                ChartService $chartService)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->testAnswerRepository = $testAnswerRepository;
        $this->testRepository = $testRepository;
        $this->chartService = $chartService;
    }

    public function showQuizz ($id) {
        try {
            $topic = Topic::query()->where('id', $id)->where('status', Topic::IS_ENABLED)->first();
            if ($topic == null) {
                return $this->responseError('test.messsage.not_active', [], 400);
            }
            $questions = $this->questionRepository->randomOrder([
                'topic_id' => $id
            ]);
            return $this->responseSuccessNoMess($questions);
        } catch (ModelNotFoundException $ex) {
            \Log::error($ex);
            return $this->responseError('test.message.not_found',[], 404);
        }
    }

    public function doQuizz (CreateTestRequest $request)
    {
        \DB::beginTransaction();
        try {
            $questions = $request->input('questions.*');
            if (!is_null($questions)) {
                if (count($questions) != Question::NUM_OF_QUESTION) {
                    return $this->responseError('test.quizz.not_enough_question');
                }
            }
            $test = $this->testRepository->create([
                'user_id' => \Auth::id(),
                'topic_id' => $request->get('topic_id'),
                'result' => 0
            ]);
            $this->testAnswerRepository->insertMany(Test::dataQuestions($request, $questions, $test));

            /*** Calculate rersult when submit ***/
            $answers = $request->input('answers.*');
            if (!is_null($answers)) {
                $answers = $this->answerRepository->find($request->input('answers.*'));
            }
            $test->update([
                'result' => Test::calculateResult($answers),
            ]);
            \DB::commit();
            return $this->responseSuccess('test.message.success_quizz');
        } catch (\Exception $ex) {
            dd($ex);
            \DB::rollBack();
            \Log::error($ex);
            return $this->responseError('test.message.quizz_error', []);
        }
    }

    public function showResult ($id)
    {
        try {
            $test = $this->testRepository->find($id);
            $results = $this->testAnswerRepository->with('question')
                ->with('question.answers')
                ->findWhere(['test_id' => $test->id]);

            $chart = \DB::table('tests')
                ->select('result')
                ->where(['topic_id' => $test->topic_id])
                ->groupBy('id')
                ->pluck('result');

            $partition = $this->chartService->partitionLevel($chart);
            $calculateChart = $this->chartService->calculateLevel($partition, $chart);
            $data = ['chart' => $calculateChart, 'results' => $results];
            return $this->responseSuccessNoMess($data);
        } catch (ModelNotFoundException $ex) {
            return $this->responseError('test.message.result_not_found', [], 404);
        }
    }
}
