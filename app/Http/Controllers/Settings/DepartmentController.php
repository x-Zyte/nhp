<?php

namespace App\Http\Controllers\Settings;

use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.department');
    }

    public function read()
    {
        $models = Department::all();
        return $models->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'detail');
        if($input['oper'] == 'add'){
            Department::create($input);
        }
        elseif($input['oper'] == 'edit'){
            Department::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            Department::destroy(explode(',',$input['id']));
        }
    }
}