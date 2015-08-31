<?php namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Amphur extends Model {

    protected $table = 'amphurs';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['code','name','geoid','provinceid'];
}
