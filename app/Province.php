<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {

    protected $table = 'provinces';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function branches()
    {
        return $this->hasMany('App\Branch', 'provinceid', 'id');
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
