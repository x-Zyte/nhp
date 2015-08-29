<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Facades\GridEncoder;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class CustomerController extends Controller {

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

        return view('customer',
            ['branchselectlist' => implode(";",$branchselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CustomerRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CustomerRepository(), $request);
    }
}