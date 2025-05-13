const User = require('../models/User');

exports.criarUsuario = async (req, res) => {
  try {
    const { nome, email, senha, telefone, status } = req.body;
    
    // Verificar se o usuário já existe
    const usuarioExistente = await User.findOne({ email });
    if (usuarioExistente) {
      return res.status(400).json({ mensagem: 'Este email já está em uso' });
    }
    
    // Criar novo usuário
    const novoUsuario = new User({
      nome,
      email,
      senha,
      telefone,
      status
    });
    
    await novoUsuario.save();
    
    res.status(201).json({
      _id: novoUsuario._id,
      nome: novoUsuario.nome,
      email: novoUsuario.email,
      telefone: novoUsuario.telefone,
      status: novoUsuario.status
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao criar usuário', erro: error.message });
  }
};

exports.listarUsuarios = async (req, res) => {
  try {
    // Opções de paginação
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const skip = (page - 1) * limit;
    
    // Opções de filtro
    const filtro = {};
    if (req.query.status) filtro.status = req.query.status;
    if (req.query.busca) {
      filtro.$or = [
        { nome: { $regex: req.query.busca, $options: 'i' } },
        { email: { $regex: req.query.busca, $options: 'i' } }
      ];
    }
    
    // Contar total de usuários
    const total = await User.countDocuments(filtro);
    
    // Buscar usuários
    const usuarios = await User.find(filtro)
      .select('-senha')
      .sort({ dataCriacao: -1 })
      .skip(skip)
      .limit(limit);
    
    res.json({
      usuarios,
      paginacao: {
        total,
        paginas: Math.ceil(total / limit),
        paginaAtual: page,
        porPagina: limit
      }
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao listar usuários', erro: error.message });
  }
};

exports.buscarUsuarioPorId = async (req, res) => {
  try {
    const usuario = await User.findById(req.params.id).select('-senha');
    
    if (!usuario) {
      return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }
    
    res.json(usuario);
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao buscar usuário', erro: error.message });
  }
};

exports.atualizarUsuario = async (req, res) => {
  try {
    const { nome, email, telefone, status } = req.body;
    
    const usuario = await User.findById(req.params.id);
    if (!usuario) {
      return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }
    
    // Verificar se o email já está em uso por outro usuário
    if (email && email !== usuario.email) {
      const emailExistente = await User.findOne({ email });
      if (emailExistente) {
        return res.status(400).json({ mensagem: 'Este email já está em uso por outro usuário' });
      }
    }
    
    // Atualizar campos
    if (nome) usuario.nome = nome;
    if (email) usuario.email = email;
    if (telefone) usuario.telefone = telefone;
    if (status) usuario.status = status;
    
    await usuario.save();
    
    res.json({
      _id: usuario._id,
      nome: usuario.nome,
      email: usuario.email,
      telefone: usuario.telefone,
      status: usuario.status
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao atualizar usuário', erro: error.message });
  }
};

exports.redefinirSenha = async (req, res) => {
  try {
    const { novaSenha } = req.body;
    
    const usuario = await User.findById(req.params.id);
    if (!usuario) {
      return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }
    
    // Atualizar senha
    usuario.senha = novaSenha;
    await usuario.save();
    
    res.json({ mensagem: 'Senha redefinida com sucesso' });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao redefinir senha', erro: error.message });
  }
};

exports.excluirUsuario = async (req, res) => {
  try {
    const usuario = await User.findById(req.params.id);
    if (!usuario) {
      return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }
    
    await User.deleteOne({ _id: req.params.id });
    
    res.json({ mensagem: 'Usuário excluído com sucesso' });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao excluir usuário', erro: error.message });
  }
};

exports.alterarStatusUsuario = async (req, res) => {
  try {
    const { status } = req.body;
    
    if (!['ativo', 'inativo', 'bloqueado'].includes(status)) {
      return res.status(400).json({ mensagem: 'Status inválido' });
    }
    
    const usuario = await User.findById(req.params.id);
    if (!usuario) {
      return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }
    
    usuario.status = status;
    await usuario.save();
    
    res.json({
      _id: usuario._id,
      nome: usuario.nome,
      email: usuario.email,
      status: usuario.status
    });
  } catch (error) {
    res.status(500).json({ mensagem: 'Erro ao alterar status do usuário', erro: error.message });
  }
};