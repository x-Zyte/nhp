<?php

namespace App\Http\Controllers\Settings;

use App\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $models = Branch::all();
        return $models->toJson();
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