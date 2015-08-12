<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $table = 'departments';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function employees()
    {
        return $this->hasMany('App\Employee', 'departmentid', 'id');
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
