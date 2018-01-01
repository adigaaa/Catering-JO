<?php 

namespace App\Controllers;

use App\Models\MAdmin;
use App\Models\MCategory;
use App\Models\MOrders;
use App\Models\MProducts;
use App\Models\MUsers;
use Respect\Validation\Validator as v;

class CAdmin extends Controller
{
	public function index($request, $response)
	{
		$categories = MCategory::count();
		$products = MProducts::count();
		$users = MUsers::count();
		$orders = MOrders::count();

		return $this->c->view->render($response,'adminPanel/pages/index.twig',compact('categories','products','users','orders'));
	}

	public function loginPage($request, $response)
	{

		if ($this->c->session->exists('login')) {

			return $response->withRedirect($this->c->router->pathFor('adminPanel'));
		}

		return $this->c->view->render($response,'adminPanel/pages/login.twig');
	}

	public function login($request, $response)
	{

		$result =	$this->c->validator->validate($request,[

						'username'=>v::notEmpty()->noWhitespace(),

						'password'=>v::length(5)->notEmpty(),

					]);
		
		if ($result->isFalid()) {

			return $response->withRedirect($this->c->router->pathFor('admin.login'));
		}

		$input = $request->getParams(['username','password']);

		$dbResult =  MAdmin::where('username',$input['username'])->get()->first();

		if ($dbResult === null) {

			$_SESSION['errors'] = ['username'=>['username is not exist']];

			return $response->withRedirect($this->c->router->pathFor('admin.login'));
		}

		if (!password_verify($input['password'],$dbResult->password)) {
			
			$_SESSION['errors'] = ['password'=>['password incorrect !']];

			return $response->withRedirect($this->c->router->pathFor('admin.login'));
		}

		$this->c->session->set('login',true);

		$this->c->session->set('username',$input['username']);

		return $response->withRedirect($this->c->router->pathFor('adminPanel'));
	}
	public function logout($request, $response)
	{
		$this->c->session::destroy();
		return $response->withRedirect($this->c->router->pathFor('admin.login'));
	}
}
