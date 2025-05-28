<?php
require_once 'conexao_db/usuarios_crud.php';

function deleteUser ($id) {
    return deletarUsuario($id);
}


function updateUser ($id, $nome, $email, $senha) {
    return atualizarUsuario($id, $nome, $email, $senha);
}

function listUsers() {
    return listarUsuarios();
}
?>