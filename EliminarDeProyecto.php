<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->deproyectoid)){
    $deproyectoid = mysqli_real_escape_string($conexion, trim($data->deproyectoid));;
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El id del detalle del proyecto esta vacio';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT COUNT(deproyectoid) 
                FROM deproyecto 
                WHERE deproyectoid = $deproyectoid ";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);
if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El detalle del proyecto no existe';
    echo json_encode($projects);
    return false;
}

$consulta = "DELETE FROM deproyecto WHERE deproyectoid = $deproyectoid";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'El detalle del proyecto se elimino correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se elimino detalle del proyecto';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>