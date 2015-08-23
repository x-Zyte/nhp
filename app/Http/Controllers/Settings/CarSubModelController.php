<?php

namespace App\Http\Controllers\Settings;

use App\CarModel;
use App\CarSubModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function read()
    {
        $models = CarSubModel::all();
        return $models->toJson();
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