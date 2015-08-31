<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model {

    protected $table = 'customers';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'firstname', 'lastname', 'address', 'districtid', 'amphurid', 'provinceid', 'zipcode',
        'email', 'phone', 'branchid',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
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
        return $this->belongsTo('App\Models\Branch', 'branchid', 'id');
    }

    public function customerExpectations()
    {
        return $this->hasMany('App\Models\CustomerExpectation', 'customerid', 'id');
    }
}
