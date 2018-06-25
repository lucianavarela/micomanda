<?php
class Encuesta
{
    public $id;
    public $param1;
    public $param2;
    public $param3;
    
    public function GetParam1() {
        return $this->param1;
    }
    public function GetParam2() {
        return $this->param2;
    }
    public function GetParam3() {
        return $this->param3;
    }

    public function SetParam1($value) {
        $this->param1 = $value;
    }
    public function SetParam2($value) {
        $this->param2 = $value;
    }
    public function SetParam3($value) {
        $this->param3 = $value;
    }
    
    public function BorrarEncuesta() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete
            from encuestas
            WHERE id=$this->id");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificarEncuesta() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update encuestas 
            set param1='$this->param1',
            param2='$this->param2',
            param3='$this->param3'
            WHERE id=$this->id");
        return $consulta->execute();
    }

    public function InsertarEncuesta() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into encuestas (param1,param2,param3)values('$this->param1','$this->param2','$this->param3')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function GuardarEncuesta() {
        if ($this->id > 0) {
            $this->ModificarEncuesta();
        } else {
            $this->InsertarEncuesta();
        }
    }

    public static function TraerEncuestas() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select id,param1 as param1, param2 as param2,param3 as param3 from encuestas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Encuesta");
    }

    public static function TraerEncuesta($id) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select id, param1 as param1, param2 as param2,param3 as param3 from encuestas where id = $id");
        $consulta->execute();
        $encuestaResultado= $consulta->fetchObject('Encuesta');
        return $encuestaResultado;
    }

    public function toString() {
        return "Metodo mostar:".$this->param1."  ".$this->param2."  ".$this->param3;
    }
}