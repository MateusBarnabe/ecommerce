<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Model\User;

$app = new Slim;

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

$app->get('/admin', function() {
    
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login', function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

$app->post('/admin/login', function(){
	try {
		User::login($_POST["login"], $_POST["password"]);

		header("Location: /admin");
	} catch(Exception $e) {
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false,
		]);

		$page->setTpl("login", ["error" => $e->getMessage()]);
	}

	//exit;
});

$app->post('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");
	exit;

});

$app->run();

 ?>