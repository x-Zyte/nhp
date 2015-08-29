<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarModel;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarSubModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarSubModelRepository(), $request);
    }
}