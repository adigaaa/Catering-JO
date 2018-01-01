<?php 

namespace App\Middlewares;

use Slim\Views\Twig;

class InputMiddleware
{
	protected $view;
	public function __construct(Twig $view)
	{
		$this->view = $view;
	}

	public function __invoke($request, $response, $next)
	{
		if (!empty($_SESSION['errors'])) {
			$this->view->getEnvironment()->addGlobal('errors',$_SESSION['errors']);
			unset($_SESSION['errors']);
		}
		return $next($request,$response);
	}
}