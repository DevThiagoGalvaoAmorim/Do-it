<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';  // Fixed path
require_once __DIR__ . '/cloudinary_service.php';

$action = $_POST['action'] ?? null;

try {
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
        $tipo = $_POST['tipo'] ?? 'Checklist';
        $id_usuario = $_SESSION['id'] ?? null;  // Added null coalescing
        
        $imagem_url = null;
        $video_url = null;
        
        // Instanciar CloudinaryService uma vez
        $cloudinary = new CloudinaryService();
        
        // Processar upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $cloudinary->uploadFile($_FILES['imagem'], 'image');
            if ($uploadResult['success']) {
                $imagem_url = $uploadResult['url'];
            }
        }
        
        // Processar upload de vídeo
        if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $cloudinary = new CloudinaryService();
            $uploadResult = $cloudinary->uploadFile($_FILES['video'], 'video');
            if ($uploadResult['success']) {
                $video_url = $uploadResult['url'];
            }
        }
    
        // Debug logging removed for production
        
        if ($id_usuario) {
            // Inserir a nota com o ID do usuário e URLs de mídia
            $stmt = $pdo->prepare("INSERT INTO notas (titulo, descricao, pasta, tipo, id_usuario, imagem_url, video_url) VALUES (:titulo, :descricao, :pasta, :tipo, :id_usuario, :imagem_url, :video_url)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':pasta' => $pasta,
                ':tipo' => $tipo,
                ':id_usuario' => $id_usuario,
                ':imagem_url' => $imagem_url,
                ':video_url' => $video_url
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Nota criada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuário não está logado ou sessão expirou.']);
        }
    } elseif ($action === 'read') {
        // Ler todas as notas do usuário
        $id_usuario = $_SESSION['id'];
        if($id_usuario){
            $stmt = $pdo->prepare("SELECT * FROM notas WHERE id_usuario = :id_usuario");
            $stmt->execute([':id_usuario' => $id_usuario]);
            $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado']);
        }
        echo json_encode($notas);
    } elseif ($action === 'update') {
        // Atualizar uma nota
        $id = $_POST['id'] ?? null;
        $id_usuario = $_SESSION['id'];
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
        
        // Buscar URLs atuais
        $stmt = $pdo->prepare("SELECT imagem_url, video_url FROM notas WHERE id = :id AND id_usuario = :id_usuario");
        $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);
        $notaAtual = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $imagem_url = $notaAtual['imagem_url'];
        $video_url = $notaAtual['video_url'];
        
        $cloudinary = new CloudinaryService();
        
        // Processar novo upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            // Delete old image from Cloudinary
            if ($imagem_url) {
                $oldImagePublicId = $cloudinary->extractPublicId($imagem_url);
                $cloudinary->deleteFile($oldImagePublicId);
            }
            
            $uploadResult = $cloudinary->uploadFile($_FILES['imagem'], 'image');
            if ($uploadResult['success']) {
                $imagem_url = $uploadResult['url'];
            }
        }
        
        // Processar novo upload de vídeo
        if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $cloudinary->uploadFile($_FILES['video'], 'video');
            if ($uploadResult['success']) {
                $video_url = $uploadResult['url'];
            }
        }
    
        if ($id && $id_usuario) {
            $stmt = $pdo->prepare("UPDATE notas SET titulo = :titulo, descricao = :descricao, pasta = :pasta, imagem_url = :imagem_url, video_url = :video_url WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':pasta' => $pasta,
                ':imagem_url' => $imagem_url,
                ':video_url' => $video_url,
                ':id' => $id,
                ':id_usuario' => $id_usuario
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Nota atualizada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID da nota ou ID do usuário não fornecido.']);
        }
    } elseif ($action === 'delete') {
        // Deletar uma nota (movendo antes para a lixeira)
        $id = $_POST['id'];
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            // 1. Buscar a nota
            $stmt = $pdo->prepare("SELECT * FROM notas WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);
            $nota = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nota) {
                // 2. Inserir na lixeira
                $stmtLixeira = $pdo->prepare("INSERT INTO lixeira (titulo, descricao, data_hora, pasta, id_usuario, tipo, imagem_url, video_url) VALUES (:titulo, :descricao, :data_hora, :pasta, :id_usuario, :tipo, :imagem_url, :video_url)");
                $stmtLixeira->execute([
                    ':titulo' => $nota['titulo'],
                    ':descricao' => $nota['descricao'],
                    ':data_hora' => $nota['data_hora'],
                    ':pasta' => $nota['pasta'],
                    ':id_usuario' => $nota['id_usuario'],
                    ':tipo' => $nota['tipo'],
                    ':imagem_url' => $nota['imagem_url'],
                    ':video_url' => $nota['video_url']
                ]);

                // 3. Deletar da tabela notas
                $stmtDelete = $pdo->prepare("DELETE FROM notas WHERE id = :id AND id_usuario = :id_usuario");
                $stmtDelete->execute([':id' => $id, ':id_usuario' => $id_usuario]);

                echo json_encode(['success' => true, 'message' => 'Nota movida para a lixeira com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nota não encontrada.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'id da nota não fornecido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>