<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Employee;
use App\Repositories\EloquentRepositoryAbstract;

class EmployeeRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Employee;
        $this->orderBy = array(array('id', 'asc'));
    }
}
