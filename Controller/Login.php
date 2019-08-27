<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->codigo) && !empty($data->contrasena)){
    $codigo = mysqli_real_escape_string($conexion, trim($data->codigo));;
    $contrasena = mysqli_real_escape_string($conexion, trim($data->contrasena));;
}
else if(empty($data->codigo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->contrasena)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'La contrasena esta vacia';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT COUNT(usuid) conteo, usuid, contrasena, nombre
                FROM usuario 
                WHERE codigo = '$codigo' 
                GROUP BY usuid, contrasena, nombre";
$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);

if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario no existe';
    echo json_encode($projects);
    return false;
}

if($row[2] != $contrasena){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'La contrasena no coincide';
    echo json_encode($projects);
    return false;
}

$projects[0]['success'] = 1;
$projects[0]['mensaje'] = 'Se encontro la informacion';
echo json_encode($projects);

mysqli_close($conexion);

?>