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
		$response->getBody()->write("Se ha ingresado la mesa #$codigo");
		return $response;
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$ArrayDeParametros['id'];
		$mesa= new Mesa();
		$mesa->id=$id;
		$cantidadDeBorrados=$mesa->BorrarMesa();
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
		$mimesa = new Mesa();
		$mimesa->id=$ArrayDeParametros['id'];
		$mimesa->param1=$ArrayDeParametros['codigo'];
		$mimesa->param2=$ArrayDeParametros['estado'];
		$mimesa->GuardarMesa();
		return $response->withJson($mimesa, 200);		
	}
}