<?php
class Pedido
{
    public $id;
    public $idComanda;
    public $sector;
    public $idEmpleado;
    public $descripcion;
    public $estado;
    public $fechaIngresado;
    public $fechaEstimado;
    public $fechaEntregado;
    
    public function GetIdComanda() {
        return $this->idComanda;
    }
    public function GetSector() {
        return $this->sector;
    }
    public function GetIdEmpleado() {
        return $this->idEmpleado;
    }
    public function GetDescripcion() {
        return $this->descripcion;
    }
    public function GetEstado() {
        return $this->estado;
    }

    public function SetIdComanda($value) {
        $this->idComanda = $value;
    }
    public function SetSector($value) {
        $this->sector = $value;
    }
    public function SetIdEmpleado($value) {
        $this->idEmpleado = $value;
    }
    public function SetDescripcion($value) {
        $this->descripcion = $value;
    }
    public function SetEstado($value) {
        $estados = array("pendiente", "en preparación", "listo para servir", "cerrado", "cancelado");
        if (in_array($value, $estados)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function BorrarPedido() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete
            from pedidos
            WHERE id=$this->id");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificarPedido() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update pedidos 
            set sector='$this->sector',
            idComanda='$this->idComanda',
            idEmpleado=$this->idEmpleado,
            descripcion='$this->descripcion',
            estado='$this->estado'
            WHERE id=$this->id");
        return $consulta->execute();
    }

    public static function CargarPedidos($arrayComanda, $comanda) {
        if (array_key_exists('barra', $arrayComanda)) {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'barra';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['barra'];
            $pedido_nuevo->InsertarPedido();
        }
        if (array_key_exists('cerveza', $arrayComanda)) {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'cerveza';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['cerveza'];
            $pedido_nuevo->InsertarPedido();
        }
        if (array_key_exists('cocina', $arrayComanda)) {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'cocina';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['cocina'];
            $pedido_nuevo->InsertarPedido();
        }
        if (array_key_exists('candy', $arrayComanda)) {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'candy';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['candy'];
            $pedido_nuevo->InsertarPedido();
        }
        return true;
    }

    public function InsertarPedido() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos
        (sector,descripcion,idComanda,estado)values
        ('$this->sector','$this->descripcion','$this->idComanda','$this->estado')"
        );
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function GuardarPedido() {
        if ($this->id >= 0) {
            $this->ModificarPedido();
        } else {
            $this->InsertarPedido();
        }
    }

    public static function TraerPedidos() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from pedidos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function TraerPedido($id) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from pedidos where id = $id");
        $consulta->execute();
        $pedidoResultado= $consulta->fetchObject('Pedido');
        return $pedidoResultado;
    }

    public static function TraerPendientes() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "SELECT *
            FROM pedidos
            WHERE estado = 'pendiente'"
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function TraerPendientesDeSector($sector) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "SELECT *
            FROM pedidos
            WHERE estado = 'pendiente'
            AND sector = '$sector'"
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function TraerListos() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "SELECT * FROM comandas WHERE estado = 'listo para servir'"
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public function toString() {
        return "Metodo mostar:".$this->sector."  ".$this->idEmpleado."  ".$this->descripcion;
    }
}