<?php

namespace App\Http\Controllers\Settings;

use App\CarModel;
use App\CarSubModel;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarSubModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Psy\Util\Json;

class CarSubModelController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carmodels = CarModel::all(['id','name']);
        $carmodelselectlist = array();
        foreach($carmodels as $cm){
            array_push($carmodelselectlist,$cm->id.':'.$cm->name);
        }

        return view('settings.carsubmodel', ['carmodelselectlist' => implode(";",$carmodelselectlist)]);
    }

    public function read(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarSubModelRepository(), Input::all());
        //$models = CarSubModel::all();
        //return json_encode(array('page' => 1, 'total' => 2, 'records' => 11, 'rows' => $models));
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'carmodelid', 'name', 'detail');
        if($input['oper'] == 'add'){
            CarSubModel::create($input);
        }
        elseif($input['oper'] == 'edit'){
            CarSubModel::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            CarSubModel::destroy(explode(',',$input['id']));
        }
    }
}