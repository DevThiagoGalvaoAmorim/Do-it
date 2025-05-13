const express = require('express');
const router = express.Router();
const adminController = require('../controllers/AdminController');
const { proteger, restringirA } = require('../middleware/authMiddleware');

// Rotas públicas
router.post('/login', adminController.login);

// Rotas protegidas
router.use(proteger);

// Rotas para super admin
router.post('/registrar', restringirA('super'), adminController.registrar);
router.get('/', restringirA('super'), adminController.listarTodos);
router.delete('/:id', restringirA('super'), adminController.excluir);

// Rotas para todos os admins
router.get('/perfil', adminController.buscarPorId);
router.put('/:id', adminController.atualizar);
router.patch('/:id/senha', adminController.atualizarSenha);

module.exports = router;