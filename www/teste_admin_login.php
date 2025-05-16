<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

// Criar um administrador de teste (se não existir)
$email_admin = "admin@teste.com";
$senha_admin = "senha123";
$nome_admin = "Admin Teste";

// Verificar se o admin já existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND tipo = 'admin'");
$stmt->execute([':email' => $email_admin]);
if ($stmt->rowCount() == 0) {
    // Criar admin
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, 'admin')");
    $stmt->execute([
        ':nome' => $nome_admin,
        ':email' => $email_admin,
        ':senha' => $senha_admin
    ]);
    echo "Administrador de teste criado com sucesso!<br>";
}

// Testar login
$usuario = buscarUsuario($email_admin, $senha_admin);
if ($usuario) {
    echo "Login do administrador funcionando corretamente!<br>";
    echo "ID: " . $usuario['id'] . "<br>";
    echo "Nome: " . $usuario['nome'] . "<br>";
    echo "Email: " . $usuario['email'] . "<br>";
} else {
    echo "Falha no login do administrador!<br>";
}
?>


### 2. Execute o arquivo no navegador

Acesse `http://localhost:8080/adicionar_coluna_tipo.php` no seu navegador para adicionar a coluna `tipo` à tabela `usuarios`.

### 3. Tente novamente o teste_admin_login.php

Depois de adicionar a coluna, acesse novamente `http://localhost:8080/teste_admin_login.php` para testar o login do administrador.

## Alternativa: Modificar o código existente

Se você preferir não modificar a estrutura do banco de dados, pode alterar o código do `teste_admin_login.php` para não usar a coluna `tipo`:
```php
<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

// Criar um administrador de teste (se não existir)
$email_admin = "admin@teste.com";
$senha_admin = "senha123";
$nome_admin = "Admin Teste";

// Verificar se o admin já existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
$stmt->execute([':email' => $email_admin]);
if ($stmt->rowCount() == 0) {
    // Criar admin
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    $stmt->execute([
        ':nome' => $nome_admin,
        ':email' => $email_admin,
        ':senha' => $senha_admin
    ]);
    echo "Administrador de teste criado com sucesso!<br>";
}

// Testar login
$usuario = buscarUsuario($email_admin, $senha_admin);
if ($usuario) {
    echo "Login do administrador funcionando corretamente!<br>";
    echo "ID: " . $usuario['id'] . "<br>";
    echo "Nome: " . $usuario['nome'] . "<br>";
    echo "Email: " . $usuario['email'] . "<br>";
} else {
    echo "Falha no login do administrador!<br>";
}
?>
```