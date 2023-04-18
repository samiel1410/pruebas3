<?php 
include_once ("db.php");
date_default_timezone_set("America/Guayaquil");
class renumeracionMetodos{ 
    public function seleccionarTipoGeneracionRol() {
        
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $anioActual=date('Y');
        $seleccionar = $baseDatos->consulta("SELECT tipo_generacion_rol AS tipo_generacion_rol FROM configuracion_general_vacacion
                                                WHERE anio_configuracion_general_vacacion='$anioActual'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            // $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['tipo_generacion_rol'];
            return $resp;
        }
    }
    
    
    
    
    public function seleccionarCodigoGerenciaEmpleado($id_empleado){
        $baseDatos = new conectarse(); 
        $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT beneficiario_empleado
FROM empleado
WHERE id_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //   $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['beneficiario_empleado'];
            return $resp;
        }
    }
    
    public function seleccionarTipoContratoEmpleado($id_empleado) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT tipo_contrato FROM empleado WHERE id_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //$resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['tipo_contrato'];
            return $resp;
        }
    }
    
    public function seleccionarPorcentajePersonalIess($anioactual) {
    
        $baseDatos = new conectarse(); $baseDatos->conectar();
         $seleccionar = $baseDatos->consulta("SELECT porcentaje_personal_iess FROM configuracion_general_vacacion
             WHERE anio_configuracion_general_vacacion='$anioactual'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             // $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             $resp = $res['porcentaje_personal_iess'];
             return $resp;
         }
         
     }
    
    public function seleccionarTotalSueldoEmpleado($id_empleado) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        
        $seleccionar = $baseDatos->consulta("SELECT sueldo_basico_empleado AS total FROM empleado
                WHERE id_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            // $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['total'];
            return $resp;
        }
    }
    
    public function calcularSueldoHorasLaborables($horas_trabajadas,$sueldobasico) {
        $horas_total_mes=240;
        $total=($sueldobasico/$horas_total_mes)*$horas_trabajadas;
        
        $resp =$total;
        return $resp;
    }
    
    
    public function calcularSueldoDiasLaborables($dias_total_mes,$dias_trabajados,$sueldobasico) {
        //nota dias_total_mes=30;
        $total=($sueldobasico/$dias_total_mes)*$dias_trabajados;
        
        $resp =$total;
        return $resp;
    }


    public function calcularSueldoSemanaLaborables($sueldobasico){
     
        $total=($sueldobasico*12)/52;
        
        $resp =$total;
        return $resp;

    }


    
    //HORAS EXTRAORDINARIAS
    public function seleccionarTotalHorasExtraoridinariasEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT SUM(total) as total FROM horas_extras_empleado
        WHERE id_empleado='$id_empleado'
        AND MONTH(fecha) ='$mes'
        AND YEAR(fecha) ='$anio'";

if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   

}
        $seleccionar = $baseDatos->consulta($query);

    
     
            
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //  $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['total'];
            
            if(empty($res['total'])){
                $resp=0;
            }
            return $resp;
        }
    }
    
    //nuevo metodo por semana


    public function seleccionarTotalComisionesEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();

        $query="SELECT SUM(monto_total_comision) AS total FROM comision_empleado
        WHERE id_fkempleado='$id_empleado'
        AND MONTH(fecha_comision)='$mes'
        AND YEAR(fecha_comision)='$anio'";


if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha_comision BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   
}
        $seleccionar = $baseDatos->consulta($query);




        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //  $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['total'];
            
            if(empty($res['total'])){
                $resp=0;
            }
            return $resp;
        }
    }
    

    //agregar si viene tipo rol semana / dividr para 52
    
    public function calcularDecimoTercero($valortotal_pagado_por_contrato,$valorhorasextras,$valorcomisiones,$tipoGeneracionRol) {
        $divisor=12;
        if($tipoGeneracionRol=="SEMANAS"){
            $divisor=52;
        }else{
            $divisor=12;
        }
        echo "Divior:",$divisor."-","Valor:".$valortotal_pagado_por_contrato,"oras".$valorhorasextras,"ValorCom".$valorcomisiones;
        $total=($valortotal_pagado_por_contrato+$valorhorasextras+$valorcomisiones)/$divisor;
        
        $total=round($total,4);
        echo "sss",$total;
        $resp =$total;
        return $resp;
        
    }
    
    
    
    public function seleccionarTipoPagoEmpleado($id_empleado) {
        
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT  tipo_pago_beneficio_empleado FROM beneficio_empleado
WHERE id_fkempleado_beneficio_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //  $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['tipo_pago_beneficio_empleado'];
            return $resp;
        }
        
    }
    
    public function seleccionarRegionEmpleadoEmpleado($id_empleado) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT R.nombre_region_detalle AS nombre
