<?php

namespace App\Http\Controllers\Settings;

use App\CarModel;
use App\CarType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarModelController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartypes = CarType::all(['id','name']);
        $cartypeselectlist = array();
        foreach($cartypes as $ct){
            array_push($cartypeselectlist,$ct->id.':'.$ct->name);
        }

        return view('settings.carmodel', ['cartypeselectlist' => implode(";",$cartypeselectlist)]);
    }

    public function read()
    {
        $models = CarModel::all();
        return $models->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'cartypeid', 'name', 'detail');
        if($input['oper'] == 'add'){
            CarModel::create($input);
        }
        elseif($input['oper'] == 'edit'){
            CarModel::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            CarModel::destroy(explode(',',$input['id']));
        }
    }
}