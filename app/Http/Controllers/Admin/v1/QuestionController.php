<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Requests\Answer\CreateAnswerRequest;
use App\Http\Requests\Question\CreateQuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateQuestionRequest $request
     * @param CreateAnswerRequest $answerRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateQuestionRequest $request, CreateAnswerRequest $answerRequest)
    {
        try {
            $question = Question::create($request->only([
                'topic_id',
                'question_text',
                'code_snippet',
                'answer_explanation',
                'more_info_link',
            ]));
        $answers = $request->get('option');
        if (isset($answers)) {
            $data = [];
            foreach ($answers as $key => $answer) {
                $isCorrect = count($answers) <= (int) $answerRequest->input('correct') && (int) $answerRequest->input('correct') === ($key + 1) ? 1 : 0;
                $data[] = [
                    'question_id' => $question->id,
                    'option' => $answer,
                    'correct' => $isCorrect,
                    'created_at' => \now(),
                    'updated_at' => \now(),
                ];
            }
            $questionOfAnswers = Answer::query()->insert($data);
            return $this->responseCreate($questionOfAnswers);
        }
        } catch (\Exception $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
