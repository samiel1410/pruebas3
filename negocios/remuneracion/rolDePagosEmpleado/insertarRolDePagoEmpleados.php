<?php
include_once '../../../clases/RemuneracionMetodosAux.php';
$metodos = new renumeracionMetodos();

//seleccionar empleados
//
//$anio=$_POST['anio'];
//$mes=$_POST['mes'];

$anio='2023';
$mes='04';

//Semana
$fecha_inicio="2023-04-01";
$fecha_fin="2023-04-07";
//$dias_trabajados=$_POST['dias_trabajados'];
//$record=$_POST['recordListado'];

//Crear attay de empleados estaticos
$semana =7;

$record ='[{"id_empleado":"12","dias_laborados":30,"tipo_generacion_rol_pagos":"SEMANAS"},{"id_empleado":"57","dias_laborados":12,"tipo_generacion_rol_pagos":"SEMANAS"}]';

$seleccionarEmpleados = json_decode($record, true);


//$seleccionarEmpleados=$metodos->seleccionarEmpleados();

for ($i = 0; $i < count($seleccionarEmpleados); $i ++) {
    
    $id_empleado[$i] = $seleccionarEmpleados[$i]['id_empleado'];
    $dias_trabajado[$i] = $seleccionarEmpleados[$i]['dias_laborados'];
   
}

//tipo rol
for ($i = 0; $i < count($seleccionarEmpleados); $i ++) {
    
    $tipo_rol[$i] = $seleccionarEmpleados[$i]['tipo_generacion_rol_pagos'];
  
   
}





//seleccionar la cantidad de empleados
$numeroEmpleados=count($seleccionarEmpleados);
$tipoGeneracionRol=$metodos->seleccionarTipoGeneracionRol();
//recorrer el empleado

