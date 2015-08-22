<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends User {

    protected $table = 'employees';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['firstname', 'lastname', 'phone', 'email', 'username', 'password', 'isadmin', 'branchid',
        'departmentid', 'teamid', 'active', 'remember_token',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    protected $hidden = ['password', 'remember_token'];

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'branchid', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'departmentid', 'id');
    }

    public function team()
    {
        return $this->belongsTo('App\Team', 'teamid', 'id');
    }
}
