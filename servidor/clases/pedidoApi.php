<?php
require_once 'pedido.php';
require_once 'IApiUsable.php';
class pedidoApi extends Pedido implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$pedidoObj=Pedido::TraerPedido($id);
		$newResponse = $response->withJson($pedidoObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$pedidos=Pedido::TraerPedidos();
		/*/Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Ver pedidos";
			$new_log->GuardarLog();
		}
		/*/
		$newResponse = $response->withJson($pedidos, 200);  
		return $newResponse;
	}

	public function EntregarACliente($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		if ($ArrayDeParametros['idPedido']) {
			$respuesta=Pedido::EntregarPedido($ArrayDeParametros['idPedido']);
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Entregar pedido a cliente";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta= new stdclass();
			$objDelaRespuesta->respuesta=$respuesta;
			return $response->withJson($objDelaRespuesta, 200);
		}
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta='Debe ingresar el numero del pedido';
		return $response->withJson($objDelaRespuesta, 401);
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$sector= $ArrayDeParametros['sector'];
		$idEmpleado= $ArrayDeParametros['idEmpleado'];
		$descripcion= $ArrayDeParametros['descripcion'];
		$mipedido = new Pedido();
		$mipedido->sector=$sector;
		$mipedido->idEmpleado=$idEmpleado;
		$mipedido->descripcion=$descripcion;
		$mipedido->InsertarPedido();
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Cargar pedido";
			$new_log->GuardarLog();
		}
		//--
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta='Se guardo el pedido';
		return $response->withJson($objDelaRespuesta, 200);
	}

	public function BorrarUno($request, $response, $args) {
        $pedido = Pedido::TraerPedido($args['id']);
		$cantidadDeBorrados=$pedido->BorrarPedido();
		
		$objDelaRespuesta= new stdclass();
		if($cantidadDeBorrados>0) {
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Borrar pedido";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta->respuesta="Pedido eliminado";
			return $response->withJson($objDelaRespuesta, 200);
		} else {
			$objDelaRespuesta->respuesta="Error eliminando el pedido";
			return $response->withJson($objDelaRespuesta, 400);
		}
	}

	public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$mipedido = new Pedido();
		$mipedido->id=$args['id'];
		$mipedido->sector=$ArrayDeParametros['sector'];
		$mipedido->idEmpleado=$ArrayDeParametros['idEmpleado'];
		$mipedido->descripcion=$ArrayDeParametros['descripcion'];
		$mipedido->estimacion=$ArrayDeParametros['estimacion'];
		$mipedido->fechaIngresado=$ArrayDeParametros['fechaIngresado'];
		$mipedido->fechaEntregado=$ArrayDeParametros['fechaEntregado'];
		$mipedido->GuardarPedido();
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Modificar pedido";
			$new_log->GuardarLog();
		}
		//--
		return $response->withJson($mipedido, 200);		
	}
}