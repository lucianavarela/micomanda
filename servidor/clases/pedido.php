<?php
class Pedido
{
    public $id;
    public $idComanda;
    public $sector;
    public $idEmpleado;
    public $descripcion;
    public $estado;
    public $estimacion;
    public $fechaIngresado;
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
    public function GetFechaIngresado() {
        return $this->fechaIngresado;
    }
    public function GetEstimacion() {
        return $this->estimacion;
    }
    public function GetFechaEntregado() {
        return $this->fechaEntregado;
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
        $this->estado = $value;
    }
    public function SetFechaIngresado($value) {
        $this->fechaIngresado = $value;
    }
    public function SetEstimacion($value) {
        $this->estimacion = $value;
    }
    public function SetFechaEntregado($value) {
        $this->fechaEntregado = $value;
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
            estado='$this->estado',
            estimacion='$this->estimacion',
            fechaIngresado='$this->fechaIngresado',
            fechaEntregado='$this->fechaEntregado'
            WHERE id=$this->id");
        return $consulta->execute();
    }

    public static function CargarPedidos($arrayComanda, $comanda) {
        if ($arrayComanda['barra'] != '') {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'barra';
            $pedido_nuevo->estado = 'pendiente';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['barra'];
            $pedido_nuevo->InsertarPedido();
        }
        if ($arrayComanda['cerveza'] != '') {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'cerveza';
            $pedido_nuevo->estado = 'pendiente';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['cerveza'];
            $pedido_nuevo->InsertarPedido();
        }
        if ($arrayComanda['cocina'] != '') {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'cocina';
            $pedido_nuevo->estado = 'pendiente';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['cocina'];
            $pedido_nuevo->InsertarPedido();
        }
        if ($arrayComanda['candy'] != '') {
            $pedido_nuevo = new Pedido();
            $pedido_nuevo->sector = 'candy';
            $pedido_nuevo->estado = 'pendiente';
            $pedido_nuevo->idComanda = $comanda;
            $pedido_nuevo->descripcion = $arrayComanda['candy'];
            $pedido_nuevo->InsertarPedido();
        }
        return true;
    }

    public function InsertarPedido() {
        $datetime_now = date("Y-m-d H:i:s");
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos
        (sector,descripcion,idComanda,estado,fechaIngresado)values
        ('$this->sector','$this->descripcion','$this->idComanda','$this->estado','$datetime_now')"
        );
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function GuardarPedido() {
        if ($this->id > 0) {
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
            "SELECT * FROM pedidos WHERE estado = 'listo para servir'"
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function TraerPedidosPorComanda($codigoComanda) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "SELECT * FROM pedidos WHERE idComanda = '$codigoComanda'"
        );
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public function toString() {
        return "Metodo mostar:".$this->sector."  ".$this->idEmpleado."  ".$this->descripcion;
    }
    
    public static function EntregarPedido($id) {
        $pedido = Pedido::TraerPedido($id);
        $pedido->estado = 'entregado';
        $pedido->GuardarPedido();
        $comanda=Comanda::TraerComanda($pedido->idComanda);
        $todos_pedidos_listos = true;
        $pedidos_pendientes_de_comanda = Pedido::TraerPedidosPorComanda($comanda->codigo);
        foreach ($pedidos_pendientes_de_comanda as $pedido) {
            if (!($pedido->estado == 'entregado')) {
                $todos_pedidos_listos = false;
                break;
            }
        }
        if ($todos_pedidos_listos) {
            $mesa=Mesa::TraerMesa($comanda->idMesa);
            $mesa->estado = 'con clientes comiendo';
            $mesa->GuardarMesa();
        }
        return "Pedido #$id entregado.";
    }
}