<?php
require_once __DIR__ . '/../conexao_db/conexao.php';

function buscar_atividades(){
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id_usuario, data_hora, acao FROM log_atividades");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todas as linhas
    } catch (Exception $e) {
        return false;
    }
}