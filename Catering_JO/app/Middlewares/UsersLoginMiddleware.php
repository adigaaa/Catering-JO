<?php 

namespace App\Middlewares;

use Slim\Interfaces\RouterInterface;


class UsersLoginMiddleware
{
	protected $router;
	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	public function __invoke($request, $response, $next)
	{

		if (isset($_SESSION['user_id'])) {
			$response = $response->withRedirect($this->router->pathFor('homePage'));
		}
		return $next($request, $response);
	}
}