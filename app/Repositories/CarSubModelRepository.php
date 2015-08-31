<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/24/2015
 * Time: 3:31
 */

namespace App\Repositories;

use App\Models\CarSubModel;

class CarSubModelRepository extends EloquentRepositoryAbstract
{
    public function __construct()
    {
        $this->Database = new CarSubModel;
        $this->orderBy = array(array('id', 'asc'));
        $this->crudFields = array('oper', 'id', 'carmodelid', 'name', 'detail');
        $this->uniqueKeySingles = array();
        $this->uniqueKeyMultiples = array(array('field'=>'carmodelid','showInMsg'=>false,'label'=>'แบบรถ'),
            array('field'=>'name','showInMsg'=>true,'label'=>'แบบรถนี้ รุ่น'));
        $this->hasBranch = false;
    }
}
