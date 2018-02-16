<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Population extends Model
{
    protected $table = "ur_count_all";
    public $timestamps = false;
    //public $fillable = ['DISEASE','DISNAME','total','gr'];
}
