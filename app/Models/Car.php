<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Car extends Model {

    protected $table = 'cars';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['provinceid', 'carmodelid', 'carsubmodelid', 'no', 'dodate', 'receiveddate', 'engineno', 'chassisno', 'keyno',
        'colorid', 'objective', 'receivetype', 'receivecarfilepath', 'deliverycarfilepath', 'issold', 'isregistered', 'isdelivered',
        'createdby', 'createddate', 'modifiedby', 'modifieddate'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->issold = false;
            $model->isregistered = false;
            $model->isdelivered = false;

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
            $rs = DB::select('call running_number("'.$model->provinceid.date("Y").'","'.$model->receivetype.'")');
            $model->no = $rs[0]->no;
            $min = KeySlot::where('provinceid', $model->provinceid)->where('active',true)->min('no');
            if($min == null){
                $branch = Branch::where('provinceid', $model->provinceid)->where('isheadquarter', true)->first();
                $branch->keyslot = $branch->keyslot+1;
                $branch->save();
                $model->keyno = $branch->keyslot;
            }
            else{
                $model->keyno = $min;
            }
            $model->save();
            KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->update(['active' => false]);
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

            KeySlot::where('provinceid', $model->provinceid)->where('no',$model->keyno)->update(['active' => true]);
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

    public function province()
    {
        return $this->belongsTo('App\Models\systemdatas\Province', 'provinceid', 'id');
    }
}
