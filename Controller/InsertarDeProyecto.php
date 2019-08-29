<?php

require("BD.php");
require("Helpers.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->proyectoid) && !empty($data->titulo) && !empty($data->fecha) && !empty($data->usuid)){
    $proyectoid = mysqli_real_escape_string($conexion, trim($data->proyectoid));;
    if(strlen(trim($data->titulo)) <= 50){
        $titulo = mysqli_real_escape_string($conexion, trim($data->titulo));;
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'El titulo no debe tener mas de 50 caracteres, no se puede insertar.';
        echo json_encode($projects);
        return false;
    }
    $descripcion = mysqli_real_escape_string($conexion, trim($data->descripcion));;
    if(!$fecha = validafecha($conexion,trim($data->fecha))){
        return false;
    }; 
    $usuid = mysqli_real_escape_string($conexion, trim($data->usuid));;
}
else if(!empty($data->titulo) && !empty($data->fecha) && !empty($data->usuid)){
    if(strlen(trim($data->titulo)) <= 50){
        $titulo = mysqli_real_escape_string($conexion, trim($data->titulo));;
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'El titulo tiene mas de 50 caracteres, no se puede insertar.';
        echo json_encode($projects);
        return false;
    }
    $descripcion = mysqli_real_escape_string($conexion, trim($data->descripcion));;
    if(!$fecha = validafecha($conexion,trim($data->fecha))){
        return false;
    }; 
    $usuid = mysqli_real_escape_string($conexion, trim($data->usuid));;
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
else if(empty($data->usuid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El id del usuario esta vacio';
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

if(!empty($data->proyectoid) && !empty($data->usuid)){
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

    $consulta = "INSERT INTO deproyecto (proyectoid, titulo, descripcion, fecha, usuid) 
                             VALUES ($proyectoid, '{$titulo}', '{$descripcion}', '{$fecha}', $usuid)";
}
else{
    $consulta = "INSERT INTO deproyecto (titulo, descripcion, fecha, usuid) 
                             VALUES ('{$titulo}', '{$descripcion}', '{$fecha}', $usuid)";
}

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se inserto tarea correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se inserto tarea';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>