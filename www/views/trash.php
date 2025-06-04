<?php
require_once __DIR__ . '/../controllers/safe_page.php';
require_once __DIR__ . '/../conexao_db/conexao.php';


$id_usuario = $_SESSION['id'] ?? null;
$notas_lixeira = [];

if ($id_usuario) {
    $stmt = $pdo->prepare("SELECT * FROM lixeira WHERE id_usuario = :id_usuario ORDER BY data_hora DESC");
    $stmt->execute([':id_usuario' => $id_usuario]);
    $notas_lixeira = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lixeira</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">

</head>

<body id="body">

    <?php include 'partials/header.php'; ?>

    <main>

        <?php include 'partials/sidebar.php'; ?>

        <div class="container-notas">
            <div class="cabecalho-notas">
                <h2>Lixeira</h2>
                <hr>
            </div>

            <div class="listagem-de-notas">
                <?php if (empty($notas_lixeira)): ?>
                    <p>Nenhuma nota na lixeira.</p>
                <?php else:
                ?>
                    <?php foreach ($notas_lixeira as $nota):
                    ?>
                        <div class="nota-lixeira">
                            <h3 class="nota-titulo"><?= htmlspecialchars($nota['titulo']) ?></h3>
                            <p class="nota-texto"><?= nl2br(htmlspecialchars($nota['descricao'])) ?></p>
                            <div class="nota-botoes">             
                                <button class="btn-restaurar" data-id="<?= $nota['id'] ?>">Restaurar</button>
                                <button class="btn-excluir" data-id="<?= $nota['id'] ?>">Excluir</button>
                            </div>
                        </div>
                    <?php endforeach;
                    ?>
                <?php endif;
                ?>
            </div>
        </div>
    </main>

    <footer>
        <?php include './partials/footer.php'; ?>
    </footer>
    <script src="./public/Javascript/search.js">
    </script>
    <script>
    document.querySelectorAll('.btn-restaurar').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Deseja restaurar esta nota?')) {
                fetch('../models/lixeira_crud.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=restaurar&id=' + this.dataset.id
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) location.reload();
                });
            }
        });
    });

    document.querySelectorAll('.btn-excluir').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Excluir esta nota da lixeira? Esta ação não pode ser desfeita.')) {
                fetch('../models/lixeira_crud.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=excluir&id=' + this.dataset.id
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) location.reload();
                });
            }
        });
    });
    </script>
</body>

</html>