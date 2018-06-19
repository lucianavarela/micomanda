<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once './composer/vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/MWparaAutentificar.php';
require_once './clases/AutentificadorJWT.php';
require_once './clases/login.php';
require_once './clases/IApiUsable.php';
require_once './clases/mesa.php';
require_once './clases/mesaApi.php';
require_once './clases/pedido.php';
require_once './clases/pedidoApi.php';
require_once './clases/comanda.php';
require_once './clases/comandaApi.php';
require_once './clases/empleado.php';
require_once './clases/empleadoApi.php';
require_once './clases/encuesta.php';
require_once './clases/encuestaApi.php';
require_once './clases/log.php';
require_once './clases/logApi.php';
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->post('/login/', \login::class . ':UserLogin');
$app->group('/api', function () use ($app) {
  $this->group('/comanda', function () use ($app) {
    $this->get('/', \comandaApi::class . ':TraerTodos');
    $this->get('/{codigoMesa}/{codigoComanda}', \comandaApi::class . ':TraerUno');
    $this->post('/', \comandaApi::class . ':CargarUno');
    $this->post('/cancelar/{id}', \comandaApi::class . ':Cancelar');
    $this->delete('/', \comandaApi::class . ':BorrarUno');
    $this->put('/', \comandaApi::class . ':ModificarUno');
  });
  $app->group('/empleado', function () use ($app) {
    $this->get('/', \empleadoApi::class . ':TraerTodos');
    $this->get('/{id}', \empleadoApi::class . ':TraerUno');
    $this->post('/tomar_pedido', \empleadoApi::class . ':TomarUnPedido');
    $this->post('/entregar_pedido', \empleadoApi::class . ':EntregarUnPedido');
    $this->post('/', \empleadoApi::class . ':CargarUno');
    $this->delete('/', \empleadoApi::class . ':BorrarUno');
    $this->put('/', \empleadoApi::class . ':ModificarUno');
  });
  $app->group('/mozo', function () use ($app) {
    $this->get('/', \mozoApi::class . ':TraerTodos');
    $this->get('/{id}', \mozoApi::class . ':TraerUno');
    $this->post('/', \mozoApi::class . ':CargarUno');
    $this->delete('/', \mozoApi::class . ':BorrarUno');
    $this->put('/', \mozoApi::class . ':ModificarUno');
  });
  $app->group('/mesa', function () use ($app) {
    $this->get('/', \mesaApi::class . ':TraerTodos');
    $this->get('/{id}', \mesaApi::class . ':TraerUno');
    $this->post('/', \mesaApi::class . ':CargarUno');
    $this->delete('/', \mesaApi::class . ':BorrarUno');
    $this->put('/', \mesaApi::class . ':ModificarUno');
  });
  $app->group('/pedido', function () use ($app) {
    $this->get('/', \pedidoApi::class . ':TraerTodos')->add(\MWparaAutentificar::class . ':VerificarToken')->add(\MWparaAutentificar::class . ':VerificarAdmin');
    $this->get('/{id}', \pedidoApi::class . ':TraerUno');
    $this->get('/pendientes/', \pedidoApi::class . ':TraerTodosPendientes');
    $this->get('/pendientes/{sector}', \pedidoApi::class . ':TraerPendientesSector');
    $this->get('/listos/', \pedidoApi::class . ':TraerTodosListos');
    $this->post('/', \pedidoApi::class . ':CargarUno');
    $this->delete('/', \pedidoApi::class . ':BorrarUno');
    $this->put('/', \pedidoApi::class . ':ModificarUno');
  });
  $app->group('/encuesta', function () use ($app) {
    $this->get('/', \encuestaApi::class . ':TraerTodos');
    $this->get('/{id}', \encuestaApi::class . ':TraerUno');
    $this->post('/', \encuestaApi::class . ':CargarUno');
    $this->delete('/', \encuestaApi::class . ':BorrarUno');
    $this->put('/', \encuestaApi::class . ':ModificarUno');
  });
  $app->group('/log', function () use ($app) {
    $this->get('/', \logApi::class . ':TraerTodos');
    $this->get('/{id}', \logApi::class . ':TraerUno');
    $this->post('/', \logApi::class . ':CargarUno');
    $this->delete('/', \logApi::class . ':BorrarUno');
    $this->put('/', \logApi::class . ':ModificarUno');
  });
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->run();