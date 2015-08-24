<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Log;

class Employee extends User {

    protected $table = 'employees';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['firstname', 'lastname', 'username', 'password', 'email', 'phone', 'isadmin', 'branchid',
        'departmentid', 'teamid', 'active', 'remember_token',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    protected $hidden = ['password', 'remember_token'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->password = bcrypt("nissanhippo");
            $model->createdby = Auth::user()->id;
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::created(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Add','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::updating(function($model)
        {
            $model->modifiedby = Auth::user()->id;
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        static::updated(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Update','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });

        static::deleted(function($model)
        {
            Log::create(['employeeid' => Auth::user()->id,'operation' => 'Delete','date' => date("Y-m-d H:i:s"),'model' => class_basename(get_class($model)),'detail' => $model->toJson()]);
        });
    }

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
