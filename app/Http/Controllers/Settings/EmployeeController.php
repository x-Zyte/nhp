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

class EmployeeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.employee');
    }

    public function read()
    {
        $employees = Employee::all();
        return $employees->toJson();
    }
}