<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarInspectionDetail extends Model {

    protected $table = 'car_inspection_details';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['carinspectionid', 'carchecklistid', 'sendercheck', 'drivercheck', 'receivercheck',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function carInspection()
    {
        return $this->belongsTo('App\CarInspection', 'carinspectionid', 'id');
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
