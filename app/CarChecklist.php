<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarChecklist extends Model {

    protected $table = 'car_checklists';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['cartypeid', 'name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function carType()
    {
        return $this->belongsTo('App\CarType', 'cartypeid', 'id');
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
