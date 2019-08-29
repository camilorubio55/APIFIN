<?php

function validafecha($conexion, $fechaavalidar){
    $validafecha = explode("/",trim($fechaavalidar));
    if(is_numeric($validafecha[0]) && is_numeric($validafecha[1]) && is_numeric($validafecha[2])){
        if(checkdate($validafecha[1],$validafecha[0],$validafecha[2])){
            $fecha = mysqli_real_escape_string($conexion, trim($fechaavalidar));;
            return $fecha = date('Y-m-d', strtotime(strtr($fecha, '/', '-')));
        }
        else{
            $projects[0]['success'] = 0;
            $projects[0]['mensaje'] = 'La fecha no es valida.';
            echo json_encode($projects);
            return false;
        }
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'No es un formato de fecha correcto.';
        echo json_encode($projects);
        return false;
    }
}

?>