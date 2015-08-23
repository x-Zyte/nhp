<?php

namespace App\Http\Controllers\Settings;

use App\Bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.bank');
    }

    public function read()
    {
        $models = Bank::all();
        return $models->toJson();
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'detail');
        if($input['oper'] == 'add'){
            Bank::create($input);
        }
        elseif($input['oper'] == 'edit'){
            Bank::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            Bank::destroy(explode(',',$input['id']));
        }
    }
}