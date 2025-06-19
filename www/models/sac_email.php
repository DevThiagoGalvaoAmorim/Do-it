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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensagem = trim($_POST['mensagem']);
    $mensagem = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');

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
        $mail->Subject = 'Seu problema foi recebido';
        $mail->Body = "<html><body><h2>Sua reclamação foi enviada!</h2><h3>Veja sua mensagem:</h3><p>$mensagem</p></body></html>";

        // **Enviar e-mail APENAS para o usuário logado**
        $mail->addAddress($email_usuario_logado['email']);
        echo "📩 Enviando para: " . $email_usuario_logado['email'] . "<br>"; // Depuração

        $mail->send();
        echo "✅ Reclamação criada";
    } catch (Exception $e) {
        echo "❌ Erro ao enviar reclamação: {$mail->ErrorInfo}";
    }
} else {
    echo "⚠️ Requisição inválida.";
}
