<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAdmin extends Model
{
	protected $table = 'admin';

	protected $fillabel = ['username','password','email'];
}