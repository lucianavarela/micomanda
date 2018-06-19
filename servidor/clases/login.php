<?php
class Login
{
    public static function UserLogin($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
        $empleado = Empleado::ValidarEmpleado($ArrayDeParametros['email'], $ArrayDeParametros['clave']);
        if($empleado) {
            $token = AutentificadorJWT::CrearToken($empleado);
            return $response->getBody()->write("Bienvenido! $token");;
        } else {
            return $response->getBody()->write("Usuario inexistente.");
        }
    }
}