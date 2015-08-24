<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers\Settings;


use App\Branch;
use App\Department;
use App\Employee;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branchs = Branch::all(['id','name']);
        $branchselectlist = array();
        array_push($branchselectlist,':เลือกสาขา');
        foreach($branchs as $item){
            array_push($branchselectlist,$item->id.':'.$item->name);
        }

        $departments = Department::all(['id','name']);
        $departmentselectlist = array();
        array_push($departmentselectlist,':เลือกแผนก');
        foreach($departments as $item){
            array_push($departmentselectlist,$item->id.':'.$item->name);
        }

        $teams = Team::all(['id','name']);
        $teamselectlist = array();
        array_push($teamselectlist,':เลือกทีม');
        foreach($teams as $item){
            array_push($teamselectlist,$item->id.':'.$item->name);
        }

        return view('settings.employee',
            ['branchselectlist' => implode(";",$branchselectlist),
            'departmentselectlist' => implode(";",$departmentselectlist),
            'teamselectlist' => implode(";",$teamselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new EmployeeRepository(), Input::all());
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'firstname', 'lastname', 'username', 'email', 'isadmin', 'branchid', 'departmentid', 'teamid', 'active');
        if($input['isadmin'] == '1'){
            $input = $request->only('oper', 'id', 'firstname', 'lastname', 'username', 'email', 'isadmin', 'active');
        }

        if($input['oper'] == 'add'){
            Employee::create($input);
        }
        elseif($input['oper'] == 'edit'){
            Employee::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            Employee::destroy(explode(',',$input['id']));
        }
    }

    public function check_username(Request $request)
    {
        $input = $request->only('id','username');

        $count = Employee::where('id','!=', $input['id'])
            ->where('username', $input['username'])->count();
        if($count > 0){
            return "true";
        }
    }

    public function check_email(Request $request)
    {
        $input = $request->only('id','email');

        $count = Employee::where('id','!=', $input['id'])
            ->where('email', $input['email'])->count();
        if($count > 0){
            return "true";
        }
    }
}