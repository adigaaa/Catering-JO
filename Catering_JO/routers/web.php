<?php

$app->add(new App\Middlewares\OldInputMiddleware($container['view']));

use App\Controllers\Product;
use App\Controllers\Category;
use App\Controllers\CAdmin;
use App\Controllers\CUsers;
use App\Controllers\Orders;

$app->get('/adminPanel/login',CAdmin::class.':loginPage')->setName('admin.login')
->add(new App\Middlewares\InputMiddleware($container['view']));

$app->post('/adminPanel/login',CAdmin::class . ':login')->setName('login.check');

$app->get('/adminPanel/logout',CAdmin::class.':logout')->setName('admin.logout');


$app->group('/adminPanel',function() use ($container){

	$this->get('/',CAdmin::class.':index')->setName('adminPanel');

	$this->get('/users',CUsers::class .':getAllUsers')->setName('get.all.users');

	$this->get('/cat',Category::class .':showCat')->setName('cat');

	$this->post('/cat/add',Category::class .':createCat')->setName('addCat');

	$this->get('/cat/delete/{id}',Category::class.':deleteCat');

	$this->get('/product/form',Product::class.':formProduct')->setName('formAddProduct')
	->add(new App\Middlewares\InputMiddleware($container['view']));


	$this->post('/product/form/add',Product::class.':addProduct')->setName('storeProduct');

	$this->get('/orders',Orders::class .':showOrders')->setName('orders');

	$this->get('/product/view',Product::class.':viewProducts')->setName('view.product');

	$this->get('/product/update/[{id}]',Product::class.':updateProduct')->setName('product.update');

	$this->post('/product/update/[{id}]',Product::class.':updateProduct')->setName('update');

	$this->get('/product/delete/[{id}]',Product::class.':productDelete')->setName('product.delete');

	$this->get('/user/delete/[{id}]',CUsers::class.':deleteUser')->setName('delete.users');

})->add(new App\Middlewares\AdminLoginMiddleware($container['router']));

$app->get('/delete/[{id}]',Orders::class.':deleteOrder')->setName('order.delete');

$app->group('',function() use ($container){


	$this->get('/',CUsers::class.':homePage')->setName('homePage');

	$this->post('/search',CUsers::class.':search')->setName('search');

	$this->get('/login-register',CUsers::class.':registerAndLogin')->setName('login.register')
	->add(new App\Middlewares\UsersLoginMiddleware($container['router']))
	->add(new App\Middlewares\InputMiddleware($container['view']));

	$this->get('/orderForm/[{p_id}]',Orders::class.':orderForm')->setName('orderForm')->add(new App\Middlewares\OrdersMiddleware($container['router']));

	$this->post('/addOrder',Orders::class.':addOrder')->setName('add.order');

	$this->get('/myOrders',Orders::class.':myOrders')->setName('my.orders')->add(new App\Middlewares\OrdersMiddleware($container['router']));;

	$this->post('/login',CUsers::class . ':usersLogin')->setName('login');


	$this->post('/register',CUsers::class . ':usersStore')->setName('register');


	$this->get('/logout' , CUsers::class. ':usersLogout')->setName('logout');

	$this->get('/cat/[{id}]',Product::class .':showByCatId')->setName('search.by.cat');
	$this->get('/about',function($request, $response){
		$this->view->render($response,'/usersView/about.twig');
	})->setName('about');

})->add(new App\Middlewares\UserDataMiddleware($container['view']));
/*
user data Middleware
*/