<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/controllers/apiController.php';

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Response;

$routes = new RouteCollection();

// Routes.
$routes->addCollection(require __DIR__ . '/src/routes/apiRoutes.php');

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
  $parameters = $matcher->match($request->getPathInfo());
  $response = call_user_func($parameters['_controller'], $request);
  $response->send();
} catch (\Exception $e) {
  $response = new Response("Error 404: No se encontró la ruta.", 404);
  $response->send();
}
?>