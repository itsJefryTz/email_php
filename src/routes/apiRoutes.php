<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('api', new Route('/api', [
  '_controller' => 'helloWorld'
]));

$routes->add('api_v1_sendEmail', new Route('/api/v1/sendEmail', [
  '_controller' => 'sendEmail'
]));

return $routes;
?>