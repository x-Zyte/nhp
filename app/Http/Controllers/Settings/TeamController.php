<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Repositories\TeamRepository;
use App\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TeamController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.team');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new TeamRepository(), Input::all());
    }

    public function update(Request $request)
    {
        $input = $request->only('oper', 'id', 'name', 'detail');
        if($input['oper'] == 'add'){
            Team::create($input);
        }
        elseif($input['oper'] == 'edit'){
            Team::find($input['id'])->update($input);
        }
        elseif($input['oper'] == 'del'){
            Team::destroy(explode(',',$input['id']));
        }
    }
}