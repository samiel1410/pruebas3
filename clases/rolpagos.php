<?php
require_once "db.php";
require_once 'pdf/library/tcpdf.php';
date_default_timezone_set('Brazil/Acre');
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish");
class metodosRol
{

    public function PDFrol(

        $id_empleado ,$anio,$mes

    ) {
        

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information

// Print text using writeHTMLCell()

// set default monospaced font

// set auto page breaks

        $style = '
<style>
table {
    border-collapse: collapse;
    border-spacing: 0;
}

table tr th {
    border: solid 1px #aaa999;

}

table tr td {
    border: solid 1px #aaa999;


}

table tr td:nth-child(1) {
    text-align: left;
    vertical-align: top;
}

table tr td:nth-child(2) {
    text-align: left;
    vertical-align: top;
}





#outer {

    width: 100%;
    display: flex;
    justify-content: center;
}

.right {
    float: right;

}

table {
    width: 65%;
    height: 300px;
    text-align: left;
    vertical-align: middle;
    ;
}
  </style>
';
// set some language-dependent strings (optional)

// ---------------------------------------------------------

// set font
        $pdf->SetFont('dejavusans', '', 9);

// add a page
        $pdf->AddPage();
       
// get current auto-page-break mode
       
// disable auto-page-break
      
// set bacground image
        
// restore auto-page-break status
       
// set the starting point for the page content
   

       
        $baseDatos = new conectarse(); $baseDatos->conectar();


        //EMPELADO
        $empleado = $baseDatos->consulta("SELECT nombre_empleado,nombre_cargo ,identificacion_empleado FROM empleado ,cargo WHERE id_empleado=$id_empleado AND empleado.id_fkcargo_empleado = cargo.Id_cargo; ");
        if ($baseDatos->numeroFilas($empleado) > 0) {
            // $resp = array();
            $res = $baseDatos->llenarColeccion($empleado);
            $nombre_empleado = $res['nombre_empleado'];
            $cargo = $res['nombre_cargo'];
            $cedula_empleado = $res['identificacion_empleado'];
            
        }

        //datos del mes
        $number = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $aux_mes = $meses[$mes-1];


