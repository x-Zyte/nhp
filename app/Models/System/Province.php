<?php namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {

    protected $table = 'provinces';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','geoid'];
}
