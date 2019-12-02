<?php

namespace App\Repositories\Eloquents;

use App\Criteria\UserCriteria;
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
        $this->pushCriteria(UserCriteria::class);
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Group by results with created at
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function groupByResults()
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->with('topic')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($value) {
                return \Carbon\Carbon::parse($value->created_at)->isoFormat('YYYY-MM-DD');
            });
        $this->resetModel();

        return $this->parserResult($model);
    }
}
