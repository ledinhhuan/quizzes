<?php

namespace App\Repositories\Eloquents;

use App\Models\Question;
use App\Repositories\Interfaces\QuestionRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ArticleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class QuestionRepositoryEloquent extends BaseRepository implements QuestionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Question::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function randomOrder(array $where)
    {
        $this->applyConditions($where);
        $model = $this->model
            ->with(['answers' => function($q) {
                $q->inRandomOrder();
            }])
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return $this->parserResult($model);
    }
}
