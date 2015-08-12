<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model {

    protected $table = 'role_permissions';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['roleid', 'permissionid',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public function role()
    {
        return $this->belongsTo('App\Role', 'roleid', 'id');
    }

    public function permission()
    {
        return $this->belongsTo('App\Permission', 'permissionid', 'id');
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
