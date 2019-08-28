<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->proyectoid) && !empty($data->titulo) && !empty($data->fecestimada) && !empty($data->fecentrega) && ($data->horas)){
    $proyectoid = mysqli_real_escape_string($conexion, trim($data->proyectoid));;
    if(strlen(trim($data->titulo)) <= 50){
        $titulo = mysqli_real_escape_string($conexion, trim($data->titulo));;
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'El titulo tiene mas de 50 caracteres, no se puede actualizar.';
        echo json_encode($projects);
        return false;
    } 
    $descripcion = mysqli_real_escape_string($conexion, trim($data->descripcion));;
    $fecestimada = mysqli_real_escape_string($conexion, trim($data->fecestimada));;
    $fecentrega = mysqli_real_escape_string($conexion, trim($data->fecentrega));;
    $horas = mysqli_real_escape_string($conexion, trim($data->horas));;
    $fecestimada = date('Y-m-d', strtotime(strtr($fecestimada, '/', '-')));
    $fecentrega = date('Y-m-d', strtotime(strtr($fecentrega, '/', '-')));
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
else if(empty($data->fecestimada)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo fecha estimada esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->fecentrega)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo fecha de entrega esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->horas)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El campo horas esta vacio';
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

$consulta = "UPDATE proyecto 
                         SET titulo = '{$titulo}', 
                             descripcion = '{$descripcion}', 
                             fecestimada = '{$fecestimada}', 
                             fecentrega = '{$fecentrega}', 
                             horas = '{$horas}' 
             WHERE proyectoid = $proyectoid ";

if($resultado = mysqli_query($conexion,$consulta)){
    $projects[0]['success'] = 1;
    $projects[0]['mensaje'] = 'Se actualizo proyecto correctamente';
    echo json_encode($projects);
}
else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se actualizo proyecto';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>