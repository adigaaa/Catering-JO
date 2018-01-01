<?php

namespace App\Middlewares;

use Slim\Views\Twig;

class OldInputMiddleware
{
	protected $view;
	public function __construct(Twig $view)
	{
		$this->view = $view;
	}
	public function __invoke($request, $response, $next)
	{
		if (isset($_SESSION['OldInput'])) {
			$this->view->getEnvironment()->addGlobal('old',$_SESSION['OldInput']);

			unset($_SESSION['OldInput']);
		}
		return $next($request, $response);
	}
}