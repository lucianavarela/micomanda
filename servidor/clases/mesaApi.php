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
		return $response->withJson($objDelaRespuesta, 200);
	}

	public function BorrarUno($request, $response, $args) {
		$id=$args['id'];
		$mesa= new Mesa();
		$mesa->id=$id;
		$cantidadDeBorrados=$mesa->BorrarMesa();

		$objDelaRespuesta= new stdclass();
		if($cantidadDeBorrados>0) {
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
		return $response->withJson($mimesa, 200);		
	}
}