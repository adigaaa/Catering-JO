<?php

namespace App\Models;

use App\Models\MProducts;
use Illuminate\Database\Eloquent\Model;

class MCategory extends Model
{
	protected  $table = 'cat';
	
	protected $fillable = ['name'];

}
