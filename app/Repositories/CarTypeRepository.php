<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\CarType;
use App\Repositories\EloquentRepositoryAbstract;

class CarTypeRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarType;
        $this->orderBy = array(array('id', 'asc'));
    }
}
