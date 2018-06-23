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
		$idEmpleado= $ArrayDeParametros['idEmpleado'];
		$fecha= $ArrayDeParametros['fecha'];
		$accion= $ArrayDeParametros['accion'];
		$milog = new Log();
		$milog->idEmpleado=$idEmpleado;
		$milog->fecha=$fecha;
		$milog->accion=$accion;
		$milog->InsertarLog();
		$archivos = $request->getUploadedFiles();
		$destino="./fotos/";
		$nombreAnterior=$archivos['foto']->getClientFilename();
		$extension= explode(".", $nombreAnterior)  ;
		$extension=array_reverse($extension);
		$archivos['foto']->moveTo($destino.$idEmpleado.".".$extension[0]);
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
		$ArrayDeParametros = $request->getParsedBody();
		$milog = new Log();
		$milog->id=$ArrayDeParametros['id'];
		$milog->idEmpleado=$ArrayDeParametros['idEmpleado'];
		$milog->fecha=$ArrayDeParametros['fecha'];
		$milog->accion=$ArrayDeParametros['accion'];
		$milog->GuardarLog();
		return $response->withJson($milog, 200);		
	}
}