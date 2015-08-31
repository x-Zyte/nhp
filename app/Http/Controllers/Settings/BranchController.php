<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\SystemDatas\Province;
use App\Repositories\BranchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BranchController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $provinces = Province::all(['id','name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        return view('settings.branch',['provinceselectlist' => implode(";",$provinceselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new BranchRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new BranchRepository(), $request);
    }
}