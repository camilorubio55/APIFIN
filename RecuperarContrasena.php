<?php

require("BD.php");

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

$consulta = "SELECT COUNT(usuid)
                FROM usuario 
                WHERE codigo = '$codigo' ";

$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);

if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El no existe usuario con el correo electronico relacionado';
    echo json_encode($projects);
    return false;
}

require("class.phpmailer.php");
$mail = new PHPMailer;
$mail->CharSet = 'UTF-8';

echo 'deded';

$mail->From = "info@tudominio.com";
$mail->FromName = "Nombre de dominio";

$mail->addAddress($codigo, "Nombre Admin");
$mail->addReplyTo("info@tudominio.com","Tunombre");

$mail->isHTML(true);

$mail->Subject = "Nuestro titulo";
$mail->Body = "Tu contraseña actualizada es:" . $row['tu_contrasena'];

if(!$mail->send()) {                    
    echo $error = "Ocurrió un error inesperado con él envió del correo electrónico, inténtelo de nuevo más tarde, disculpa las molestias.";
    return false;
} else {
    echo$error = "Se envio correctamente el correo electrónico.";
}  

$projects[0]['success'] = 1;
$projects[0]['mensaje'] = 'Se ha enviado correo electronico';
echo json_encode($projects);

mysqli_close($conexion);

?>