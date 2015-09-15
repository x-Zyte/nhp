<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarModelColorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarModelColorController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read()
    {
        $input = Input::all();
        if(in_array("filters", $input)){
            $input = Input::all();
            $filters = json_decode(str_replace('\'','"',$input['filters']), true);
            array_push($filters['rules'],array("field"=>"carmodelid","op"=>"eq","data"=>$input['carmodelid']));
            $input['filters'] = json_encode($filters);
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"carmodelid","op"=>"eq","data"=>$input['carmodelid'])))));
        }
        GridEncoder::encodeRequestedData(new CarModelColorRepository(), $input);
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarModelColorRepository(), $request);
    }
}