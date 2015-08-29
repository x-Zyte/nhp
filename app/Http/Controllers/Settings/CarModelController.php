<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarType;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        GridEncoder::encodeRequestedData(new CarModelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarModelRepository(), $request);
    }
}