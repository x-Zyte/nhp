<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $table = 'roles';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'detail', 'sequence', 'active',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permissions', 'roleid');
    }

    public function employees()
    {
        return $this->hasMany('App\Employee', 'roleid', 'id');
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
