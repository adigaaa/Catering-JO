<?php 

namespace App\Middlewares;

use Slim\Views\Twig;

class UserDataMiddleware
{
	protected $view;
	public function __construct(Twig $view)
	{
		$this->view = $view;
	}
	public function __invoke($request, $response, $next)
	{
		if (!empty($_SESSION['user_id'])) {
			$this->view->getEnvironment()->addGlobal('user_id',$_SESSION['user_id']);
		}
		
		return $next($request, $response);
	}
}