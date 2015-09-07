<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Facades\GridEncoder;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branchs = Branch::orderBy('name', 'asc')->get(['id', 'name']);
        $branchselectlist = array();
        array_push($branchselectlist,':เลือกสาขา');
        foreach($branchs as $item){
            array_push($branchselectlist,$item->id.':'.$item->name);
        }

        $carmodels = CarModel::orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodelids = Car::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return view('car',
            ['branchselectlist' => implode(";",$branchselectlist),
            'carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CarRepository(), Input::all());
    }

    public function update(Request $request)
    {
        //$input = $request->only('receivecarfilepath','deliverycarfilepath');
        //return $input;
        GridEncoder::encodeRequestedData(new CarRepository(), $request);
    }

    public function upload()
    {
        $error = false;
        $uploaddir = base_path().'/uploads/images/';
        $engineno = Input::get('engineno');

        $car = Car::where('engineno', $engineno)->first();

        if(Input::hasFile('receivecarfile') && Input::file('receivecarfile')->isValid()){
            $extension = Input::file('receivecarfile')->getClientOriginalExtension();
            $fileName = $engineno.'_received'.'.'.$extension;
            $upload_success = Input::file('receivecarfile')->move($uploaddir, $fileName);
            if($upload_success)
                $car->receivecarfilepath = '/uploads/images/'.$fileName;
            else
                $error = true;
        }
        if(Input::hasFile('deliverycarfile') && Input::file('deliverycarfile')->isValid()){
            $extension = Input::file('deliverycarfile')->getClientOriginalExtension();
            $fileName = $engineno.'_delivered'.'.'.$extension;
            $upload_success = Input::file('deliverycarfile')->move($uploaddir, $fileName);
            if($upload_success)
                $car->deliverycarfilepath = '/uploads/images/'.$fileName;
            else
                $error = true;
        }

        $car->save();

        $data = ($error) ? array('error' => 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์') : array('success' => 'อัพโหลดไฟล์สำเร็จ');
        echo json_encode($data);
    }

    public function readSelectlistForDisplayInGrid()
    {
        $carsubmodelids = Car::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกเขต/อำเภอ');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist)];
    }
}