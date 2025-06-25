<?php
require_once  '../models/account_config.php';
require_once  '../views/auth/warning.php';
require '/var/www/html/vendor/autoload.php';
require_once __DIR__ . '/../conexao_db/conexao.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$email = $_POST['email'];
$token = bin2hex(random_bytes(32)); // token seguro

salvarToken($email, $token);

// Enviar e-mail
$link = "http://localhost:8080/views/auth/reset.php?token=$token";



$mensagem = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #333;
      background-color: #f8f9fa;
      padding: 20px;
    }
    .container {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 30px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      background-color:rgb(221, 236, 253);
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Olá,</h2>
    <p>Recebemos uma solicitação para redefinir sua senha no <strong>Do It</strong>.</p>
    <p>Se você foi o autor desta solicitação, clique no botão abaixo:</p>
    <p>
      <a href="'.$link.'" class="btn">Redefinir Senha</a>
    </p>
    <p>Se você não solicitou isso, ignore este e-mail. Sua conta permanecerá segura.</p>
    <div class="footer">
      &copy; 2025 Do It - Todos os direitos reservados.
    </div>
  </div>
</body>
</html>
';






$mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'projeto.do.it2025@gmail.com';
        $mail->Password = 'xsfylxpzstnubwoc'; // Use senha de aplicativo!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('projeto.do.it2025@gmail.com', 'Do it');
        $mail->isHTML(true);
        $mail->Subject = 'Lembrete criado com sucesso';
        $mail->Body = $mensagem;

        // **Enviar e-mail APENAS para o usuário logado**
        $mail->addAddress($email);// AQUI FICA O EMAIL DO DESTINATARIO

        $mail->send();
        
        
        tela_de_mensagem("Um link de recuperação enviado ao seu E-mail!");
    } catch (Exception $e) {
        tela_de_mesagem("Erro ao enviar e-mail: {$mail->ErrorInfo}");
    }



