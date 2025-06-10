<?php
require_once  '../models/account_config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/html/vendor/autoload.php';
require_once __DIR__ . '/../conexao_db/conexao.php'; // Certifique-se de que está puxando a conexão corretamente!


$email = $_POST['email'];
$token = bin2hex(random_bytes(32)); // token seguro

salvarToken($email, $token);

// Enviar e-mail
$apiKey = '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';
$link = "http://localhost:8080/views/auth/reset.php?token=$token";

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
        $mail->Body = $link;

        // **Enviar e-mail APENAS para o usuário logado**
        $mail->addAddress($email);// AQUI FICA O EMAIL DO DESTINATARIO

        $mail->send();
        
        
        echo "✅ E-mail enviado para o usuário logado!";
    } catch (Exception $e) {
        echo "❌ Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
