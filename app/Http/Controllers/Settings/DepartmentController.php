<?php

namespace App\Http\Controllers\Settings;

use App\Department;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        GridEncoder::encodeRequestedData(new DepartmentRepository(), Input::all());
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