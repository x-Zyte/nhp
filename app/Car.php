<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model {

    protected $table = 'cars';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['cartypeid', 'branchid', 'no', 'dodate', 'receiveddate', 'engineno', 'chassisno', 'keyno',
        'model', 'submodel', 'colour', 'iscompanycar',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function carType()
    {
        return $this->belongsTo('App\CarType', 'cartypeid', 'id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'branchid', 'id');
    }

    public function carInspection()
    {
        return $this->hasOne('App\CarInspection', 'carid', 'id');
    }

    public function employeeCreated()
    {
        return $this->belongsTo('App\Employee', 'createdby', 'id');
    }

    public function employeMmodified()
    {
        return $this->belongsTo('App\Employee', 'modifiedby', 'id');
    }

}
