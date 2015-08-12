<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model {

    protected $table = 'banks';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function employeeCreated()
    {
        return $this->belongsTo('App\Employee', 'createdby', 'id');
    }

    public function employeMmodified()
    {
        return $this->belongsTo('App\Employee', 'modifiedby', 'id');
    }

}
