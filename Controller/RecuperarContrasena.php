<?php

require("BD.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../Email/vendor/autoload.php';

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->codigo)){
    $codigo = mysqli_real_escape_string($conexion, trim($data->codigo));;
}
else if(empty($data->codigo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El correo esta vacio';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT COUNT(usuid) conteo, usuid
                FROM usuario 
                WHERE codigo = '$codigo' 
                GROUP BY usuid";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);

if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El no existe usuario con el correo electronico relacionado';
    echo json_encode($projects);
    return false;
}

$usuid = $row[1];

$mail = new PHPMailer(true);
try{
    $nuevacontra = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    #$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'camilorubiocontreras@gmail.com';
    $mail->Password = 'fhdwcxfppxveavfh';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('camilorubiocontreras@gmail.com');
    $mail->addAddress($codigo);
    $mail->Subject='Recuperacion de contrasena';
    $mail->Body="La nueva contrasena es {$nuevacontra}";

    $consulta = "UPDATE usuario 
                         SET contrasena = '$nuevacontra'
                 WHERE usuid = $usuid ";

    if($resultado = mysqli_query($conexion,$consulta) && $mail->send()){
        $projects[0]['success'] = 1;
        $projects[0]['mensaje'] = 'Se recupero contrasena satisfactoriamente';
        echo json_encode($projects);
    }
}
catch(Exception $excepcion){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'No se recupero contrasena';
    echo json_encode($projects);
}

mysqli_close($conexion);

?>