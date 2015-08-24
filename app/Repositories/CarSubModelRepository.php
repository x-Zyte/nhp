<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\CarSubModel;
use App\Repositories\EloquentRepositoryAbstract;

class CarSubModelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarSubModel;
        $this->orderBy = array(array('id', 'asc'));
        /*$this->orderBy = array(array('carmodelid', 'asc'), array('name','asc'));*/
        /*$this->visibleColumns = array('name', 'carmodelid', 'detail', 'active',
            'createdby', 'createddate', 'modifiedby', 'modifieddate');*/
    }
}
