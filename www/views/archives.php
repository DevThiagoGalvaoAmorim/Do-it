<?php
require_once __DIR__ . '/../controllers/safe_page.php';
require_once __DIR__ . '/../conexao_db/conexao.php';


$id_usuario = $_SESSION['id'] ?? null;
$notas_arquivadas = [];

if ($id_usuario) {
    $stmt = $pdo->prepare("SELECT * FROM arquivadas WHERE id_usuario = :id_usuario ORDER BY data_hora DESC");
    $stmt->execute([':id_usuario' => $id_usuario]);
    $notas_arquivadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arquivos</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">

</head>

<body id="body">

    <?php include 'partials/header.php'; ?>

    <main>

        <?php
        session_start();
        //corrigir verificação posteriormente
        if ($_SESSION['email'] == 'admin@mail') {
            include './partials/sidebarAdmin.php';
        } else {
            include './partials/sidebar.php';
        }
        ?>

        <div class="container-notas">
            <div class="cabecalho-notas">
                <h2>Arquivos</h2>
                <hr>
            </div>

            <div class="listagem-de-notas">
                <?php if (empty($notas_arquivadas)): ?>
                    <p>Nenhuma nota arquivada.</p>
                <?php else:
                ?>
                    <?php foreach ($notas_arquivadas as $nota):
                    ?>
                        <div class="nota-arquivada">
                            <h3 class="nota-titulo"><?= htmlspecialchars($nota['titulo']) ?></h3>
                            <p class="nota-texto"><?= nl2br(htmlspecialchars($nota['descricao'])) ?></p>
                            
                            <!-- Exibir mídia se existir -->
                            <?php if (!empty($nota['imagem_url'])): ?>
                                <div class="nota-imagem">
                                    <img src="<?= htmlspecialchars($nota['imagem_url']) ?>" alt="Imagem da nota" style="max-width: 200px; height: auto;">
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($nota['video_url'])): ?>
                                <div class="nota-video">
                                    <video controls style="max-width: 200px; height: auto;">
                                        <source src="<?= htmlspecialchars($nota['video_url']) ?>" type="video/mp4">
                                        Seu navegador não suporta vídeos.
                                    </video>
                                </div>
                            <?php endif; ?>
                            
                            <div class="nota-info">
                                <small>Data: <?= date('d/m/Y H:i', strtotime($nota['data_hora'])) ?></small>
                                <?php if (!empty($nota['pasta'])): ?>
                                    <small>Pasta: <?= htmlspecialchars($nota['pasta']) ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="nota-botoes">
                                <button class="btn-desarquivar" data-id="<?= $nota['id'] ?>">Desarquivar</button>
                                <button class="btn-excluir" data-id="<?= $nota['id'] ?>">Mover para Lixeira</button>
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
        document.querySelectorAll('.btn-desarquivar').forEach(btn => {
            btn.addEventListener('click', function() {
                    fetch('../models/arquivadas_crud.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'action=desarquivar&id=' + this.dataset.id
                        })
                        .then(res => res.json())
                        .then(data => {
                            // alert(data.message);
                            if (data.success) location.reload();
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Erro ao desarquivar a nota.');
                        });
                
            });
        });

        document.querySelectorAll('.btn-excluir').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Mover esta nota para a lixeira?')) {
                    fetch('../models/arquivadas_crud.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'action=excluir&id=' + this.dataset.id
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            if (data.success) location.reload();
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Erro ao mover a nota para a lixeira.');
                        });
                }
            });
        });
    </script>
     <script src="../public/Javascript/script.js"></script>
</body>

</html>