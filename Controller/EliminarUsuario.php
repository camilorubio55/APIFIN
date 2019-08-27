<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->usuidsesion) && !empty($data->usuid)){
    $usuidsesion = mysqli_real_escape_string($conexion, trim($data->usuidsesion));;
    $usuid = mysqli_real_escape_string($conexion, trim($data->usuid));;
}
else if(empty($data->usuidsesion)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El id del usuario de la sesion esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->usuid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El id del usuario a eliminar esta vacio';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT rol 
                FROM usuario 
                WHERE usuid = '$usuidsesion' ";
$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);
if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario no tiene rol de administrador, no puede eliminar.';
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

$consulta = "DELETE FROM usuario WHERE usuid = $usuid";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se elimino correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se elimino usuario';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>