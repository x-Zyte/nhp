<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model {

    protected $table = 'logs';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['employeeid', 'operation', 'date', 'model', 'detail'];
}
