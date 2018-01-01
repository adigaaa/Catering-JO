<?php

namespace App\Models;

use App\Models\MProducts;
use Illuminate\Database\Eloquent\Model;

class MOrders extends Model
{
	protected $table = 'orders';

	protected $fillable = ['price','users_id','products_id'];

	public function products()
	{
		return $this->belongsTo(MProducts::class);
	}
	public function users()
	{
		return $this->belongsTo(MUsers::class);
	}
}
