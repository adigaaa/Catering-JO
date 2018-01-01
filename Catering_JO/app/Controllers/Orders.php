<?php

namespace App\Controllers;

use App\Models\MOrders;
use App\Models\MProducts;

class Orders extends Controller 
{
	public function orderForm($request, $response,$args)
	{
		$product= MProducts::find($args['p_id']);

		if (is_null($product)) {
			return $response->withRedirect($this->c->router->pathFor('homePage'));
		}

		return $this->c->view->render($response, 'usersView/orderForm.twig',compact('product'));
	}
	public function addOrder($request, $response)
	{
	 	$order =  $request->getParams();

	 	$order['users_id'] = $this->c->session->get('user_id');

	 	MOrders::create($order);

	 	return $response->withRedirect($this->c->router->pathFor('homePage'));
	}

	public function deleteOrder($request, $response, $args)
	{
		$order =  MOrders::find($args['id']);
		switch ($order) {
			case isset($_SESSION['login']):
				$order->delete();
				return $response->withRedirect($this->c->router->pathFor('orders'));
			break;
			case $_SESSION['user_id'] == $order->users_id:
				$order->delete();
				return $response->withRedirect($this->c->router->pathFor('my.orders'));
			break;
			default:
				return $response->withRedirect($this->c->router->pathFor('homePage'));
		}
	}

	public function showOrders($request, $response)
	{
		$orders =  MOrders::get();

		foreach ($orders as $key => $value) {
			
			$value->product = $value->products()->find($value->products_id)->name;

			$value->username = $value->users()->find($value->users_id)->username;
		}

		$orders = $orders->toArray();

		return $this->c->view->render($response, 'adminPanel/pages/orders.twig',compact('orders'));
	}

	public function showOrdersById($request, $response, $args)
	{

	}

	public function myOrders($request, $response)
	{
		$orders =  MOrders::where('users_id',$this->c->session->get('user_id'))->get();
		foreach ($orders as $key => $value) {
			$value->product = $value->products()->find($value->products_id)->name;
		}

		$orders = $orders->toArray();

		return $this->c->view->render($response, 'usersView/orders.twig',compact('orders'));

	}
}
