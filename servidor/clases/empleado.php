<?php
class Empleado
{
    public $id;
    public $email;
    public $clave;
    public $sector;
    public $estado;
    public $sueldo;
    
    public function GetEmail() {
        return $this->email;
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

    public function SetEmail($value) {
        $this->email = $value;
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
            set email='$this->email',
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
        into empleados (email,clave,sector,estado,sueldo)
        values('$this->email','$this->clave','$this->sector','$this->estado',$this->sueldo)");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function GuardarEmpleado() {
        if ($this->id >= 0) {
            $this->ModificarEmpleado();
        } else {
            $this->InsertarEmpleado();
        }
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

    public static function ValidarEmpleado($email, $clave) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados where email='$email' and clave='$clave'");
        $consulta->execute();
        $empleadoResultado= $consulta->fetchObject('Empleado');
        return $empleadoResultado;
    }

    public static function TomarPedido($id, $pedido, $tiempo) {
        $empleado=Empleado::TraerEmpleado($id);
        $empleado->estado = 'ocupado';
        $empleado->GuardarEmpleado();
        $pedido = Pedido::TraerPedido($pedido);
        $pedido->SetIdEmpleado($empleado->id);
        $pedido->estado = 'en preparación';
        $pedido->GuardarPedido();
        return "Se le ha asignado el pedido para la comanda #".$pedido->GetIdComanda().
        "\nDetalles del pedido: ".$pedido->GetDescripcion();
    }

    public static function EntregarPedido($id) {
        $pedido = Pedido::TraerPedido($id);
        $pedido->estado = 'listo para servir';
        $pedido->GuardarPedido();
        $comanda = Comanda::TraerComanda($pedido->idComanda);
        $comanda->ValidarPedidos();
        $empleado=Empleado::TraerEmpleado($pedido->idEmpleado);
        $empleado->estado = 'activo';
        $empleado->GuardarEmpleado();
        return "Pedido #$id entregado.";
    }

    public function toString() {
        return "Metodo mostar:".$this->email."  ".$this->clave."  ".$this->sector;
    }
}