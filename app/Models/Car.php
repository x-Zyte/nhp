<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Car extends Model {

    protected $table = 'cars';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['branchid', 'carmodelid', 'carsubmodelid', 'no', 'dodate', 'receiveddate', 'engineno', 'chassisno', 'keyno',
        'colour', 'objective', 'receivetype', 'receivecarfilepath', 'deliverycarfilepath', 'issold', 'isregistered', 'isdelivered',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->dodate = date('Y-m-d', strtotime($model->dodate));
            $model->receiveddate = date('Y-m-d', strtotime($model->receiveddate));
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
            $model->dodate = date('Y-m-d', strtotime($model->dodate));
            $model->receiveddate = date('Y-m-d', strtotime($model->receiveddate));
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

            if($model->receivecarfilepath != '')
                File::delete(public_path().$model->receivecarfilepath);
            if($model->deliverycarfilepath != '')
                File::delete(public_path().$model->deliverycarfilepath);
        });
    }

    public function carModel()
    {
        return $this->belongsTo('App\Models\CarModel', 'carmodelid', 'id');
    }

    public function carSubModel()
    {
        return $this->belongsTo('App\Models\CarSubModel', 'carsubmodelid', 'id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branchid', 'id');
    }
}
