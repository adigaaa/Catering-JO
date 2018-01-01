<?php

namespace App\Controllers;

use App\Models\MCategory;
use App\Models\MProducts;
use App\Models\MUsers;
use Respect\Validation\Validator as v;

class CUsers extends Controller
{

	public function getAllUsers($request, $response)
	{
		$users = MUsers::get();
		return $this->c->view->render($response,'adminPanel/pages/users.twig',compact('users'));
	}

	public function search($request, $response)
	{

		$result = 	$this->c->validator->validate($request,[
						'product'=>v::notEmpty(),
					]);

		if ($result->isFalid()) {
			$_SESSION['errors'] = $result->getErrors();
			return $response->withRedirect($this->c->router->pathFor('homePage'));
		}
		$input = $request->getParam('product');
		$products = MProducts::where('name','LIKE','%'.$input.'%')->get();
		$categories = MCategory::get();
		foreach ($products as  $value) {
			$value->cat_name = $value->category()->find($value->cat_id)->name;
		}

		return $this->c->view->render($response, 'usersView/main.twig',compact('products','categories'));
	}

	public function homePage($request, $response)
	{
		$categories = MCategory::get();

		$products = MProducts::get();
		foreach ($products as  $value) {

			$value->cat_name = $value->category()->find(1)->name;
		}
		$imagePath = $this->c->upload_directory;

		return $this->c->view->render($response, 'usersView/main.twig',[
			'categories'=>$categories,
			'products'=>$products,
			'path'=>$imagePath,
		]);	
	}
	public function registerAndLogin($request, $response)
	{

		if ($this->c->session->exists('user_id')) {

			return $response->withRedirect($this->c->router->pathFor('homePage'));
		}

		return $this->c->view->render($response, 'usersView/login.twig');
	}

	public function usersStore($request, $response)
	{

		$result =	$this->c->validator->validate($request,[

						'username'=>v::length(4)->notEmpty()->noWhitespace(),

						'password'=>v::length(5)->notEmpty(),

						'email'=>v::email()->notEmpty()->noWhitespace(),
					]);

		$input = $request->getParams();

		if ($result->isFalid()) {

			$_SESSION['OldInput'] = $result;

			return $response->withRedirect($this->c->router->pathFor('login.register'));
		}

		$confirmP = $input['confirmP'];

		$password = $input['password'];

		if (strcmp($password,$confirmP) !== 0) {

			$_SESSION['OldInput'] = $input;

			$_SESSION['errors'] = ['password' => ['The password Dosen\'t  match !!']];

			return $response->withRedirect($this->c->router->pathFor('login.register'));
		}

		$input['password'] = password_hash($password, PASSWORD_DEFAULT);

		unset($input['confirmP']);

		$result =  MUsers::create($input);
		
		return $response->withRedirect($this->c->router->pathFor('login.register'));
	}

	public function usersLogin($request, $response)
	{

		$username = $request->getParam('username');

		$password = $request->getParam('password');

		$result = MUsers::where('username',$username)->get()->first();

		if ($result === null) {

			$_SESSION['errors'] = ['username' => ['username Not Found']];

			return $response->withRedirect($this->c->router->pathFor('login.register'));
		}
		if (!password_verify($password, $result->password)) {

			$_SESSION['errors'] = ['password' => ['password  incorrect !']];

			return $response->withRedirect($this->c->router->pathFor('login.register'));
		}

		$this->c->session->set('username',$result->username);

		$this->c->session->set('user_id',$result->id);

		return $response->withRedirect($this->c->router->pathFor('homePage'));
	}

	public function usersLogout($request, $response)
	{
		$this->c->session::destroy();

		return $response->withRedirect($this->c->router->pathFor('homePage'));
	}
	public function deleteUser($request, $response,$args)
	{

		MUsers::find($args['id'])->delete();
		return $response->withRedirect($this->c->router->pathFor('get.all.users'));
	}

}
