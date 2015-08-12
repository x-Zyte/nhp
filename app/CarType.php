<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model {

    protected $table = 'car_types';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function carChecklists()
    {
        return $this->hasMany('App\CarChecklist', 'cartypeid', 'id');
    }

    public function cars()
    {
        return $this->hasMany('App\Car', 'cartypeid', 'id');
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
