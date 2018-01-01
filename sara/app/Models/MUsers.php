<?php

namespace App\Models;

use App\Models\MOrders;
use Illuminate\Database\Eloquent\Model;

class MUsers extends Model
{
	protected $table = 'users';

	protected $fillable = [ 'username' , 'password', 'email' ,];

}
