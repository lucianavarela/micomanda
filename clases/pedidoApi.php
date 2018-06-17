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
		$newResponse = $response->withJson($pedidos, 200);  
		return $newResponse;
	}

	public function TraerTodosPendientes($request, $response, $args) {
		$pedidos=Pedido::TraerPendientes();
		$newResponse = $response->withJson($pedidos, 200);  
		return $newResponse;
	}

	public function TraerPendientesSector($request, $response, $args) {
		$sector=$args['sector'];
		$pedidos=Pedido::TraerPendientesDeSector($sector);
		$newResponse = $response->withJson($pedidos, 200);  
		return $newResponse;
	}

	public function TraerTodosListos($request, $response, $args) {
		$pedidos=Comanda::TraerListos();
		$newResponse = $response->withJson($pedidos, 200);  
		return $newResponse;
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
		$archivos = $request->getUploadedFiles();
		$destino="./fotos/";
		$nombreAnterior=$archivos['foto']->getClientFilename();
		$extension= explode(".", $nombreAnterior)  ;
		$extension=array_reverse($extension);
		$archivos['foto']->moveTo($destino.$sector.".".$extension[0]);
		$response->getBody()->write("se guardo el pedido");
		return $response;
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$ArrayDeParametros['id'];
		$pedido= new Pedido();
		$pedido->id=$id;
		$cantidadDeBorrados=$pedido->BorrarPedido();
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
		$mipedido = new Pedido();
		$mipedido->id=$ArrayDeParametros['id'];
		$mipedido->sector=$ArrayDeParametros['sector'];
		$mipedido->idEmpleado=$ArrayDeParametros['idEmpleado'];
		$mipedido->descripcion=$ArrayDeParametros['descripcion'];
		$resultado =$mipedido->ModificarPedido();
		$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
	}
}