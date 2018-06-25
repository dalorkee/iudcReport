<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
	protected $fillable = ['disease', 'disname', 'tdis', 'ntdis', 'gr', 'gr_th', 'list'];
}
