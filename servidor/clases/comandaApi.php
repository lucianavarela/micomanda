<?php

class comandaApi extends Comanda implements IApiUsable
{
	public function TraerUno($request, $response, $args) {
		$codigoComanda=$args['codigoComanda'];
		$codigoMesa=$args['codigoMesa'];
		$comanda=Comanda::TraerComanda($codigoComanda);
		if ($comanda) {
			if ($comanda->GetIdMesa() == $codigoMesa) {
				$newResponse = $response->withJson($comanda, 200);  
			} else {
				$newResponse = array(
					'respuesta'=>"Id de Mesa incorrecto para esta comanda."
				);
			}
		} else {
			$newResponse = array(
				'respuesta'=>"Comanda inexistente."
			);
		}
		return $response->withJson($newResponse, 401);
	}

	public function TraerTodos($request, $response, $args) {
		$comandas=Comanda::TraerComandas();
		$newResponse = $response->withJson($comandas, 200);  
		return $newResponse;
	}

	public function CargarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$archivos = $request->getUploadedFiles();
		//Cargo la comanda
		$micomanda = new Comanda();
		$micomanda->SetNombreCliente($ArrayDeParametros['nombreCliente']);
		$micomanda->SetIdMesa($ArrayDeParametros['idMesa']);
		if (sizeof($archivos)) {
			$destino="./fotos/";
			$nombreAnterior=$archivos['foto']->getClientFilename();
			$extension= explode(".", $nombreAnterior)  ;
			$extension=array_reverse($extension);
			$micomanda->SetFoto($extension[0]);
		} else {
			$micomanda->SetFoto(NULL);
		}
		$codigo = $micomanda->InsertarComanda();
		if ($codigo) {
			if (Pedido::CargarPedidos($ArrayDeParametros, $codigo)) {
				//Me encargo de la foto
				if (sizeof($archivos)) {
					$archivos['foto']->moveTo($destino.$codigo.".".$extension[0]);		
				}
				$objDelaRespuesta = array(
					'respuesta'=>"Su comanda ha sido ingresada! Codigo de seguimiento: $codigo"
				);
				return $response->withJson($objDelaRespuesta, 200);
			} else {
				$objDelaRespuesta = array(
					'respuesta'=>'Su comanda ha sido ingresada, pero no se han podido cargar los pedidos de esta comanda (faltan campos)'
				);
			}
		} else {
			$objDelaRespuesta = array(
				'respuesta'=>"Esta mesa no está cargada en el sistema o está ocupada."
			);
		}
		return $response->withJson($objDelaRespuesta, 401);
	}

	public function BorrarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$id=$args['id'];
		$comanda= new Comanda();
		$comanda->id=$id;
		$cantidadDeBorrados=$comanda->BorrarComanda();
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->cantidad=$cantidadDeBorrados;
		if($cantidadDeBorrados>0)
			{
				$objDelaRespuesta->resultado="Comanda eliminado";
			}
			else
			{
				$objDelaRespuesta->resultado="Comanda inexistente";
			}
		$newResponse = $response->withJson($objDelaRespuesta, 200);  
		return $newResponse;
	}
		
	public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		$micomanda = new Comanda();
		$micomanda->id=$args['id'];
		$micomanda->nombreCliente=$ArrayDeParametros['nombreCliente'];
		$micomanda->codigo=$ArrayDeParametros['codigo'];
		$micomanda->importe=$ArrayDeParametros['importe'];
		$micomanda->idMesa=$ArrayDeParametros['idMesa'];
		$micomanda->foto=$ArrayDeParametros['foto'];
		$micomanda->GuardarComanda();
		return $response->withJson($micomanda, 200);		
	}
}