<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->proyectoid)){
    $proyectoid = $data->proyectoid;
}else if(empty($data->proyectoid)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El id del proyecto esta vacio';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT *   
                FROM deproyecto 
                WHERE proyectoid = $proyectoid ";
$resultado = mysqli_query($conexion,$consulta);

if(mysqli_num_rows($resultado) > 0){
    $i = 0;
    while($row = mysqli_fetch_assoc($resultado)){
        $projects[$i]['deproyectoid'] = $row['deproyectoid'];
        $projects[$i]['titulo'] = $row['titulo'];
        $projects[$i]['fecha'] = date("d/m/y", strtotime($row['fecha']));
        $i++;
    }
    echo json_encode($projects);
}else{
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se encontraron tareas relacionadas';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>