<?php
session_start();
require_once __DIR__ . '/conexao_db/conexao.php';

try {
    // Fetch only email and tipo from database
    $stmt = $pdo->query("SELECT email, tipo FROM usuarios ORDER BY tipo, email");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .admin {
            color: #d32f2f;
            font-weight: bold;
        }
        .user {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <h1>User List</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td class="<?= $user['tipo'] === 'admin' ? 'admin' : 'user' ?>">
                    <?= htmlspecialchars($user['tipo']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
