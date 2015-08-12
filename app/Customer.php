<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    protected $table = 'customers';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['firstname', 'lastname', 'address', 'phone', 'email', 'branchid',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'branchid', 'id');
    }

    public function customerExpectations()
    {
        return $this->hasMany('App\CustomerExpectation', 'customerid', 'id');
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
