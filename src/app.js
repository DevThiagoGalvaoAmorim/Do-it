const express = require('express');
const mongoose = require('mongoose');
const dotenv = require('dotenv');
const cors = require('cors');
const path = require('path');

// Carregar variáveis de ambiente
dotenv.config();

// Inicializar app
const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Servir arquivos estáticos da pasta www
app.use(express.static(path.join(__dirname, '../www')));

// Conectar ao MongoDB
mongoose.connect(process.env.MONGODB_URI)
  .then(() => console.log('Conectado ao MongoDB'))
  .catch(err => console.error('Erro ao conectar ao MongoDB:', err));

// Rotas da API
app.use('/api/admin', require('./routes/adminRoutes'));
app.use('/api/usuarios', require('./routes/userManagementRoutes'));

// Rotas para as páginas HTML
app.get('/login', (req, res) => {
  res.sendFile(path.join(__dirname, '../www/login.html'));
});

app.get('/cadastro', (req, res) => {
  res.sendFile(path.join(__dirname, '../www/cadastro.html'));
});

// Rota padrão
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, '../www/index.html'));
});

// Middleware de tratamento de erros
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ mensagem: 'Erro interno do servidor' });
});

// Iniciar servidor
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Servidor rodando na porta ${PORT}`));