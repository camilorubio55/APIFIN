<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->proyectoid) && !empty($data->titulo) && ($data->fecha)){
    $proyectoid = mysqli_real_escape_string($conexion, trim($data->proyectoid));;
    $titulo = mysqli_real_escape_string($conexion, trim($data->titulo));;
    $descripcion = mysqli_real_escape_string($conexion, trim($data->descripcion));;
    $fecha = mysqli_real_escape_string($conexion, trim($data->fecha));;

    $fecha = date('Y-m-d', strtotime(strtr($fecha, '/', '-')));
}
else if(empty($data->proyectoid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se encontro id del proyecto';
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

$consulta = "SELECT COUNT(proyectoid) 
                FROM proyecto 
                WHERE proyectoid = $proyectoid ";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);
if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El proyecto no existe';
    echo json_encode($projects);
    return false;
}

$consulta = "INSERT INTO deproyecto (proyectoid, titulo, descripcion, fecha) 
                             VALUES ($proyectoid, '{$titulo}', '{$descripcion}', '{$fecha}')";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se inserto detalle del proyecto correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se inserto detalle del proyecto';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>