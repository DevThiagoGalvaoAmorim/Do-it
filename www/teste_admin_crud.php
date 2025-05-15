<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

echo "<h2>Teste de CRUD de Usuários</h2>";

// 1. Criar um usuário de teste
$nome_teste = "Usuário Teste " . rand(1000, 9999);
$email_teste = "teste" . rand(1000, 9999) . "@exemplo.com";
$senha_teste = "senha" . rand(1000, 9999);

echo "<h3>1. Criando usuário</h3>";
$id_usuario = criarUsuario($nome_teste, $email_teste, $senha_teste);
if ($id_usuario) {
    echo "Usuário criado com sucesso! ID: $id_usuario<br>";
} else {
    echo "Falha ao criar usuário!<br>";
    exit;
}

// 2. Listar usuários
echo "<h3>2. Listando usuários</h3>";
$usuarios = listarUsuarios();
if ($usuarios) {
    echo "Total de usuários: " . count($usuarios) . "<br>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th></tr>";
    foreach ($usuarios as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['nome'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Falha ao listar usuários!<br>";
}

// 3. Atualizar usuário
echo "<h3>3. Atualizando usuário</h3>";
$novo_nome = "Nome Atualizado " . rand(1000, 9999);
$resultado = atualizarUsuario($id_usuario, $novo_nome, $email_teste, $senha_teste);
if ($resultado) {
    echo "Usuário atualizado com sucesso!<br>";
    
    // Verificar se a atualização funcionou
    $usuarios = listarUsuarios();
    foreach ($usuarios as $user) {
        if ($user['id'] == $id_usuario) {
            echo "Nome atualizado: " . $user['nome'] . "<br>";
            break;
        }
    }
} else {
    echo "Falha ao atualizar usuário!<br>";
}

// 4. Deletar usuário
echo "<h3>4. Deletando usuário</h3>";
$resultado = deletarUsuario($id_usuario);
if ($resultado) {
    echo "Usuário deletado com sucesso!<br>";
    
    // Verificar se a exclusão funcionou
    $usuarios = listarUsuarios();
    $encontrado = false;
    foreach ($usuarios as $user) {
        if ($user['id'] == $id_usuario) {
            $encontrado = true;
            break;
        }
    }
    
    if (!$encontrado) {
        echo "Confirmado: usuário não está mais na lista!<br>";
    } else {
        echo "Erro: usuário ainda está na lista!<br>";
    }
} else {
    echo "Falha ao deletar usuário!<br>";
}
?>