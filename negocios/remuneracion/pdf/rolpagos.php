<?php  


include_once '../../../clases/rolpagos.php';


$id_empleado=57;
$anio=2023;
$mes=4;

/*
$id_empleado=$_GET['id_empleado'];
$anio=$_GET['anio'];
$mes=$_GET['mes'];
*/
$reporte= new metodosRol();
$reporte->PDFrol(
    $id_empleado ,$anio,$mes
);

?>