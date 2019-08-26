<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->deproyectoid) && !empty($data->titulo) && ($data->fecha)){
    $deproyectoid = mysqli_real_escape_string($conexion, trim($data->deproyectoid));;
    $titulo = mysqli_real_escape_string($conexion, trim($data->titulo));;
    $descripcion = mysqli_real_escape_string($conexion, trim($data->descripcion));;
    $fecha = mysqli_real_escape_string($conexion, trim($data->fecha));;

    $fecha = date('Y-m-d', strtotime(strtr($fecha, '/', '-')));
}
else if(empty($data->deproyectoid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se encontro id del detalle del proyecto';
    echo json_encode($projects);
    return false;
}
else if(empty($data->titulo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo titulo esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->fecha)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo fecha esta vacio';
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

$consulta = "UPDATE deproyecto 
                         SET titulo = '{$titulo}', 
                             descripcion = '{$descripcion}', 
                             fecha = '{$fecha}'
             WHERE deproyectoid = $deproyectoid ";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se actualizo detalle del proyecto correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se actualizo detalle del proyecto';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>