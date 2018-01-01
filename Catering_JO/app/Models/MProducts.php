<?php

namespace App\Models;

use App\Models\MCategory;
use Illuminate\Database\Eloquent\Model;

class MProducts extends Model
{
	protected $table = 'products';

	protected $fillable = ['name','description','image','cat_id','price'];

	public function category()
	{
		return $this->belongsTo(MCategory::class,'cat_id');
	}
}
