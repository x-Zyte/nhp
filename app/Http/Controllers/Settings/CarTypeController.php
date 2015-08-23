<?php

namespace App\Http\Controllers\Settings;

use App\CarType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarTypeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.cartype');
    }

    public function read()
    {
        $models = CarType::all();
        return $models->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'detail');
        if($input['oper'] == 'add'){
            CarType::create($input);
        }
        elseif($input['oper'] == 'edit'){
            CarType::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            CarType::destroy(explode(',',$input['id']));
        }
    }
}