<?php

namespace App\Controllers;

use App\Models\MCategory;
use App\Models\MProducts;
use Respect\Validation\Validator as v;

class Product extends Controller 
{

	public function productDelete($request, $response, $args)
	{
		$result = MProducts::find($args['id']);
		
		unlink(__DIR__.'/../../resource/uploads'.DIRECTORY_SEPARATOR . $result->image);
		$result->delete();
		return $response->withRedirect($this->c->router->pathFor('adminPanel'));
	}

	public function viewProducts($request, $response)
	{
		$products =  MProducts::get();

		return  $this->c->view->render($response,'adminPanel/pages/viewProducts.twig',compact('products'));
	}

	public function addProduct($request, $response)
	{
		$data = $request->getParams(['name','cat_id','description','price']);

		$uploadedFiles = $request->getUploadedFiles();

		$uploadedFile = $uploadedFiles['image'];
		
		$result = $this->c->validator->validate($request,[

			'name'=>v::notEmpty(),

			'cat_id'=>v::notEmpty(),

			'description'=>v::notEmpty(),

			'price'=>v::notEmpty(),
		]);
		
		$checkFile = v::image()->validate($uploadedFile->file);

		if (!$checkFile) {

			$_SESSION['errors'] = ['image' => ['Please Upload FIle !!']];

			return $response->withRedirect($this->c->router->pathFor('formAddProduct'));
		}
		
		if (file_exists($this->c->upload_directory . DIRECTORY_SEPARATOR . $uploadedFile->getClientFilename())) {
			/*
				resource/uploads/image.jpg 
			 */
			$_SESSION['errors'] = ['image' => ['file aleady exists !!']];

			return $response->withRedirect($this->c->router->pathFor('formAddProduct'));
		}

		if ($result->isFalid()) {

			$_SESSION['OldInput'] = $data;

			$_SESSION['errors'] = $result->getErrors();

			return $response->withRedirect($this->c->router->pathFor('formAddProduct'));
		}
		$eFile = str_replace(' ','_',$uploadedFile->getClientFilename());
		$eFile = str_replace('-','_',$eFile);
		$uploadedFile->moveTo($this->c->upload_directory . DIRECTORY_SEPARATOR . $eFile);
		
		$data['image'] = $eFile;

		$result = MProducts::create($data);

		return $response->withRedirect($this->c->router->pathFor('adminPanel'));
	}

	public function formProduct($request, $response)
	{
		$categories = MCategory::get();

		return $this->c->view->render($response, 'adminPanel/pages/addForm.twig',compact('categories'));
	}

	public function updateProduct($request, $response,$args)
	{

		$old = MProducts::find($args['id']);
		$categories = MCategory::get();

		if ($request->getMethod() !=='POST') {
			return $this->c->view->render($response,'adminPanel/pages/updateForm.twig',compact('old','categories'));
		}
		MProducts::where('id',$args['id'])->update($request->getParams());
		return $response->withRedirect($this->c->router->pathFor('product.update',['id'=>$args['id']]));

	}

	public function showByCatId($request, $response, $args)
	{
		$products = MProducts::where('cat_id',$args['id'])->get();
		$categories = MCategory::get();
		foreach ($products as $value) {
			$value->cat_name = $value->category()->find($value->cat_id)->name;
		}
		return $this->c->view->render($response,'usersView/showByCatId.twig',compact('products','categories'));
	}
}
