<?php

namespace App\Http\Controllers\Settings;

use App\CarType;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarTypeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.cartype');
    }

    public function read(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarTypeRepository(), Input::all());
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