for ($i = 0; $i < $numeroEmpleados; $i++) {
    
    $dias_trabajados=$dias_trabajado[$i];
    
    //INGRESOS
    $codigogerencia=$metodos->seleccionarCodigoGerenciaEmpleado($id_empleado[$i]);
    
    
    //TIPO DE CONTRATO
    $tipo_contrato=$metodos->seleccionarTipoContratoEmpleado($id_empleado[$i]);
    
    //echo "tipocontrato :( ".$tipo_contrato." ) ";
    
    
    $porcentajeIess=$metodos->seleccionarPorcentajePersonalIess($anio);
    
    //SUELDO EMPLEADO CAMBIAR METODO
    
    $seleccionarsueldoAsignado=$metodos->seleccionarTotalSueldoEmpleado($id_empleado[$i]);
    
    /*NUEVO*/
    
    
    if($tipoGeneracionRol=="HORAS"){
        
        $seleccionarsueldo=$metodos->calcularSueldoHorasLaborables($dias_trabajados,$seleccionarsueldoAsignado);
        $seleccionarsueldoAsignado=$seleccionarsueldo;
    }else if($tipoGeneracionRol=='DIAS'){
        $seleccionarsueldo=$metodos->calcularSueldoDiasLaborables('30',$dias_trabajados,$seleccionarsueldoAsignado);
        
    }else if($tipoGeneracionRol=='SEMANAS'){
        $seleccionarsueldo =$metodos->calcularSueldoSemanaLaborables($seleccionarsueldoAsignado);
        
    }
    

    
    //HORAS EXTRAS
    //agregar filtros
    $seleccionarhorasextras=$metodos->seleccionarTotalHorasExtraoridinariasEmpleado($id_empleado[$i],$mes, $anio, $tipo_rol[$i],$fecha_inicio,$fecha_fin);
    //COMISION
    //garegar filtros
    $seleccionarcomision=$metodos->seleccionarTotalComisionesEmpleado($id_empleado[$i],$mes,$anio, $tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    //cpontrolR si la fecha actual es igual reporte
    
    //DECIMO TERCERO
    if($codigogerencia!='2'){
        if($tipoGeneracionRol=="SEMANAS"){
            $seleccionardecimotercero=$metodos->calcularDecimoTercero($seleccionarsueldoAsignado, $seleccionarhorasextras, $seleccionarcomision, $tipo_rol[$i]);

        }else{
            $seleccionardecimotercero=$metodos->calcularDecimoTercero($seleccionarsueldo, $seleccionarhorasextras, $seleccionarcomision, $tipo_rol[$i]);
        }
        
     
        $tipo_pago=$metodos->seleccionarTipoPagoEmpleado($id_empleado[$i]);
        
        // ECHO "TIPO PAGO ".$tipo_pago;
        
        if(empty($seleccionardecimotercero)){
            $seleccionardecimotercero=0;
        }
        if($tipo_pago=='ANUAL'){
            $seleccionardecimotercero=0;
        }
        if($tipo_contrato=='10'){
            $seleccionardecimotercero=0;
        }
    }else{
        $seleccionardecimotercero=0;
    }
    
 
    //DECIMO CUARTO
    if($codigogerencia!='2'){
        $fechaactual=date('d-m-Y');
        $nombre_region=$metodos->seleccionarRegionEmpleadoEmpleado($id_empleado[$i]);
        
        
        
        
        $fecha_region_detalle=$metodos->seleccionarFechaPorRegionDecimoCuarto($nombre_region);
        $sueldobasicounificado=$metodos->seleccionarSueldoBasicoUnificadoFiniquito();
        
        $numeroMeses=$metodos->calcularMesesTrabajados($fecha_region_detalle,$fechaactual);
        $numeroDias=$metodos->calcularDiasTrabajados($fecha_region_detalle,$fechaactual);
        
        //
        $seleccionardecimocuarto=$metodos->calcularDecimoCuarto($sueldobasicounificado, $numeroDias,
            $numeroMeses, $tipo_contrato, $anio);
        
        if(empty($seleccionardecimocuarto)){
            $seleccionardecimocuarto=0;
        }
        if($tipo_pago=='ANUAL'){
            $seleccionardecimocuarto=0;
        }
        
        if($tipo_contrato==10){
            $seleccionardecimocuarto=0;
        }
    }else{
        $seleccionardecimocuarto=0;
    }
    
    
    //echo "cuarto : ".$seleccionardecimocuarto;
    //FONDO DE RESERVA
    if($codigogerencia!='2'){
        $fechabeneficio=$metodos->seleccionarFechaBeneficioEmpleadoEmpleado($id_empleado[$i]);
        //CONSULTAR SI VA AL ROL DE PAGO
        $fecha_actual = date("Y-m-d");
        
        
        if($fecha_actual >= $fechabeneficio){
            $lugarfondoreserva=$metodos->seleccionarLugarFondoReservaEmpleadoEmpleado($id_empleado[$i]);
            
            if($lugarfondoreserva=='rol_pago'){
               // echo $seleccionarfondodereserva."esta";
                $seleccionarfondodereserva=$metodos->calcularFondoDeReservaMensual($seleccionarsueldo);
                
            }else if($lugarfondoreserva=='iess'){
                $seleccionarfondodereserva=0;
            }
        }else{
            $seleccionarfondodereserva=0;
        }
    }else{
        $seleccionarfondodereserva=0;
    }
    
    
    //otros ingresos
    //agregar filtros -1
    $seleccionarotrosIngresos=$metodos->seleccionarTotalHaberesEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    $TotalIngresos= $seleccionarsueldo+$seleccionarhorasextras
    +$seleccionarcomision+$seleccionardecimotercero
    +$seleccionardecimocuarto+$seleccionarfondodereserva+$seleccionarotrosIngresos;
    
    //EGRESOS
    
    //BASE IMPONIBLE IESS
    
    $baseImponibleIess=$TotalIngresos-$seleccionarcomision-$seleccionarotrosIngresos-$seleccionardecimocuarto-$seleccionardecimotercero-$seleccionarfondodereserva;
    
    //obtener si el empleado tiene codigo gerencia
    
    
    
    //***************************************   AQUI ***********************************************************//
    if($codigogerencia=='2'){
        
        //CALCULAR EL $
        $porcentaje=$metodos->seleccionarPorcentajeGerenciaEmpleado();
        $porcentajeIess=$porcentaje;
        
        $seleccionarseguroEmpleado=$metodos->calcularfondoDeReservaGerencia($seleccionarsueldoAsignado,$porcentaje);
        
    }else{
        
        $seleccionarseguroEmpleado=$metodos->calcularAportacionIessMensualEmpleado($id_empleado[$i],$anio,$baseImponibleIess);
    }
    //***************************************************************************************************//
    
    $aportacion_patronal_empresa=$metodos->calcularAportacionIessMensualEmpresa($id_empleado[$i], $anio,$baseImponibleIess);
    
    $aportacion_secap_empresa=$metodos->calcularAportacionSecapMensualEmpresa($id_empleado[$i], $anio,$baseImponibleIess);
    
    $aporte_iece_empresa=$metodos->calcularAportacionIeceMensualEmpresa($id_empleado[$i], $anio,$baseImponibleIess);
    
    $aportacion_patronal_empresa=round($aportacion_patronal_empresa,4);
    $aportacion_secap_empresa=round($aportacion_secap_empresa,4);
    $aporte_iece_empresa=round($aporte_iece_empresa,4);
    $seleccionarseguroEmpleado=round($seleccionarseguroEmpleado,4);
    
    //PRESTAMO
    $seleccionaridPrestamo=$metodos->seleccionarIdPrestamoRol($id_empleado[$i]);
    
    //agregar filtros -0
    $seleccionarprestamo=$metodos->seleccionarTotalPrestamosEmpleado($seleccionaridPrestamo,$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    
    //Retencion Judicial
    //agregar filtros -0
    $seleccionarretencionjudicial=$metodos->seleccionarTotalRetencionJudicialEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    //Impuesto Renta
    $porcentaje =9.45;
    
    if($tipo_contrato==10){
        $seleccionarimpuesto = $metodos->seleccionarTotalImpuestoRentaEmpleado($id_empleado[$i],$porcentaje,$seleccionarsueldo,$tipo_contrato);
        
    }else{
        $seleccionarimpuesto=0;
    }
    
    //echo "impuesto : ".$seleccionarimpuesto;
    //QUINCENA EMPLEADO
        //agregar filtros -1
    $seleccionarquincena=$metodos->seleccionarTotalQuincenaEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    //echo "QUINCENA : ".$seleccionarquincena;
    //ANTICIPOS
        //agregar filtros - 1
    $seleccionaranticipo=$metodos->seleccionarTotalAnticiposEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    // echo "ANTICIPO : ".$seleccionaranticipo;
    //OTROS
        //agregar filtros
    $seleccionarotrosdescuentos=$metodos->seleccionarTotalDescuentosEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    
    // echo "OTROS : ".$seleccionarotrosdescuentos;
    
    $TotalEgresos= $seleccionarseguroEmpleado+$seleccionarprestamo
    +$seleccionarretencionjudicial[0]+$seleccionarimpuesto
    +$seleccionarquincena+$seleccionaranticipo+$seleccionarotrosdescuentos;
    
 
    $valor_neto=$TotalIngresos-$TotalEgresos;
      //agregar filtros
      //variable dias =30
      //horas=240
      //semana=52;
      if($tipo_rol[$i]=="SEMANAS"){
        $var_aux=52;

      }

      else if($tipo_rol[$i]=="DIAS"){
        $var_aux=30;

      }else{
        $var_aux=240;
      }
    $verificarSiExisteRegistro=$metodos->seleccionarRolDePagoEmpleado($id_empleado[$i],$mes, $anio,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    if($verificarSiExisteRegistro[0]['id_rol_de_pagos']=='none'){
        $seleccionar = $metodos->insertarRolDePagoEmpleado($id_empleado[$i],
            $seleccionarsueldoAsignado,
            $seleccionarhorasextras,
            $seleccionarcomision,
            $seleccionardecimotercero,
            $seleccionardecimocuarto,
            $seleccionarfondodereserva,
            $seleccionarotrosIngresos,
            $TotalIngresos,
            $seleccionarseguroEmpleado,
            $seleccionarprestamo,
            $seleccionarretencionjudicial[0],
            $seleccionarimpuesto,
            $seleccionarquincena,
            $seleccionaranticipo,
            $seleccionarotrosdescuentos,
            $TotalEgresos,
            $valor_neto,
            $mes,
            $anio,
            $porcentajeIess,
            'NO PAGADO',
            $var_aux,
            $dias_trabajados,
            $aportacion_patronal_empresa,
            $aportacion_secap_empresa,
            $aporte_iece_empresa,
            $baseImponibleIess,$tipo_rol[$i],$fecha_inicio,$fecha_fin);
    }else{
        $seleccionar = $metodos->actualizarRolDePagoEmpleado($id_empleado[$i],
            $seleccionarsueldoAsignado,
            $seleccionarhorasextras,
            $seleccionarcomision,
            $seleccionardecimotercero,
            $seleccionardecimocuarto,
            $seleccionarfondodereserva,
            $seleccionarotrosIngresos,
            $TotalIngresos,
            $seleccionarseguroEmpleado,
            $seleccionarprestamo,
            $seleccionarretencionjudicial[0],
            $seleccionarimpuesto,
            $seleccionarquincena,
            $seleccionaranticipo,
            $seleccionarotrosdescuentos,
            $TotalEgresos,
            $valor_neto,
            $mes,
            $anio,
            $porcentajeIess,
            'NO PAGADO',
            $var_aux,
            $dias_trabajados,
            $aportacion_patronal_empresa,
            $aportacion_secap_empresa,
            $aporte_iece_empresa,
            $baseImponibleIess,$tipo_rol,$fecha_inicio,$fecha_fin);
    } 
    
    
    
}
echo "{success : true, message : $seleccionar}";
?>
