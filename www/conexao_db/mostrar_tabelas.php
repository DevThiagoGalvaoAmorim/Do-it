<?php
$host = 'mysql';
$port = 3306;
$db   = 'doitdb';
$user = 'user';
$pass = 'password';

try {
    echo "<p>Conectando ao banco <strong>$db</strong> em <strong>$host:$port</strong> como <strong>$user</strong>...</p>";
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Conexão estabelecida com sucesso!</p>";

    // Obter as tabelas do banco de dados
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "<p>⚠️ Nenhuma tabela encontrada no banco <strong>$db</strong>.</p>";
    } else {
        echo "<h3>Tabelas no banco '$db':</h3><ul>";
        foreach ($tables as $table) {
            echo "<li><strong>$table</strong></li>";

            // Exibir colunas da tabela
            $columnsStmt = $pdo->query("DESCRIBE $table");
            $columns = $columnsStmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<ul>";
            foreach ($columns as $column) {
                echo "<li>{$column['Field']} - {$column['Type']}</li>";
            }
            echo "</ul>";
        }
        echo "</ul>";
    }
} catch (PDOException $e) {
    die("<p>❌ Erro na conexão com o banco de dados: " . $e->getMessage() . "</p>");
}
?>