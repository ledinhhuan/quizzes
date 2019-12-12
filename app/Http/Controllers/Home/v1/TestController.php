<?php

namespace App\Http\Controllers\Home\v1;

use App\Criteria\UserCriteria;
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
use Illuminate\Support\Facades\DB;

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

    /**
     * @OA\Get(
     *     path="/api/users/test/{id}",
     *     tags={""},
     *     operationId="resultTested",
     *     summary="Show Exam Quizz",
     *   security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/users/tests",
     *     tags={""},
     *     operationId="doTest",
     *     summary="Do Test Exam",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="topic_id",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[0]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[0]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[1]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[1]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[2]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[2]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[3]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[3]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[4]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[4]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[5]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[5]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[6]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[6]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[7]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[7]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[8]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[8]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="questions[9]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="answers[9]",in="query",required=true,@OA\Schema(type="string")),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/users/results/{id}",
     *     tags={""},
     *     operationId="resultTested",
     *     summary="Show Result Tested",
     *   security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
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
            $data = ['test' => $test, 'chart' => $calculateChart, 'results' => $results];

            return $this->responseSuccessNoMess($data);
        } catch (ModelNotFoundException $ex) {
            return $this->responseError('test.message.result_not_found', [], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/history-tests",
     *     tags={""},
     *     operationId="history",
     *     summary="History Test",
     *   security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function historyResults()
    {
        $data = $this->testRepository->groupByResults();

        return $this->responseSuccessNoMess($data);
    }

    /**
     * @OA\Get(
     *     path="/api/users/delete-result/{id}",
     *     tags={""},
     *     operationId="deleteResult",
     *     summary="Delete History Result",
     *   security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function deleteResult ($id)
    {
        \DB::beginTransaction();
        try {
            $this->testRepository->pushCriteria(UserCriteria::class);
            $test = $this->testRepository->find($id);
            $test->delete();
            \DB::commit();
            return $this->responseSuccess('test.message.delete_successfully', [], 200);
        } catch (ModelNotFoundException $e) {
            \DB::rollback();
            \Log::error('Delete Result History Error: ' . $e->getMessage());
            return $this->responseError('test.message.delete_error');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/rank-of-quizzes",
     *     tags={""},
     *     operationId="rank",
     *     summary="Rank Of Quizzes",
     *   security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function rankOfQuizzes ()
    {
        $rank = Test::query()
                ->selectRaw('AVG(result) as average, users.name')
            ->leftJoin('users', 'users.id', '=', 'tests.user_id')
            ->groupBy('user_id')
            ->orderBy('average', 'DESC')
            ->take(10)->get();

        return $this->responseSuccessNoMess(['rank' => $rank]);
    }
}
