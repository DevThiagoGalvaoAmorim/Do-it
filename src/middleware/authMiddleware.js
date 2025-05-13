const jwt = require('jsonwebtoken');
const Admin = require('../models/Admin');

exports.proteger = async (req, res, next) => {
  try {
    let token;
    
    // Verificar se o token existe no header
    if (req.headers.authorization && req.headers.authorization.startsWith('Bearer')) {
      token = req.headers.authorization.split(' ')[1];
    }
    
    if (!token) {
      return res.status(401).json({ mensagem: 'Acesso não autorizado. Faça login para continuar.' });
    }
    
    // Verificar token
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    
    // Buscar admin pelo ID
    const adminAtual = await Admin.findById(decoded.id).select('-senha');
    if (!adminAtual) {
      return res.status(401).json({ mensagem: 'Token inválido. Usuário não encontrado.' });
    }
    
    // Adicionar admin ao request
    req.admin = adminAtual;
    next();
  } catch (error) {
    res.status(401).json({ mensagem: 'Acesso não autorizado. Token inválido ou expirado.' });
  }
};

exports.restringirA = (...niveis) => {
  return (req, res, next) => {
    if (!niveis.includes(req.admin.nivel)) {
      return res.status(403).json({ 
        mensagem: 'Você não tem permissão para realizar esta ação' 
      });
    }
    next();
  };
};