FROM beneficio_empleado B,region_detalle R
WHERE B.id_fkempleado_beneficio_empleado='$id_empleado'
AND B.id_fkregion_beneficio_empleado IN(SELECT R.id_region_detalle
                                     FROM region_detalle
                                     WHERE R.id_region_detalle=B.id_fkregion_beneficio_empleado)");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //   $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['nombre'];
            return $resp;
        }
    }
    
    public function seleccionarFechaPorRegionDecimoCuarto($region) {
        
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT concat_ws('-', dia_decimo_cuarto_region_detalle,mes_decimo_cuarto_region_detalle,YEAR(NOW())-1)  AS fecha_region_detalle
        FROM region_detalle WHERE nombre_region_detalle='$region'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //  $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['fecha_region_detalle'];
            return $resp;
        }
        
    }
    
    
    
    public function seleccionarSueldoBasicoUnificadoFiniquito() {
        
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $anioActual=date('Y');
        $seleccionar = $baseDatos->consulta("SELECT sueldo_basico_por_ley_general_vacacion AS sueldo_basico_unificado FROM configuracion_general_vacacion
                                                WHERE anio_configuracion_general_vacacion='$anioActual'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            // $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['sueldo_basico_unificado'];
            return $resp;
        }
    }
    
    public function calcularMesesTrabajados($fecha_region_detalle,$fechaactual) {
        
        $diferenciaFechas=abs(strtotime($fecha_region_detalle) - strtotime($fechaactual));
        $anio  = floor($diferenciaFechas / (365 * 60 * 60 * 24));
        $mes = floor(($diferenciaFechas - $anio * 365  *60  *60  *24) / (30 * 60 * 60 * 24));
        
        $resp =$mes;
        // $resp =$anionuevo;
        return $resp;
        
    }
    
    public function calcularDiasTrabajados($fecha_region_detalle,$fechaactual) {
        
        $diferenciaFechas=abs(strtotime($fecha_region_detalle) - strtotime($fechaactual));
        $anio  = floor($diferenciaFechas / (365 * 60 * 60 * 24));
        $mes = floor(($diferenciaFechas - $anio * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        
        $dias   = floor(($diferenciaFechas - $anio * 365 * 60 * 60 * 24 - $mes * 30 * 60 * 60 *24) / (60 * 60 * 24));
        $resp =$dias;
        // $resp =$anionuevo;
        return $resp;
        
    }

    public function seleccionarAlicuotaMensualDecimoCuarto( $anioactual) {
    
        $baseDatos = new conectarse(); $baseDatos->conectar();
         $seleccionar = $baseDatos->consulta("SELECT alicuota_mes_decimoTercero AS alicuota_mensual
             FROM configuracion_general_vacacion WHERE anio_configuracion_general_vacacion='$anioactual'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             
             if($res['alicuota_mensual']!=0){
                 
             }else{
                 $res['alicuota_mensual']=0;
             }
             $resp = $res['alicuota_mensual'];
             return $resp;
         }
         
     }
     public function seleccionarAlicuotaDiarioDecimoCuarto($anioactual) {
         
        $baseDatos = new conectarse(); $baseDatos->conectar();
         $seleccionar = $baseDatos->consulta("SELECT alicuota_dia_decimoTercero AS alicuota_diario
             FROM configuracion_general_vacacion WHERE anio_configuracion_general_vacacion='$anioactual'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
           //  $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             
             if($res['alicuota_diario']!=0){
                 
             }else{
                 $res['alicuota_diario']=0;
             }
             $resp = $res['alicuota_diario'];
             return $resp;
         }
         
     }
    
    
    public function calcularDecimoCuarto($sueldo_basico_unificado,$numero_dias,$numero_meses,$tipo,$anioactual) {
        
        if($tipo=="ANUAL"){
            $valorMensual=$this->seleccionarAlicuotaMensualDecimoCuarto($anioactual);
            
            //controlar numero de meses respecto a cuando inicia el pago de decimo cuarto acorde a la region
            $submes=$valorMensual*$numero_meses;
            if($numero_dias>0){
                $valorDiario=$this->seleccionarAlicuotaDiarioDecimoCuarto($anioactual);
                $subdias=$valorDiario*$numero_dias;
            }else{
                $subdias=0;
            }
            $total=$submes+$subdias;
        }else{
            //mensual
            $valormensual=$this->seleccionarAlicuotaMensualDecimoCuarto($anioactual);
            $total=$valormensual;
        }
        
        
        // $resp =$total;
        return $total;
        
    }


    
    
    
    //Verificar si tiene beneficio a los fondos de reserva
    public function seleccionarFechaBeneficioEmpleadoEmpleado($id_empleado){
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT fecha_comienzo_beneficio_beneficio_empleado
FROM beneficio_empleado
WHERE Id_fkempleado_beneficio_empleado='$id_empleado';");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //   $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['fecha_comienzo_beneficio_beneficio_empleado'];
            return $resp;
        }
    }
    
    
    
    
    public function seleccionarLugarFondoReservaEmpleadoEmpleado($id_empleado) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT tipo_fondo_reserva FROM beneficio_empleado
WHERE id_fkempleado_beneficio_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            //   $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['tipo_fondo_reserva'];
            return $resp;
        }
    }
    
    
    ///CALCULAR FONDO DE RESERVA A PAGAR MENSUAL
    public function calcularFondoDeReservaMensual($sueldo_basico) {
        $total=($sueldo_basico*8.33)/100;
        $resp =$total;
        return $resp;
        
    }
    
    public function seleccionarTotalHaberesEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT SUM(monto) AS total
        FROM detalle_haberdescuento
        WHERE id_empleado='$id_empleado'
        AND MONTH(fecha)='$mes'
        AND YEAR(fecha)='$anio'
        AND id_haber_descuento IN(SELECT id
                                 FROM haber_descuento
                                 WHERE id=id_haber_descuento
                                 AND tipo=1
                                 AND egreso_anticipo=0
                                 AND quincena_haber_descuento=0)";


