<?php
/**
 * Created by IntelliJ IDEA.
 * User: garaccii
 * Date: 2019-02-12
 * Time: 20:20
 */

namespace Webdimension\SessionHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAuthMiddleware
{
 public $redirect_path;

 public function __construct($redirect_path = '/')
{
 $this->redirect_path = $redirect_path;
}

 public function __invoke(Request $request, Response $response, callable $next)
 {
	if(!$_SESSION['administrator_info']->auth_result){
	 $response = $response->withRedirect($this->redirect_path);
	 return $response;
	}
	 $response = $next($request, $response);
	 return $response;
 }
}