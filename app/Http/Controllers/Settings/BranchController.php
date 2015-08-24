<?php

namespace App\Http\Controllers\Settings;

use App\Branch;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
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
        return view('settings.branch');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new BranchRepository(), Input::all());
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'address', 'district', 'amphur', 'province', 'zipcode');
        if($input['oper'] == 'add'){
            Branch::create($input);
        }
        elseif($input['oper'] == 'edit'){
            Branch::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            Branch::destroy(explode(',',$input['id']));
        }
    }
}