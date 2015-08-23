<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers\Settings;


use App\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->isadmin == 1){

        }

        return view('settings.employee');
    }

    public function read()
    {
        $employees = Employee::all();
        return $employees->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('id', 'firstname', 'lastname', 'isadmin', 'branchid', 'departmentid', 'teamid', 'active');
        if($input['isadmin'] == 1){
            $input = $request->only('id', 'firstname', 'lastname', 'isadmin', 'active');
        }
        $employee = Employee::find($input['id']);
        $employee->update($input);
        return response()->json(['error' => false, 'message' => ''],200);

    }
}