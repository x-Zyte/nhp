<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Branch;
use App\Repositories\EloquentRepositoryAbstract;

class BranchRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Branch;
        $this->orderBy = array(array('id', 'asc'));
    }
}
