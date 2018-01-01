<?php
namespace App\Controllers;

use App\Models\MCategory;

class Category extends Controller
{
	public function showCat($request, $response)
	{
		$categories =  MCategory::get();

		return $this->c->view->render($response,'adminPanel/pages/categories.twig',compact('categories'));
	}
	public function createCat($request, $response)
	{

		$data = $request->getParams(['name']);

		$categories =  MCategory::create($data);

		return $response->withRedirect($this->c->router->pathFor('cat'));
	}
	public function deleteCat($request, $response, $args)
	{

		MCategory::where('id',$args['id'])->delete();

		return $response->withRedirect($this->c->router->pathFor('cat'));
	}


}
