<?php

namespace App\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TestAnswerRepository
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface TestAnswerRepository extends RepositoryInterface
{
    public function insertMany(array $attribute);
}
