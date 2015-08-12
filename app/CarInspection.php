<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarInspection extends Model {

    protected $table = 'car_inspections';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['carid', 'irno', 'irdate', 'laneno', 'lanedate', 'zone', 'from', 'to', 'trailergroup',
        'trailercompany', 'remark', 'uptype', 'position', 'senderkilometer', 'sendername', 'senderdate',
        'driverkilometer', 'drivername', 'driverdate', 'receiverkilometer', 'receiver', 'receiverdate',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function car()
    {
        return $this->belongsTo('App\Car', 'carid', 'id');
    }

    public function carInspectionDetails()
    {
        return $this->hasMany('App\CarInspectionDetail', 'carinspectionid', 'id');
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
