<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/html/vendor/autoload.php';
require_once __DIR__ . '/../conexao_db/conexao.php'; // Certifique-se de que está puxando a conexão corretamente!

// **Passo 1: Verificar se há um usuário logado**
if (!isset($_SESSION['id'])) {
    die("⚠️ Nenhum usuário está logado!");
}

$id_usuario_logado = $_SESSION['id']; // ID do usuário autenticado

// **Passo 2: Buscar o e-mail do usuário logado**
function buscarEmailDoUsuarioLogado($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

$email_usuario_logado = buscarEmailDoUsuarioLogado($id_usuario_logado);
if (!$email_usuario_logado) {
    die("⚠️ Erro ao buscar o e-mail do usuário logado!");
}

// **Passo 3: Criar o conteúdo do e-mail**
$mensagem = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembrete Criado - Do it!</title>

</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif;">
    
    <!-- Container principal -->
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Tabela do conteúdo -->
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #000000; padding: 30px 20px; text-align: center;">
                            <h1 style="margin: 0 0 10px 0; font-size: 28px; font-weight: 700; color: #ffffff; font-family: Arial, Helvetica, sans-serif;">
                                Seu lembrete foi criado com sucesso
                            </h1>
                            <p style="margin: 0 0 20px 0; font-size: 18px; font-weight: 400; color: #ffffff; font-family: Arial, Helvetica, sans-serif; line-height: 1.4;">
                                Seu trabalho fica <strong>melhor</strong> e mais <strong>organizado</strong> com Do it!
                            </p>
                            <!-- Botão Header -->
                            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="background-color: #085DFC; border-radius: 8px; padding: 12px 24px;">
                                        <a href="http://localhost:8080/views/notification.php" target="_blank"; style="text-decoration: none; color: #ffffff; font-weight: 600; font-family: Arial, Helvetica, sans-serif; font-size: 16px; display: block;">
                                            Ver lembrete
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 20px; text-align: center;">
                            <!-- Placeholder para imagem do polvo -->
                            <div style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 50%; margin: 0 auto 20px auto; border: 2px solid rgb(0, 0, 0); display: inline-block; vertical-align: middle; text-align: center; line-height: 76px; font-size: 24px; color: #085DFC;">
                                😊
                            </div>
                            
                            <p style="margin: 0 0 25px 0; font-size: 18px; font-weight: 400; color: #333333; font-family: Arial, Helvetica, sans-serif;">
                                Clique aqui para voltar para o Do it
                            </p>
                            
                            <!-- Botão Main -->
                            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="background-color: #000000; border-radius: 8px; padding: 12px 24px; border: 2px solid #000000;">
                                        <a href="http://localhost:8080/views/main.php" style="text-decoration: none; target="_blank"; color:rgb(255, 255, 255); font-weight: 600; font-family: Arial, Helvetica, sans-serif; font-size: 16px; display: block;">
                                            Do it!
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #000000; padding: 30px 20px; text-align: center;">
                            <!-- Navigation Links -->
                            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto 20px auto;">
                                <tr>
                                    <td style="padding: 0 15px;">
                                        <a href="../public/index.php" style="text-decoration: none; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 14px; border-bottom: 2px solid transparent;">
                                            Home
                                        </a>
                                    </td>
                                    <td style="padding: 0 15px;">
                                        <a href="#" style="color: #ffffff; text-decoration: none; font-size: 20px;">
                                            <span style="font-family: monospace;">⚡</span>
                                        </a>
                                    </td>
                                    <td style="padding: 0 15px;">
                                        <a href="#" style="text-decoration: none; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 14px; border-bottom: 2px solid transparent;">
                                            Sobre
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Copyright -->
                            <p style="margin: 0; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                &copy; 2025 Do it. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
    
</body>
</html>
HTML;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $mail->addAddress($email_usuario_logado['email']);
        echo "📩 Enviando para: " . $email_usuario_logado['email'] . "<br>"; // Depuração

        $mail->send();
        echo "✅ E-mail enviado para o usuário logado!";
    } catch (Exception $e) {
        echo "❌ Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
} else {
    echo "⚠️ Requisição inválida.";
}
