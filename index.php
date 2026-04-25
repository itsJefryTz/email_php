<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_controller' => function () {
  return new Response('Hello, World!');
}]));

$routes->add('api', new Route('/api', ['_controller' => function () {
  return new JsonResponse([
    'message' => 'Hello, API!',
    'status' => 'success'
  ]);
}]));

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
  $parameters = $matcher->match($request->getPathInfo());
  $response = call_user_func($parameters['_controller']);
  $response->send();
} catch (\Exception $e) {
  $response = new Response("Error 404: No se encontró la ruta.", 404);
  $response->send();
}
?>