<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->codigo) && !empty($data->nombre) && ($data->rol)){
    $codigo = mysqli_real_escape_string($conexion, trim($data->codigo));;
    $nombre = mysqli_real_escape_string($conexion, trim($data->nombre));;
    $rol = mysqli_real_escape_string($conexion, trim($data->rol));;
}
else if(empty($data->codigo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->nombre)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El nombre esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->rol)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'La contrasena esta vacia';
    echo json_encode($projects);
    return false;
}

if(!checkdnsrr(array_pop(explode("@",$codigo)),"MX")){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No es un dominio correcto de correo electronico';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT COUNT(usuid) 
                FROM usuario 
                WHERE codigo = '$codigo' ";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);
if($row[0] == 1){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario ya existe';
    echo json_encode($projects);
    return false;
}

$consulta = "INSERT INTO usuario (codigo, nombre, rol) 
                          VALUES ('{$codigo}', '{$nombre}', '{$rol}')";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se inserto usuario correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se inserto usuario';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>