if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   
}

        $seleccionar = $baseDatos->consulta($query);



        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            
            $res = $baseDatos->llenarColeccion($seleccionar);
            
            if(!empty($res['total'])){
                $resp = $res['total'];
            }else{
                $resp = 0;
            }
            return $resp;
        }
    }
    
    
    public function calcularfondoDeReservaGerencia($sueldo ,$porcentaje) {
        $total=($sueldo*$porcentaje)/100;
        return $total;
    }
    
    public function seleccionarPorcentajePersonalIessEmpleado($anio) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
         
         $seleccionar = $baseDatos->consulta("SELECT porcentaje_personal_iess AS porcentaje
         FROM configuracion_general_vacacion
         WHERE anio_configuracion_general_vacacion='$anio'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             // $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             $resp = $res['porcentaje'];
             return $resp;
         }
     }


     public function seleccionarPorcentajeCecapIessEmpleado($anio) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
         
         $seleccionar = $baseDatos->consulta("SELECT secap_iess AS porcentaje
     FROM configuracion_general_vacacion
     WHERE anio_configuracion_general_vacacion='$anio'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             //  $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             $resp = $res['porcentaje'];
             return $resp;
         }
     }

     public function calcularAportacionIessMensualEmpresa($id_empleado,$anio_actual,$baseImponible) {
        
        $porcentaje=$this->seleccionarPorcentajePatronalIessEmpleado($anio_actual);        
        
        $total=($baseImponible*$porcentaje)/100;
        
        $resp =$total;
        return $resp;
        
    }

    public function seleccionarPorcentajePatronalIessEmpleado($anio) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
         
         $seleccionar = $baseDatos->consulta("SELECT porcentaje_patronal_iess AS porcentaje
         FROM configuracion_general_vacacion
         WHERE anio_configuracion_general_vacacion='$anio'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             //  $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             $resp = $res['porcentaje'];
             return $resp;
         }
     }

     public function seleccionarPorcentajeIeceIessEmpleado($anio) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
         
         $seleccionar = $baseDatos->consulta("SELECT iece_iess AS porcentaje
     FROM configuracion_general_vacacion
     WHERE anio_configuracion_general_vacacion='$anio'");
         if ($baseDatos->numeroFilas($seleccionar) > 0) {
             //  $resp = array();
             $res = $baseDatos->llenarColeccion($seleccionar);
             $resp = $res['porcentaje'];
             return $resp;
         }
     }
    
    public function calcularAportacionIessMensualEmpleado($id_empleado,$anio_actual,$baseImponible) {
        
        
        $porcentaje=$this->seleccionarPorcentajePersonalIessEmpleado($anio_actual);
        
        $total=($baseImponible*$porcentaje)/100;
        
        $resp =$total;
        return $resp;
        
    }
    
  
    
    public function calcularAportacionSecapMensualEmpresa($id_empleado,$anio_actual,$baseImponible) {
        
        $porcentaje=$this->seleccionarPorcentajeCecapIessEmpleado($anio_actual);
        
        
        $total=($baseImponible*$porcentaje)/100;
        
        $resp =$total;
        return $resp;
        
    }
    
    public function calcularAportacionIeceMensualEmpresa($id_empleado,$anio_actual,$baseImponible) {
        
        $porcentaje=$this->seleccionarPorcentajeIeceIessEmpleado($anio_actual);
        
        
        $total=($baseImponible*$porcentaje)/100;
        
        $resp =$total;
        return $resp;
        
    }
    
    
    public function seleccionarIdPrestamoRol($id_empleado) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $seleccionar = $baseDatos->consulta("SELECT id_prestamo FROM prestamo_empleado WHERE estado_prestamo='NO PAGADO'
AND id_fkempleado_empleado='$id_empleado'");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            $resp = $res['id_prestamo'];
            if(empty($res['id_prestamo'])){
                $resp=0;
            }
            
        }else{
            $resp=0;
        }
        return $resp;
    }
    
    
    // PRESTAMOS
    public function seleccionarTotalPrestamosEmpleado($id_prestamo,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();

        $query="SELECT monto_detalle_prestamo AS total FROM detalle_prestamo
        WHERE id_fkprestamo='$id_prestamo'
        AND estado_detalle_prestamo='NO PAGADO'
        AND YEAR(fecha_cuota_detalle_prestamo)='$anio'
        AND MONTH(fecha_cuota_detalle_prestamo)='$mes'";
        $seleccionar = $baseDatos->consulta($query);

if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha_cuota_detalle_prestamo BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   
}
        
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            
            if($res['total']!=0){
                
            }else{
                $res['total']=0;
            }
            $resp = $res['total'];
            
        }  else{
            $resp=0;
        }
        return $resp;
    }
    
    
    public function seleccionarTotalRetencionJudicialEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        
        $seleccionar = $baseDatos->consulta("SELECT  SUM(monto_retencion_judicial_empleado)  as total
from retencion_judicial_empleado
WHERE id_fkempleado='$id_empleado'
AND '$mes' BETWEEN  MONTH(fecha_inicial_retencion_judicial_empleado) AND MONTH(fecha_final_retencion_empleado)
AND '$anio' BETWEEN  YEAR(fecha_inicial_retencion_judicial_empleado) AND YEAR(fecha_final_retencion_empleado)");



        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            $resp = array();
            $i = 0;
            while ($res = $baseDatos->llenarColeccion($seleccionar)) {
                if( $res['total']!=0){
                    $resp[$i] = $res['total'];
                }else{
                    $resp[$i]  =0;
                }
                $i ++;
            }
            
            return $resp;
        }else{
            
            $resp[0] =0;
            return $resp;
        }
    }
    
    // IMPUESTO A LA RENTA
    public function seleccionarTotalImpuestoRentaEmpleado($id_empleado,$porcentaje,$sueldo) {
        
        $total=$sueldo*$porcentaje/100;
        
        return $total;
        
    }
    
    
    
    // QUINCENA
    public function seleccionarTotalQuincenaEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT SUM(monto) AS total
        FROM detalle_haberdescuento,haber_descuento H
        WHERE id_empleado='$id_empleado'
        AND MONTH(fecha)='$mes'
        AND YEAR(fecha)='$anio'
        AND estado='PAGADO'
        AND id_haber_descuento IN (SELECT H.id
                                    FROM haber_descuento
                                    WHERE H.id=id_haber_descuento
                                    AND H.tipo=0
                                    AND H.egreso_anticipo=0
                                      AND H.quincena_haber_descuento=1)";

                                      
