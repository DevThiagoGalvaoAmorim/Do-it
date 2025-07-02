<?php
<?php

// Inicie a sessão se necessário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Defina as rotas e os arquivos correspondentes
$routes = [
    '/' => 'views/auth/login.php',
    '/login' => 'views/auth/login.php',
    '/logout' => 'controllers/logout.php',
    '/register' => 'views/auth/cadastro.php',
    '/auth' => 'controllers/auth_controller.php',
    '/perfil' => 'views/perfil.php',
    '/perfil/processar' => 'controllers/processar_perfil.php',
    '/admin' => 'views/admin/admin_view.php',
    '/admin/usuarios' => 'controllers/usuarios_controller.php',
    '/senha/atualizar' => 'controllers/atualizar_senha.php',
    '/senha/recuperar' => 'views/auth/recuperar_senha.php',
    '/recuperar' => 'public/accountRecovery.php',
    // Adicione outras rotas conforme necessário
];

// Obtenha o caminho da URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o prefixo do diretório se necessário (exemplo: /Do-it/www)
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}
if ($uri === '' || $uri === false) {
    $uri = '/';
}

// Protege rotas que exigem autenticação
$protectedRoutes = [
    '/perfil',
    '/perfil/processar',
    '/admin',
    '/admin/usuarios',
];

if (in_array($uri, $protectedRoutes)) {
    if (!isset($_SESSION['id'])) {
        header('Location: /login');
        exit;
    }
}

// Verifica se a rota existe
if (array_key_exists($uri, $routes)) {
    require __DIR__ . '/' . $routes[$uri];
    exit;
} else {
    // 404 simples
    http_response_code(404);
    echo "Página não encontrada: $uri";
}
