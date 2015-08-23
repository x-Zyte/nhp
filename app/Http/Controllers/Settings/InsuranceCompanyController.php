<?php

namespace App\Http\Controllers\Settings;

use App\InsuranceCompany;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InsuranceCompanyController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.insurancecompany');
    }

    public function read()
    {
        $models = InsuranceCompany::all();
        return $models->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'detail');
        if($input['oper'] == 'add'){
            InsuranceCompany::create($input);
        }
        elseif($input['oper'] == 'edit'){
            InsuranceCompany::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            InsuranceCompany::destroy(explode(',',$input['id']));
        }
    }
}