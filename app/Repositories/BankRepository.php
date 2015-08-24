<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Bank;
use App\Repositories\EloquentRepositoryAbstract;

class BankRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new Bank;
        $this->orderBy = array(array('id', 'asc'));
    }
}
