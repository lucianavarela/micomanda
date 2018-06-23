<?php
require_once 'empleado.php';
require_once 'IApiUsable.php';
class empleadoApi extends Empleado implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$empleadoObj = Empleado::TraerEmpleado($id);
		$newResponse = $response->withJson($empleadoObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$empleados = Empleado::TraerEmpleados();
		$newResponse = $response->withJson($empleados, 200);  
		return $newResponse;
	}

	public function TomarUnPedido($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		if ($ArrayDeParametros['idEmpleado'] && $ArrayDeParametros['idPedido'] && $ArrayDeParametros['estimacion']) {
			$respuesta=Empleado::TomarPedido($ArrayDeParametros['idEmpleado'], $ArrayDeParametros['idPedido'], $ArrayDeParametros['estimacion']);
			$response->getBody()->write($respuesta);
			return $response;
		}
		$response->getBody()->write('Debe ingresar el id del empleado y el numero del pedido');
		return $response;
	}

	public function EntregarUnPedido($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		if ($ArrayDeParametros['idPedido']) {
			$respuesta=Empleado::EntregarPedido($ArrayDeParametros['idPedido']);
			$response->getBody()->write($respuesta);
			return $response;
		}
		$response->getBody()->write('Debe ingresar el id del empleado y el numero del pedido');
		return $response;
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$miempleado = new Empleado();
		$miempleado->usuario=$ArrayDeParametros['usuario'];
		$miempleado->clave=$ArrayDeParametros['clave'];
		$miempleado->sector=$ArrayDeParametros['sector'];
		$miempleado->sueldo=$ArrayDeParametros['sueldo'];
		$miempleado->estado='activo';
		$miempleado->InsertarEmpleado();
		$response->getBody()->write("Se ingreso el empleado!");
		return $response;
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$ArrayDeParametros['id'];
		$empleado= new Empleado();
		$empleado->id=$id;
		$cantidadDeBorrados=$empleado->BorrarEmpleado();
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->cantidad=$cantidadDeBorrados;
		if($cantidadDeBorrados>0)
			{
				$objDelaRespuesta->resultado="algo borro!!!";
			}
			else
			{
				$objDelaRespuesta->resultado="no Borro nada!!!";
			}
		$newResponse = $response->withJson($objDelaRespuesta, 200);  
		return $newResponse;
	}
		
	public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$miempleado = new Empleado();
		$miempleado->id = $args['id'];
		$miempleado->usuario=$ArrayDeParametros['usuario'];
		$miempleado->clave=$ArrayDeParametros['clave'];
		$miempleado->sector=$ArrayDeParametros['sector'];
		$miempleado->sueldo=$ArrayDeParametros['sueldo'];
		$miempleado->estado=$ArrayDeParametros['estado'];
		$miempleado->GuardarEmpleado();
		return $response->withJson($miempleado, 200);		
	}
}