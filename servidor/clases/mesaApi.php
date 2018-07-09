<?php
require_once 'mesa.php';
require_once 'IApiUsable.php';
class mesaApi extends Mesa implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$mesaObj=Mesa::TraerMesa($id);
		$newResponse = $response->withJson($mesaObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$mesas=Mesa::TraerMesas();
		$newResponse = $response->withJson($mesas, 200);  
		return $newResponse;
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$mimesa = new Mesa();
		$mimesa->SetEstado('cerrada');
		$codigo = $mimesa->InsertarMesa();
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Se ha ingresado la mesa #$codigo";
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Cargar mesa";
			$new_log->GuardarLog();
		}
		//--
		return $response->withJson($objDelaRespuesta, 200);
	}

	public function BorrarUno($request, $response, $args) {
		$id=$args['id'];
		$mesa= new Mesa();
		$mesa->id=$id;
		$cantidadDeBorrados=$mesa->BorrarMesa();

		$objDelaRespuesta= new stdclass();
		if($cantidadDeBorrados>0) {
			//Cargo el log
			if ($request->getAttribute('empleado')) {
				$new_log = new Log();
				$new_log->idEmpleado = $request->getAttribute('empleado')->id;
				$new_log->accion = "Borrar mesa";
				$new_log->GuardarLog();
			}
			//--
			$objDelaRespuesta->respuesta="Mesa eliminada";
			return $response->withJson($objDelaRespuesta, 200);
		} else {
			$objDelaRespuesta->respuesta="Error eliminando la mesa";
			return $response->withJson($objDelaRespuesta, 400);
		}
	}
		
	public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$mimesa = new Mesa();
		$mimesa->id=$args['id'];
		$mimesa->param1=$ArrayDeParametros['codigo'];
		$mimesa->param2=$ArrayDeParametros['estado'];
		$mimesa->GuardarMesa();
		//Cargo el log
		if ($request->getAttribute('empleado')) {
			$new_log = new Log();
			$new_log->idEmpleado = $request->getAttribute('empleado')->id;
			$new_log->accion = "Modificar mesa";
			$new_log->GuardarLog();
		}
		//--
		return $response->withJson($mimesa, 200);		
	}
	
	public function CerrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$mesa=Mesa::TraerMesa($ArrayDeParametros['codigoMesa']);
		if ($mesa) {
			if ($mesa->estado == "con clientes pagando") {
				$mesa->CerrarMesa();
				//Cargo el log
				if ($request->getAttribute('empleado')) {
					$new_log = new Log();
					$new_log->idEmpleado = $request->getAttribute('empleado')->id;
					$new_log->accion = "Cerrar mesa";
					$new_log->GuardarLog();
				}
				//--
				$objDelaRespuesta= new stdclass();
				$objDelaRespuesta->respuesta="Mesa cerrada";
				return $response->withJson($objDelaRespuesta, 200);
			} else if ($mesa->estado == "cerrada") {
				$objDelaRespuesta= new stdclass();
				$objDelaRespuesta->respuesta="Esta mesa ya esta cerrada";
				return $response->withJson($objDelaRespuesta, 401);
			} else {
				$objDelaRespuesta= new stdclass();
				$objDelaRespuesta->respuesta="Esta mesa tiene comensales";
				return $response->withJson($objDelaRespuesta, 401);
			}
		}
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Error encontrando la mesa seleccionada";
		return $response->withJson($objDelaRespuesta, 401);
	}
}