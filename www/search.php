<?php
require_once 'safe_page.php';
require_once 'conexao_db/conexao.php';

if (isset($_GET['query'])) {
    $search = $_GET['query'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM notas WHERE titulo LIKE :search OR descricao LIKE :search");
        $stmt->execute(['search' => '%' . $search . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($results);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Se não houver consulta, retorne todas as notas
    try {
        $stmt = $pdo->query("SELECT * FROM notas");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>