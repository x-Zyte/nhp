<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerExpectation extends Model {

    protected $table = 'customer_expectations';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['customerid', 'date', 'details',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customerid', 'id');
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
