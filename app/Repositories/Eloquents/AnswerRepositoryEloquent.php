<?php

namespace App\Repositories\Eloquents;

use App\Models\Answer;
use App\Repositories\Interfaces\AnswerRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ArticleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class AnswerRepositoryEloquent extends BaseRepository implements AnswerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Answer::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
