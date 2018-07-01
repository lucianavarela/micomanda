<?php
class Empleado
{
    public $id;
    public $usuario;
    public $clave;
    public $sector;
    public $estado;
    public $sueldo;
    
    public function GetUsuario() {
        return $this->usuario;
    }
    public function GetClave() {
        return $this->clave;
    }
    public function GetSector() {
        return $this->sector;
    }
    public function GetEstado() {
        return $this->estado;
    }
    public function GetSueldo() {
        return $this->sueldo;
    }

    public function SetUsuario($value) {
        $this->usuario = $value;
    }
    public function SetClave($value) {
        $this->clave = $value;
    }
    public function SetSector($value) {
        $this->sector = $value;
    }
    public function SetEstado($value) {
        $this->estado = $value;
    }
    public function SetSueldo($value) {
        $this->sueldo = $value;
    }
    
    public function BorrarEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete
            from empleados
            WHERE id=$this->id");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificarEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update empleados 
            set usuario='$this->usuario',
            clave='$this->clave',
            sector='$this->sector',
            estado='$this->estado',
            sueldo=$this->sueldo
            WHERE id=$this->id;");
        return $consulta->execute();
    }

    public function InsertarEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT
        into empleados (usuario,clave,sector,estado,sueldo)
        values('$this->usuario','$this->clave','$this->sector','$this->estado',$this->sueldo)");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function GuardarEmpleado() {
        if ($this->id > 0) {
            $this->ModificarEmpleado();
        } else {
            $this->InsertarEmpleado();
        }
    }

    public function DeshabilitarEmpleado() {
        $this->estado = "deshabilitado";
        $this->GuardarEmpleado();
    }

    public function ActivarEmpleado() {
        $this->estado = "activo";
        $this->GuardarEmpleado();
    }

    public static function TraerEmpleados() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Empleado");
    }

    public static function TraerEmpleado($id) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados where id = $id");
        $consulta->execute();
        $empleadoResultado= $consulta->fetchObject('Empleado');
        return $empleadoResultado;
    }

    public static function ValidarEmpleado($usuario, $clave) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados where usuario='$usuario' and clave='$clave'");
        $consulta->execute();
        $empleadoResultado= $consulta->fetchObject('Empleado');
        return $empleadoResultado;
    }

    public function TomarPedido($pedido, $tiempo) {
        $this->estado = 'ocupado';
        $empleado->GuardarEmpleado();
        $pedido = Pedido::TraerPedido($pedido);
        $pedido->SetEstimacion($tiempo);
        $pedido->SetIdEmpleado($this->id);
        $pedido->estado = 'en preparación';
        $pedido->GuardarPedido();
        return "Se le ha asignado el pedido para la comanda #".$pedido->GetIdComanda().
        "\nDetalles del pedido: ".$pedido->GetDescripcion();
    }

    public static function PedidoPreparado($id) {
        $pedido = Pedido::TraerPedido($id);
        $pedido->estado = 'listo para servir';
        $pedido->fechaEntregado = date("Y-m-d H:i:s");
        $pedido->GuardarPedido();
        $empleado=Empleado::TraerEmpleado($pedido->idEmpleado);
        $empleado->estado = 'activo';
        $empleado->GuardarEmpleado();
        return "Pedido #$id entregado.";
    }

    public function toString() {
        return "Metodo mostar:".$this->usuario."  ".$this->clave."  ".$this->sector;
    }
}