<?php

include ".env";

/* FORM DATA ./index.html */ 
$nome = $_POST['nome'];
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

/* CONNECION DATA */
$servername = "localhost";
$database = "formulario";
$username = "root";
$password = "";

/* Create connection */
$connection = mysqli_connect($servername, $username, $password, $database);

/* Check connection */
if (!$connection) {
      die("A conexão falhou! " . mysqli_connect_error());
}
 
/* Database Recording */
$record = "INSERT INTO envios(nome, email, assunto, mensagem) VALUES ('{$nome}', '{$email}', '{$assunto}', '{$mensagem}')";
if (mysqli_query($connection, $record)) {
      echo "Gravado com Sucesso no Banco de Dados!";
} else {
      echo "Error: " . $record . "<br>" . mysqli_error($connection);
      exit;
}

// $lastId = mysqli_insert_id($connection);

// $query = "SELECT * FROM envios WHERE id = {$lastId}";
// $data = mysqli_query($connection, $query);

// echo "<br><br>";

// while ($info = mysqli_fetch_array($data)) {
//     $nomeUsuario = $info['nome'];
// }
// echo $nomeUsuario;
// exit;

mysqli_close($connection);

/* SENDING THE EMAIL */
include 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Instancia a classe e cria um objeto

try {
    //Server settings
    $mail->isSMTP();
    $mail->SMTPDebug  = SMTP::DEBUG_LOWLEVEL;
    $mail->Host       = "smtp.gmail.com";
    $mail->Port       = 465; // Encriptacao
    $mail->SMTPAuth   = true;
    $mail->Username   = 'davipenso@gmail.com';
    $mail->Password   = '[sua_senha_do_email]';
    
    //Recipients
    $mail->setFrom("davipenso@gmail.com");
    $mail->addAddress("davipenso@gmail.com");
    $mail->addCC('davipenso@gmail.com');

    //Content
    $dataHoje = date("d/m/Y") . " às " . date("H:i:s");

    $mail->isHTML(true);
    $mail->Subject = "Mensagem do site";
    $mail->CharSet = 'UTF-8';
    $mail->Body    = "
        <h2>Mensagem enviada do site</h2>
        <p>Nome: {$nome}</p>
        <p>Email: {$email}</p>
        <p>Assunto: {$assunto}</p>
        <p>Mensagem: {$mensagem}</p>

        <br><br>

        Email enviado em {$dataHoje}
    ";

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "A mensagem não pôde ser enviada. Erro: {$mail->ErrorInfo}";
}