if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   
}

        $seleccionar = $baseDatos->consulta($query);


        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            
            $res = $baseDatos->llenarColeccion($seleccionar);
            
            if(!empty($res['total'])){
                $resp = $res['total'];
            }else{
                $resp = 0;
            }
            return $resp;
        }
    }
    
    
    // ANTICIPO
    public function seleccionarTotalAnticiposEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT SUM(monto) AS total
        FROM detalle_haberdescuento,haber_descuento H
        WHERE id_empleado='$id_empleado'
        AND MONTH(fecha)='$mes'
        AND YEAR(fecha)='$anio'
        AND estado='PAGADO'
        AND id_haber_descuento IN (SELECT H.id
                                    FROM haber_descuento
                                    WHERE H.id=id_haber_descuento
                                    AND H.tipo=0
                                    AND H.egreso_anticipo=1
                                      AND H.quincena_haber_descuento=0)";
        
  
        if($tipoGeneracionRol=="SEMANAS"){
            $query .=" AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            
        }



        $seleccionar = $baseDatos->consulta($query);



        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            
            $res = $baseDatos->llenarColeccion($seleccionar);
            
            if(!empty($res['total'])){
                $resp = $res['total'];
            }else{
                $resp = 0;
            }
            return $resp;
        }
    }
    
    
    //DESCUENTOS
    
    public function seleccionarTotalDescuentosEmpleado($id_empleado,$mes,$anio ,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT SUM(monto) AS total
        FROM detalle_haberdescuento,haber_descuento H
        WHERE id_empleado='$id_empleado'
        AND MONTH(fecha)='$mes'
        AND YEAR(fecha)='$anio'
        AND estado='PAGADO'
        AND id_haber_descuento IN (SELECT H.id
                                    FROM haber_descuento
                                    WHERE H.id=id_haber_descuento
                                    AND H.tipo=0
                                    AND H.egreso_anticipo=0
                                      AND H.quincena_haber_descuento=0)";

if($tipoGeneracionRol=="SEMANAS"){
    $query .=" AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
   
    
}
        $seleccionar = $baseDatos->consulta( $query);


        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            
            $res = $baseDatos->llenarColeccion($seleccionar);
            
            if(!empty($res['total'])){
                $resp = $res['total'];
            }else{
                $resp = 0;
            }
            // ECHO $resp;
            return $resp;
        }
    }
    
    public function seleccionarRolDePagoEmpleado($id_empleado,$mes,$anio,$tipoGeneracionRol,$fecha_inicio,$fecha_fin) {
        $baseDatos = new conectarse(); $baseDatos->conectar();
        $query="SELECT id_rol_de_pagos
        FROM rol_de_pagos
        WHERE id_empleado='$id_empleado'
        AND mes='$mes'
        AND anio='$anio'";
        if($tipoGeneracionRol=="SEMANAS"){
            $query .=" AND fecha_inicio >='$fecha_inicio' and fecha_fin <= '$fecha_fin'";
            
            
        }

  

       
        $seleccionar = $baseDatos->consulta($query);


        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            $resp = array();
            $i = 0;
            while ($res = $baseDatos->llenarColeccion($seleccionar)) {
                $resp[$i]['id_rol_de_pagos'] = $res['id_rol_de_pagos'];
                $i ++;
            }
            
        }else{
            $resp[0]['id_rol_de_pagos']='none';
        }
        return $resp;
    }
    
    
    ////ADMINISTRACION ROL DE PAGOS
    public function insertarRolDePagoEmpleado($id_empleado,$sueldo_basico,$horas_extras,$comisiones
        ,$decimo_tercero,$decimo_cuarto,$fondo_reserva,$otros_ingresos,$total_ingresos,$aportacion_iess_rol_pagos,
        $prestamo,$retencion_judicial,$impuesto_renta,$quincena,$anticipo,$otros_descuento,$total_descuentos
        ,$valor_neto,$mes,$anio,$porcentaje_aporte_iess,$estado_rol_pago,$dias_total_mes,$dias_trabajados,
        $aportacion_patronal_empresa, $aportacion_secap_empresa, $aporte_iece_empresa,
        $base_imponible,$tipo_rol,$fecha_inicio,$fecha_fin) {
            $baseDatos = new conectarse(); $baseDatos->conectar();
            $insertarRetencionJudicial=$baseDatos->consulta("INSERT INTO rol_de_pagos( id_empleado,
 sueldo_basico, horas_extras, comisiones, decimo_tercero, decimo_cuarto,fondo_reserva,
otros_ingresos,total_ingresos,aportacion_iess_rol_pagos, prestamo, retencion_judicial,
 impuesto_renta, quincena, anticipo, otros_descuento, total_descuentos,
 valor_neto, mes, anio, porcentaje_aporte_iess, estado_rol_pago, dias_total_mes,
dias_trabajados,aportacion_patronal_empresa,aportacion_secap_empresa,aporte_iece_empresa,base_imponible,tipo_generacion_rol_pagos, fecha_inicio ,fecha_fin) VALUES ('$id_empleado',
'$sueldo_basico','$horas_extras','$comisiones','$decimo_tercero','$decimo_cuarto','$fondo_reserva'
,'$otros_ingresos','$total_ingresos','$aportacion_iess_rol_pagos','$prestamo','$retencion_judicial'
,'$impuesto_renta','$quincena','$anticipo','$otros_descuento','$total_descuentos',
'$valor_neto','$mes','$anio','$porcentaje_aporte_iess','$estado_rol_pago','$dias_total_mes',
'$dias_trabajados','$aportacion_patronal_empresa','$aportacion_secap_empresa',
'$aporte_iece_empresa','$base_imponible','$tipo_rol','$fecha_inicio','$fecha_fin')");
            if($insertarRetencionJudicial){
                $resp=$baseDatos->ultimoId();
                
                //Generar Asiento de registro
               // $metodosAsiento=new metodosAsientos();
                //$metodosAsiento->insertarAsientoRegistroRemuneracion($resp);
                
                
                
            }	else{
                $resp=0;
            }
            return $resp;
    }
    
    
    public function   actualizarRolDePagoEmpleado($id_empleado,$sueldo_basico,$horas_extras,$comisiones
        ,$decimo_tercero,$decimo_cuarto,$fondo_reserva,$otros_ingresos,$total_ingresos,$aportacion_iess_rol_pagos,
        $prestamo,$retencion_judicial,$impuesto_renta,$quincena,$anticipo,$otros_descuento,$total_descuentos
        ,$valor_neto,$mes,$anio,$porcentaje_aporte_iess,$estado_rol_pago,$dias_total_mes,$dias_trabajados,
        $aportacion_patronal_empresa, $aportacion_secap_empresa, $aporte_iece_empresa,
        $base_imponible) {
            $baseDatos = new conectarse(); $baseDatos->conectar();
            $insertarRetencionJudicial=$baseDatos->consulta("UPDATE rol_de_pagos
SET
sueldo_basico='$sueldo_basico',
horas_extras='$horas_extras',
comisiones='$comisiones',
decimo_tercero='$decimo_tercero',
decimo_cuarto='$decimo_cuarto',
fondo_reserva='$fondo_reserva',
otros_ingresos='$otros_ingresos',
total_ingresos='$total_ingresos',
aportacion_iess_rol_pagos='$aportacion_iess_rol_pagos',
prestamo='$prestamo',
retencion_judicial='$retencion_judicial',
impuesto_renta='$impuesto_renta',
quincena='$quincena',
anticipo='$anticipo',
otros_descuento='$otros_descuento',
total_descuentos='$total_descuentos',
valor_neto='$valor_neto',
porcentaje_aporte_iess='$porcentaje_aporte_iess',
estado_rol_pago='$estado_rol_pago',
dias_total_mes='$dias_total_mes',
dias_trabajados='$dias_trabajados' ,
aportacion_patronal_empresa='$aportacion_patronal_empresa',
aportacion_secap_empresa='$aportacion_secap_empresa',
aporte_iece_empresa='$aporte_iece_empresa',
base_imponible='$base_imponible'

WHERE  id_empleado='$id_empleado'
AND anio='$anio'
AND mes='$mes'");
            if($insertarRetencionJudicial){
               // $metodosAsiento=new metodosAsientos();
                
                //$metodosAsiento->editarRegistroRemuneracion($id_empleado, $anio,$mes);
                $resp=1;
            }	else{
                $resp=0;
            }
            return $resp;
    }
    
}



?>