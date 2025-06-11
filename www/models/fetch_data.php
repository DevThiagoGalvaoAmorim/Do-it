<?php 
session_start();
require_once '../conexao_db/conexao.php';


//tabela usuarios
function getCountUsuarios(){
    try{
        global $pdo;
        $count_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios");
        return $count_usuarios->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}

function getUsuarios(){
    global $pdo;
    $usuarios = $pdo->query("SELECT * FROM usuarios");
    return $usuarios->fetchAll(PDO::FETCH_ASSOC);

}

function getUsuariosPorMes(){
    try{
        global $pdo;
        $query = "SELECT 
            MONTH(criado_em) AS mes, 
            YEAR(criado_em) AS ano, 
            COUNT(*) AS total 
        FROM usuarios 
        GROUP BY ano, mes 
        ORDER BY ano, mes";
        
        $result = $pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}



//tabela notas
function getCountNotas(){
    try{
        global $pdo;
        $count_notas = $pdo->query("SELECT COUNT(*) FROM notas");
        return $count_notas->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}

function getNotas(){
    try{
        global $pdo;
        $notas = $pdo->query("SELECT * FROM notas");
        return $notas->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}


//tabela lembrete
function getCountLembrete(){
    try{
        global $pdo;
        $count_lembrete = $pdo->query("SELECT COUNT(*) FROM lembrete");
        return $count_lembrete->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}

function getLembrete(){
    try{
        global $pdo;
        $lembrete = $pdo->query("SELECT * FROM lembrete");
        return $lembrete->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}
?>