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
		$archivos = $request->getUploadedFiles();
		$destino="./fotos/";
		$nombreAnterior=$archivos['foto']->getClientFilename();
		$extension= explode(".", $nombreAnterior)  ;
		$extension=array_reverse($extension);
		$archivos['foto']->moveTo($destino.$param1.".".$extension[0]);
		$response->getBody()->write("se guardo el encuesta");
		return $response;
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$ArrayDeParametros['id'];
		$encuesta= new Encuesta();
		$encuesta->id=$id;
		$cantidadDeBorrados=$encuesta->BorrarEncuesta();
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
		//$response->getBody()->write("<h1>Modificar  uno</h1>");
		$ArrayDeParametros = $request->getParsedBody();
		//var_dump($ArrayDeParametros);    	
		$miencuesta = new Encuesta();
		$miencuesta->id=$ArrayDeParametros['id'];
		$miencuesta->param1=$ArrayDeParametros['param1'];
		$miencuesta->param2=$ArrayDeParametros['param2'];
		$miencuesta->param3=$ArrayDeParametros['param3'];
		$resultado =$miencuesta->ModificarEncuesta();
		$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
	}
}