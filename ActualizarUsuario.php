<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->usuid) && !empty($data->codigo) && ($data->nombre) && ($data->rol)){
    $usuid = mysqli_real_escape_string($conexion, trim($data->usuid));;
    $codigo = mysqli_real_escape_string($conexion, trim($data->codigo));;
    $nombre = mysqli_real_escape_string($conexion, trim($data->nombre));;
    $rol = mysqli_real_escape_string($conexion, trim($data->rol));;
}
else if(empty($data->usuid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se encontro id del usuario';
    echo json_encode($projects);
    return false;
}
else if(empty($data->codigo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo codigo esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->nombre)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo nombre esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->rol)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo rol esta vacio';
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
                WHERE usuid = $usuid ";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);
if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario no existe';
    echo json_encode($projects);
    return false;
}

$consulta = "UPDATE usuario 
                         SET codigo = '{$codigo}', 
                             nombre = '{$nombre}', 
                             rol = '{$rol}' 
             WHERE usuid = $usuid ";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se actualizo usuario correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se actualizo usuario';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>