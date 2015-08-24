<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\InsuranceCompany;
use App\Http\Controllers\Controller;
use App\Repositories\InsuranceCompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        GridEncoder::encodeRequestedData(new InsuranceCompanyRepository(), Input::all());
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