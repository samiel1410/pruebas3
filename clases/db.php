<?php
class conectarse
{
    private $conexion;

    public function conectar()
    {
        $baseDatos = "cuentas";  
      
            if (! isset($this->conexion)) {             
             
                $this->conexion = (mysqli_connect("localhost", "root", "", $baseDatos));
                mysqli_set_charset($this->conexion, "utf8");
            }
            
      
    }
    
 
    public function consulta($consulta)
    {
           
            $resultado = mysqli_query($this->conexion, $consulta);
            if (! $resultado) {
                echo 'MySQL Error: ' . mysqli_error($this->conexion);
                exit();
            }
            return $resultado;
       
    }
    

    public function llenarColeccion($consulta)
    {
        return mysqli_fetch_array($consulta);
    }

    public function ultimoId()
    {
        return mysqli_insert_id($this->conexion);
    }

    public function numeroFilas($consulta)
    {
        return mysqli_num_rows($consulta);
    }
   
}
?>