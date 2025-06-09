<?php
$email = 'destinatario@email.com'; // Email do usuário
$token = bin2hex(random_bytes(16)); // Gera token seguro
$link = "http://localhost:8080/public/reset.php?token=$token"; // Link de redefinição

$apiKey = 'SUA_CHAVE_API_DO_BREVO';

$data = [
    'sender' => ['name' => 'Do It App', 'email' => 'no-reply@doit.com'],
    'to' => [['email' => $email]],
    'subject' => 'Redefinição de senha',
    'htmlContent' => "
        <html>
            <body>
                <p>Olá,</p>
                <p>Clique no link abaixo para redefinir sua senha:</p>
                <p><a href='$link'>Redefinir senha</a></p>
                <p>Se você não solicitou isso, ignore este e-mail.</p>
            </body>
        </html>"
];

$options = [
    'http' => [
        'header'  => [
            "Content-type: application/json",
            "api-key: $apiKey"
        ],
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents('https://api.brevo.com/v3/smtp/email', false, $context);

if ($response === FALSE) {
    echo "Erro ao enviar e-mail.";
} else {
    echo "E-mail enviado com sucesso!";
}