        //datos del rol
        $seleccionar = $baseDatos->consulta("SELECT 
        SUM(sueldo_basico) AS sueldo_basico ,
        SUM(decimo_tercero) AS decimo_tercero,
        SUM(decimo_cuarto) AS decimo_cuarto,
        SUM(fondo_reserva) AS fondo_reserva,
        SUM(horas_extras) AS horas_extras,
        SUM(comisiones) AS comisiones,
        SUM(otros_ingresos) AS otros_ingresos,
        SUM(total_ingresos) AS total_ingresos,

        SUM(aportacion_iess_rol_pagos) AS aportacion_iess_rol_pagos,
        SUM(prestamo) AS prestamo,
        SUM(impuesto_renta) AS impuesto_renta,
        SUM(quincena) AS quincena,
        SUM(anticipo) AS anticipo,
        SUM(otros_descuento) AS otros_descuento,
        SUM(total_descuentos) AS total_descuentos,
        SUM(retencion_judicial) AS retencion_judicial,
        sueldo_basico
        
        
        FROM rol_de_pagos
        WHERE id_empleado=$id_empleado AND mes=$mes AND anio=$anio");
        if ($baseDatos->numeroFilas($seleccionar) > 0) {
            // $resp = array();
            $res = $baseDatos->llenarColeccion($seleccionar);
            if($res['sueldo_basico']==NULL){
                $sueldo_mensual=0;
            }else{
                $sueldo_mensual = $res['sueldo_basico'];
            }

            if($res['decimo_tercero']==NULL){
                $decimo_tercero=0;
            }else{
                $decimo_tercero = $res['decimo_tercero'];
            }

            if($res['decimo_cuarto']==NULL){
                $decimo_cuarto=0;
            }else{
                $decimo_cuarto = $res['decimo_cuarto'];
            }


            if($res['fondo_reserva']==NULL){
                $fondo_reserva=0;
            }else{
                $fondo_reserva = $res['fondo_reserva'];
            }


            if($res['horas_extras']==NULL){
                $horas_extras=0;
            }else{
                $horas_extras = $res['horas_extras'];
            }


            if($res['comisiones']==NULL){
                $comisiones=0;
            }else{
                $comisiones = $res['comisiones'];
            }

            
            if($res['aportacion_iess_rol_pagos']==NULL){
                $aportacion_iess_rol_pagos=0;
            }else{
                $aportacion_iess_rol_pagos = $res['aportacion_iess_rol_pagos'];
            }


              
            if($res['total_ingresos']==NULL){
                $total_ingresos=0;
            }else{
                $total_ingresos = $res['total_ingresos'];
            }

         
            if($res['otros_ingresos']==NULL){
                $otros_ingresos=0;
            }else{
                $otros_ingresos = $res['otros_ingresos'];
            }


            
            if($res['prestamo']==NULL){
                $prestamo=0;
            }else{
                $prestamo = $res['prestamo'];
            }



            if($res['impuesto_renta']==NULL){
                $impuesto_renta=0;
            }else{
                $impuesto_renta = $res['impuesto_renta'];
            }


            
            if($res['quincena']==NULL){
                $quincena=0;
            }else{
                $quincena = $res['quincena'];
            }


            if($res['otros_descuento']==NULL){
                $otros_descuento=0;
            }else{
                $otros_descuento = $res['otros_descuento'];
            }

            if($res['total_descuentos']==NULL){
                $total_descuentos=0;
            }else{
                $total_descuentos = $res['total_descuentos'];
            }


            if($res['anticipo']==NULL){
                $anticipo=0;
            }else{
                $anticipo = $res['anticipo'];
            }

            if($res['retencion_judicial']==NULL){
                $retencion_judicial=0;
            }else{
                $retencion_judicial = $res['retencion_judicial'];
            }

            

      
           
           
           
            
        }
        
$total_recibir=$total_ingresos-$total_descuentos;
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>

   
   
    <div id="outer">
        <table style=" border-collapse: collapse;
        border-spacing: 0; ">
            <tr style="text-align: left;
            vertical-align: top;">
                <td style="border: solid 1px black;height: 40px; text-align: center;" colspan="4" ><b>
                <div style="font-size:12px">   MUSAKALIUM CIA.LTDA. </div>
                <div style="font-size:10px">  RUC:0515656545445 </div>
                <div style="font-size:13px">  ROL DE PAGO </div>
                
             </b> </td>


               
            </tr>

            <tr style="text-align: left;
            vertical-align: top;">
                <td bgcolor="gray" style="border: solid 1px black; text-align: center;" colspan="4">
                <b>
                DATOS DEL EMPLEADOS
             </b> </td>


               
            </tr>


            
            <tr style="text-align: left;
            vertical-align: top;">
                <td style="border: solid 1px black; text-align: left;"  colspan="2">
                
                <b>NOMBRE: </b>    <span>   '.$nombre_empleado.'</span> <br>
                <b>DIAS LABORADOS: </b>   '.$number.' DÍAS <br>
                <b>PERIODO:  </b>    1-'. $aux_mes.'-'.$anio.' AL '.$number.'-'.$aux_mes.'-'.$anio.'
               
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="2">
               
                <b>CARGO: </b>    <span>  '.$cargo.'</span> <br>
                <b>FECHA: </b>   '.  strftime("%A %d de %B del %Y").'  <br>
                <b>SUELDO MENSUAL: </b>   '.$sueldo_mensual.'
          </td>


               
            </tr>

            
            <tr style="text-align: left;
            vertical-align: top;">
                <td bgcolor="gray" style="border: solid 1px black; text-align: center;"  colspan="2">
                <b>
               INGRESOS
             </b> </td>

             <td bgcolor="gray" style="border: solid 1px black; text-align: center;" colspan="2">
                <b>
              DESCUENTOS
             </b> </td>

            



               
            </tr>

            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               SUELDO
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
           $'.$sueldo_mensual.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
           APORTE AL IESS
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.round($aportacion_iess_rol_pagos,2).'
        </td>
            </tr>




            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               FONDO DE RESERVA
           </td>

             <td style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$fondo_reserva.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
           ANTICIPOS DE SUELDO
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.$anticipo.'
         </td>

            </tr>


            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               HORAS EXTRA ORDINARIAS
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$horas_extras.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
          PRESTAMOS
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.$prestamo.'
         </td>

            </tr>



            

            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               VACACIONES
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$horas_extras.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
          RETENCION JUDICIAL
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.$retencion_judicial.'
         </td>

            </tr>


            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               COMISIONES
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$comisiones.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
          QUINCENA
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.$quincena.'
         </td>

            </tr>




            
            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               DECIMO TERCERO
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
             $'.round($decimo_tercero,2).'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
          IMPUESTO A LA RENTA
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
        $'.$impuesto_renta.'
         </td>

            </tr>



            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               DECIMO CUARTO
           </td>

             <td  style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$decimo_cuarto.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            OTROS DESCUENTOS
          
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
          $'.$otros_descuento.'
         </td>

            </tr>



            <tr style="text-align: left;
            vertical-align: top;">
                <td  style="border: solid 1px black; text-align: left;"  colspan="1">
                
               OTROS INGRESOS
           </td>

             <td style="border: solid 1px black; text-align: right;" colspan="1">
             $'.$otros_ingresos.'
                </td>

             <td  style="border: solid 1px black; text-align: left;" colspan="1">
            
          
          </td>
          <td  style="border: solid 1px black; text-align: center;" colspan="1">
          
       
         </td>

            </tr>



            

            

            <tr style="text-align: left;
            vertical-align: top;">
                <td  bgcolor="gray"  style="border: solid 1px black; text-align: left;"  colspan="1">
                
             <b> TOTAL INGRESOS </b>
           </td>

             <td style="border: solid 1px black; text-align: right;" colspan="1">
             $'.round($total_ingresos,2).'
                </td>

             <td  bgcolor="gray"  style="border: solid 1px black; text-align: left;" colspan="1">
             <b> TOTAL DESCUENTOS </b>
          
          </td>
          <td  style="border: solid 1px black; text-align: right;" colspan="1">
          
          $'.round($total_descuentos,2).'
         </td>

            </tr>



            <tr style="text-align: left;
            vertical-align: top;">
                <td   style="border: solid 1px black; text-align: left;"  colspan="4">
                
             
           </td>

            

            </tr>


            <tr style="text-align: center;
            vertical-align: top;">
                <td   bgcolor="gray" style="border: solid 1px black; text-align: center;"  colspan="3">
              <b>  LIQUIDO A RECIBIR </b>
             
           </td>
           <td   style="border: solid 1px black; text-align: right;"  colspan="1">
           $'.round($total_recibir,2).'
             
           </td>
         

          

            </tr>






            
            


            





           




        </table>




        <table style=" border-collapse: collapse;
        border-spacing: 0; " align="center" >


        <tr style="text-align: left;
        vertical-align: top;">
            <td   style="border: solid 1px black; text-align: left; height:40px;  "  align="center" >
          
        <b> ELABORADO POR </b>
       </td>

       <td   style="border: solid 1px black; text-align: left; height:40px;  "  align="center"  >
     
       <b>  AUTORIZADO POR</b>
         
       </td>

       <td  style="border: solid 1px black; text-align: left; height:40px;  "  align="center"  >
      
       <b> RECIBI CONFORME</b>
       </td>

        

        </tr>






        <tr style="text-align: left;
        vertical-align: top;">
            <td   style="border: solid 1px black; text-align: left; height:40px;  "  align="center"   >
            ing.Com.,Kimberly Torres <br>
            CI.9999999999999999 <br>
            <b> AUXILIAR CONTABLE</b>
         
       </td>

       <td   style="border: solid 1px black; text-align: left; height:40px;  "  align="center"   >
       Sr.Santiago Abadb Montenegro<br>
       CI.9999999999999999   <br>
        <b> GERENTE GENERAL</b>
       </td>

       <td   style="border: solid 1px black; text-align: left; height:40px;  "  align="center"   >
            
         '.$nombre_empleado.' <br>
         CI. <b>'.$cedula_empleado.' </b><br>
         <b>EMPLEADO(A) </b>
       </td>

        

        </tr>
         

        </table>
    </div>
    <br>
 








</body>
</html>';

// Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->writeHTML($style, true, false, true, false, '');
        $pdf->writeHTML($html, true, false, true, false, '');

      
        

        $pdf->Output('example_001.pdf', 'I');

    }
}

//============================================================+
// END OF FILE
//============================================================+
