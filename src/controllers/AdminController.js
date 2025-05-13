const Admin = require('../models/Admin');
const jwt = require('jsonwebtoken');

// Configuração do JWT
const gerarToken = (id) => {
  return jwt.sign({ id }, process.env.JWT_SECRET, {
    expiresIn: '24h'
  });
};

exports.registrar = async (req, res) => {
  try {
    const { nome, email, senha, nivel } = req.body;
    
    // Verificar se o admin já existe
    const adminExistente = await Admin.findOne({ email });
    if (adminExistente) {
      return res.status(400).json({ mensagem: 'Este email já está em uso' });
    }
    
    // Criar novo admin
    const novoAdmin = new Admin({
      nome,
      email,
      senha,
      nivel
    });
    
    await novoAdmin.save();
    
    // Gerar token
    const token = gerarToken(novoAdmin._id);
    
    res.status(201).json({
      _id: novoAdmin._id,
      nome: novoAdmin.nome,
      email: novoAdmin.email,
      nivel: novoAdmin.nivel,
      token
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao registrar administrador', erro: error.message });
  }
};

exports.login = async (req, res) => {
  try {
    const { email, senha } = req.body;
    
    // Buscar admin pelo email
    const admin = await Admin.findOne({ email });
    if (!admin) {
      return res.status(401).json({ mensagem: 'Email ou senha inválidos' });
    }
    
    // Verificar senha
    const senhaCorreta = await admin.verificarSenha(senha);
    if (!senhaCorreta) {
      return res.status(401).json({ mensagem: 'Email ou senha inválidos' });
    }
    
    // Atualizar último acesso
    admin.ultimoAcesso = Date.now();
    await admin.save();
    
    // Gerar token
    const token = gerarToken(admin._id);
    
    res.json({
      _id: admin._id,
      nome: admin.nome,
      email: admin.email,
      nivel: admin.nivel,
      token
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao fazer login', erro: error.message });
  }
};

exports.listarTodos = async (req, res) => {
  try {
    const admins = await Admin.find({}).select('-senha');
    res.json(admins);
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao listar administradores', erro: error.message });
  }
};

exports.buscarPorId = async (req, res) => {
  try {
    const admin = await Admin.findById(req.params.id).select('-senha');
    
    if (!admin) {
      return res.status(404).json({ mensagem: 'Administrador não encontrado' });
    }
    
    res.json(admin);
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao buscar administrador', erro: error.message });
  }
};

exports.atualizar = async (req, res) => {
  try {
    const { nome, email, nivel } = req.body;
    
    const admin = await Admin.findById(req.params.id);
    if (!admin) {
      return res.status(404).json({ mensagem: 'Administrador não encontrado' });
    }
    
    // Atualizar campos
    if (nome) admin.nome = nome;
    if (email) admin.email = email;
    if (nivel) admin.nivel = nivel;
    
    await admin.save();
    
    res.json({
      _id: admin._id,
      nome: admin.nome,
      email: admin.email,
      nivel: admin.nivel
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao atualizar administrador', erro: error.message });
  }
};

exports.atualizarSenha = async (req, res) => {
  try {
    const { senhaAtual, novaSenha } = req.body;
    
    const admin = await Admin.findById(req.params.id);
    if (!admin) {
      return res.status(404).json({ mensagem: 'Administrador não encontrado' });
    }
    
    // Verificar senha atual
    const senhaCorreta = await admin.verificarSenha(senhaAtual);
    if (!senhaCorreta) {
      return res.status(401).json({ mensagem: 'Senha atual incorreta' });
    }
    
    // Atualizar senha
    admin.senha = novaSenha;
    await admin.save();
    
    res.json({ mensagem: 'Senha atualizada com sucesso' });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao atualizar senha', erro: error.message });
  }
};

exports.excluir = async (req, res) => {
  try {
    const admin = await Admin.findById(req.params.id);
    if (!admin) {
      return res.status(404).json({ mensagem: 'Administrador não encontrado' });
    }
    
    await Admin.deleteOne({ _id: req.params.id });
    
    res.json({ mensagem: 'Administrador excluído com sucesso' });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao excluir administrador', erro: error.message });
  }
};