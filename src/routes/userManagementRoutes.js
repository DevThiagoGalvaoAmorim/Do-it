const express = require('express');
const router = express.Router();
const userController = require('../controllers/UserController');
const { proteger } = require('../middleware/authMiddleware');

// Todas as rotas requerem autenticação de administrador
router.use(proteger);

// Rotas de gerenciamento de usuários
router.post('/', userController.criarUsuario);
router.get('/', userController.listarUsuarios);
router.get('/:id', userController.buscarUsuarioPorId);
router.put('/:id', userController.atualizarUsuario);
router.patch('/:id/senha', userController.redefinirSenha);
router.patch('/:id/status', userController.alterarStatusUsuario);
router.delete('/:id', userController.excluirUsuario);

module.exports = router;