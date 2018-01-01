<?php

namespace App\Middlewares;

use Slim\Interfaces\RouterInterface;

class AdminLoginMiddleware
{

	protected $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	public function __invoke($request, $response, $next)
	{
		if (empty($_SESSION['login'])) {
			
			return $response->withRedirect($this->router->pathFor('admin.login'));
		}

		return $next($request, $response);
	}
}
