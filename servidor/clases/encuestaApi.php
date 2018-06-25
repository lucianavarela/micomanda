<?php
require_once 'encuesta.php';
require_once 'IApiUsable.php';
class encuestaApi extends Encuesta implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$encuestaObj=Encuesta::TraerEncuesta($id);
		$newResponse = $response->withJson($encuestaObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$encuestas=Encuesta::TraerEncuestas();
		$newResponse = $response->withJson($encuestas, 200);  
		return $newResponse;
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$param1= $ArrayDeParametros['param1'];
		$param2= $ArrayDeParametros['param2'];
		$param3= $ArrayDeParametros['param3'];
		$miencuesta = new Encuesta();
		$miencuesta->param1=$param1;
		$miencuesta->param2=$param2;
		$miencuesta->param3=$param3;
		$miencuesta->InsertarEncuesta();
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="Se ha ingresado la encuesta";
		return $response->withJson($objDelaRespuesta, 200);
	}

	public function BorrarUno($request, $response, $args) {
		$id=$args['id'];
		$encuesta= new Encuesta();
		$encuesta->id=$id;
		$cantidadDeBorrados=$encuesta->BorrarEncuesta();
		
		$objDelaRespuesta= new stdclass();
		if($cantidadDeBorrados>0) {
			$objDelaRespuesta->respuesta="Encuesta eliminada";
			return $response->withJson($objDelaRespuesta, 200);
		} else {
			$objDelaRespuesta->respuesta="Error eliminando la encuesta";
			return $response->withJson($objDelaRespuesta, 400);
		}
	}

	public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$miencuesta = new Encuesta();
		$miencuesta->id=$args['id'];
		$miencuesta->param1=$ArrayDeParametros['param1'];
		$miencuesta->param2=$ArrayDeParametros['param2'];
		$miencuesta->param3=$ArrayDeParametros['param3'];
		$miencuesta->GuardarEncuesta();
		return $response->withJson($miencuesta, 200);		
	}
}