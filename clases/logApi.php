<?php
require_once 'log.php';
require_once 'IApiUsable.php';
class logApi extends Log implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$id=$args['id'];
		$logObj=Log::TraerLog($id);
		$newResponse = $response->withJson($logObj, 200);  
		return $newResponse;
	}

	public function TraerTodos($request, $response, $args) {
		$logs=Log::TraerLogs();
		$newResponse = $response->withJson($logs, 200);  
		return $newResponse;
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$param1= $ArrayDeParametros['param1'];
		$param2= $ArrayDeParametros['param2'];
		$param3= $ArrayDeParametros['param3'];
		$milog = new Log();
		$milog->param1=$param1;
		$milog->param2=$param2;
		$milog->param3=$param3;
		$milog->InsertarLog();
		$archivos = $request->getUploadedFiles();
		$destino="./fotos/";
		$nombreAnterior=$archivos['foto']->getClientFilename();
		$extension= explode(".", $nombreAnterior)  ;
		$extension=array_reverse($extension);
		$archivos['foto']->moveTo($destino.$param1.".".$extension[0]);
		$response->getBody()->write("se guardo el log");
		return $response;
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$ArrayDeParametros['id'];
		$log= new Log();
		$log->id=$id;
		$cantidadDeBorrados=$log->BorrarLog();
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
		$milog = new Log();
		$milog->id=$ArrayDeParametros['id'];
		$milog->param1=$ArrayDeParametros['param1'];
		$milog->param2=$ArrayDeParametros['param2'];
		$milog->param3=$ArrayDeParametros['param3'];
		$resultado =$milog->ModificarLog();
		$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
	}
}