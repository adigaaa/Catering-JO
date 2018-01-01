<?php
session_start();
require_once __DIR__.'/../vendor/autoload.php';

$app = new Slim\App([
	'settings'=>[

		'displayErrorDetails'=>true,

		'db'=>[

            'driver'=>'mysql',

            'host'=>'localhost',

            'database'=>'sara',

            'username'=>'root',

            'password'=>'',

            'port'=>3306,

            'charset'=>'utf8',

            'collation'=>'utf8_unicode_ci',

            'prefix'=>'',
        ],
	]
]);




$container = $app->getContainer();


$container['upload_directory'] = __DIR__ . '/../resource/uploads';

$app->add(new \Slim\Middleware\Session([

  'name' => 'dummy_session',

  'autorefresh' => true,

  'lifetime' => '1 hour'
]));

$container['session'] = function () {
    
  return new \SlimSession\Helper;
};

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function() use ($capsule){
     
    return $capsule;
};

$container['validator'] = function(){
    return new  App\Validators\Validator;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resource/views/', [
        'cache' => false,
    ]);
    
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};
