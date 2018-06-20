<?php
class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
	public function VerificarUsuario($request, $response, $next) {
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";
	   
		if($request->isGet() || $request->isPost()) {
            $response = $next($request, $response);
		} else {
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];
            $objDelaRespuesta->esValido=true;
            
			try {
				AutentificadorJWT::verificarToken($token);
				$objDelaRespuesta->esValido=true;
			} catch (Exception $e) {
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;
			}
			if($objDelaRespuesta->esValido) {
				$payload=AutentificadorJWT::ObtenerData($token);
				if($payload->sector=="management")
				{
					$response = $next($request, $response);
				}
				else
				{
					$objDelaRespuesta->respuesta="Solo socios";
				}
			} else {
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;
			}
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }

        return $response;
	}
	
	public function VerificarToken($request, $response, $next) {
        
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";
		$arrayConToken = $request->getHeader('token');
		$token=$arrayConToken[0];
		$objDelaRespuesta->esValido=true;
		
		try {
			AutentificadorJWT::verificarToken($token);
			$objDelaRespuesta->esValido=true;
		} catch (Exception $e) {
			$objDelaRespuesta->excepcion=$e->getMessage();
			$objDelaRespuesta->esValido=false;
		}
		if($objDelaRespuesta->esValido) {
			$payload=AutentificadorJWT::ObtenerData($token);
			$request = $request->withAttribute('sector', $payload->sector);
			$response = $next($request, $response);
		} else {
			$objDelaRespuesta->respuesta="Rechazado!";
			$objDelaRespuesta->elToken=$token;
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }

        return $response;
	}

	public function VerificarAdmin($request, $response, $next) {
		$sector = $request->getAttribute('sector');
		if($sector == "management") {
			$response = $next($request, $response);
		}
		else
		{
			$objDelaRespuesta->respuesta="Solo socios";
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }

        return $response;
	}

	public function VerificarEmpleado($request, $response, $next) {
		$sector = $request->getAttribute('sector');
		if($sector == "management") {
			$response = $next($request, $response);
		}
		else
		{
			$objDelaRespuesta->respuesta="Solo socios";
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }

        return $response;
	}

	public function VerificarMozo($request, $response, $next) {
		$sector = $request->getAttribute('sector');
		if($sector == "management") {
			$response = $next($request, $response);
		}
		else
		{
			$objDelaRespuesta->respuesta="Solo socios";
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }

        return $response;
	}
	
	public function FiltrarSueldos($request, $response, $next) {
        
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";
		$objDelaRespuesta->esValido=false;
		$arrayConToken = $request->getHeader('token');
		if(sizeof($arrayConToken) > 0) {
			$token=$arrayConToken[0];
			try {
				AutentificadorJWT::verificarToken($token);
				$objDelaRespuesta->esValido=true;
			} catch (Exception $e) {
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;
			}
		}

		if($objDelaRespuesta->esValido) {
			$payload=AutentificadorJWT::ObtenerData($token);
			$response = $next($request, $response);
			if($payload->sector != "management") {
				$filtered_list = [];
				foreach ($response->getBody() as $empleado) {
					$empleado_filtrado = new stdclass();
					$empleado_filtrado->email=$empleado->email;
					$empleado_filtrado->clave=$empleado->clave;
					$empleado_filtrado->sector=$empleado->sector;
					$empleado_filtrado->estado=$empleado->estado;
					$filtered_list[] = $empleado_filtrado;
				}
				$nueva=$response->withJson($filtered_list, 200);
				return $nueva;
			}
		} else {
			$response = $next($request, $response);
			$filtered_list = [];
			foreach ($response as $empleado) {
				$empleado_filtrado = new stdclass();
				$empleado_filtrado->email=$empleado->email;
				$empleado_filtrado->clave=$empleado->clave;
				$empleado_filtrado->sector=$empleado->sector;
				$empleado_filtrado->estado=$empleado->estado;
				$filtered_list[] = $empleado_filtrado;
			}
			$nueva=$response->withJson($filtered_list, 200);
			return $nueva;
		}
        
        if($objDelaRespuesta->respuesta!="") {
			$nueva=$response->withJson($objDelaRespuesta, 401);
			return $nueva;
        }
		
        return $response;
	}
}