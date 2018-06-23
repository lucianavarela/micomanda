<?php
class Login
{
    public static function UserLogin($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
        $empleado = Empleado::ValidarEmpleado($ArrayDeParametros['usuario'], $ArrayDeParametros['clave']);
        if($empleado) {
            $token = AutentificadorJWT::CrearToken($empleado);
            $objDelaRespuesta = array(
                'token'=>$token,
                'usuario' => $ArrayDeParametros['usuario']
            );
            return $response->withJson($objDelaRespuesta, 200);
        } else {
            $objDelaRespuesta = array(
                'respuesta'=>'Usuario inexistente'
            );
            return $response->withJson($objDelaRespuesta, 401);
        }
    }
}