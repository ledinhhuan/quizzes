<?php

namespace App\Repositories\Eloquents;

use App\Models\TestAnswer;
use App\Repositories\Interfaces\TestAnswerRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ArticleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class TestAnswerRepositoryEloquent extends BaseRepository implements TestAnswerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TestAnswer::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
