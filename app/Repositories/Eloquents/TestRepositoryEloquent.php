<?php

namespace App\Repositories\Eloquents;

use App\Models\Test;
use App\Repositories\Interfaces\TestRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ArticleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class TestRepositoryEloquent extends BaseRepository implements TestRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Test::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
