<?php
require_once 'empleado.php';
require_once 'IApiUsable.php';
class empleadoApi extends Empleado implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$empleadoObj = Empleado::TraerEmpleado($id);
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Ver empleado";
			$new_log->GuardarLog();
		}
		//--
		$newResponse = $response->withJson($empleadoObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$empleados = Empleado::TraerEmpleados();
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Ver empleados";
			$new_log->GuardarLog();
		}
		//--
		$newResponse = $response->withJson($empleados, 200);  
		return $newResponse;
	}

	public function TomarUnPedido($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$idEmpleado = $request->getAttribute('empleado')->id;
		if ($idEmpleado && $ArrayDeParametros['idPedido'] && $ArrayDeParametros['estimacion']) {
			$respuesta=Empleado::TomarPedido($idEmpleado, $ArrayDeParametros['idPedido'], $ArrayDeParametros['estimacion']);
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Tomar un pedido";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta= new stdclass();
			$objDelaRespuesta->respuesta=$respuesta;
			return $response->withJson($objDelaRespuesta, 200);
		}
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Debe ingresar el id del empleado y el numero del pedido";
		return $response->withJson($objDelaRespuesta, 401);
	}

	public function EntregarUnPedido($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		if ($ArrayDeParametros['idPedido']) {
			$respuesta=Empleado::PedidoPreparado($ArrayDeParametros['idPedido']);
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();	
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Entregar pedido listo para servir";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta= new stdclass();
			$objDelaRespuesta->respuesta=$respuesta;
			return $response->withJson($objDelaRespuesta, 200);
		}
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Debe ingresar el numero del pedido";
		return $response->withJson($objDelaRespuesta, 401);
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
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Cargar empleado";
			$new_log->GuardarLog();
		}
		//--
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Se ha ingresado el empleado";
		return $response->withJson($objDelaRespuesta, 200);
	}

	public function BorrarUno($request, $response, $args) {
		$id=$args['id'];
		$empleado= new Empleado();
		$empleado->id=$id;
		$cantidadDeBorrados=$empleado->BorrarEmpleado();
		$objDelaRespuesta= new stdclass();
		if($cantidadDeBorrados>0) {
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Borrar empleado";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta->respuesta="Empleado eliminado";
			return $response->withJson($objDelaRespuesta, 200);
		} else {
			$objDelaRespuesta->respuesta="Error eliminando el empleado";
			return $response->withJson($objDelaRespuesta, 400);
		}
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
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Modificar empleados";
			$new_log->GuardarLog();
		}
		//--
		return $response->withJson($miempleado, 200);		
	}
}