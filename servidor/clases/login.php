<?php
class Login
{
    public static function UserLogin($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
        $datos = array('email' => $ArrayDeParametros['email'],'clave' => $ArrayDeParametros['clave']);
        $empleado = Empleado::ValidarEmpleado();
        if($empleado) {
            $token = AutentificadorJWT::CrearToken($empleado);
            return $response->getBody()->write("Bienvenido!");;
        } else {
            return $response->getBody()->write("Usuario inexistente.");
        }
    }
}