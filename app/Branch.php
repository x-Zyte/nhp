<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model {

    protected $table = 'branches';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid', 'name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function employees()
    {
        return $this->hasMany('App\Employee', 'branchid', 'id');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer', 'branchid', 'id');
    }

    public function cars()
    {
        return $this->hasMany('App\Car', 'branchid', 'id');
    }

    public function province()
    {
        return $this->belongsTo('App\Province', 'provinceid', 'id');
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
