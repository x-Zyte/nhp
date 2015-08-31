<?php namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Geography extends Model {

    protected $table = 'geographies';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = ['name'];
}
