# Engenharia de Software 2024.2 - Universidade Federal do Tocantins  
Bacharelado em Ciência da Computação, 4º semestre  
Professor: Edeilson Milhomem da Silva  
Grupo: 
* Thiago Galvâo Amorim
* Douglas Alves da Cruz
* Ruam Marcos Maciel dos Santos
* João Sestari Galvão
* Guilherme da Silva Carvalho
* Raphael Sales de Souza

## Sistema de Notas

### Descrição  
O Sistema de Notas é uma aplicação voltada para organização pessoal de informações, permitindo ao usuário criar, editar e gerenciar notas, lembretes, listas de tarefas e agrupá-las em pastas. O sistema também possibilita personalização de perfil e gerenciamento de conta, com diferentes níveis de acesso (Usuário e Administrador).

---

### Requisitos Funcionais do Projeto

**RF01** - Cadastro de Usuário  
**RF02** - Login do Usuário e Administrador  
**RF03** - Criação de Notas (Anotações, Lembretes, Listas de Tarefas)  
**RF04** - Edição de Notas  
**RF05** - Exclusão de Notas  
**RF06** - Arquivamento de Notas  
**RF07** - Organização em Pastas  
**RF08** - Pesquisa de Notas por Título  
**RF09** - Edição de Dados Pessoais do Usuário  
**RF10** - Exclusão da Conta do Usuário  
**RF11** - Gerenciamento de Usuários pelo Administrador  
**RF12** - Recuperação de Senha

---

### User Stories

**US01 -  
Criar Estrutura da Página Inicial do Sistema  
Eu, como usuário, cadastrado ou não, quero visualizar uma página inicial simples e informativa ao acessar o sistema, para que eu entenda do que se trata o sistema e saiba como iniciar o uso ou realizar o login.

**US02 -   
Implementar CRUD Básico de Notas  
Eu, como usuário, logado no sistema, quero criar e visualizar notas simples, para que eu possa começar a organizar minhas informações dentro do sistema.

**US03 -  
Configurar Conexão com Banco de Dados  
Eu, como desenvolvedor, quero configurar a conexão com o banco de dados, para garantir que os dados sejam armazenados de forma persistente e segura no sistema.

**US04 -  
Prototipar Interface com HTML/CSS  
Eu, como usuário, quero interagir com uma interface funcional, mesmo que simples, para que eu possa testar as primeiras funcionalidades de criação e listagem de notas.

**US05 -   
Criar Navegação entre Telas  
Eu, como usuário, quero navegar entre diferentes seções do sistema, como a página inicial e o painel de notas, para acessar facilmente as funcionalidades básicas do sistema.

**US06 -  
Implementar Cadastro de Usuário  
Eu, como novo usuário, quero me cadastrar no sistema, fornecendo meus dados pessoais e criando uma senha, para poder acessar minha área exclusiva e utilizar o sistema de forma segura.

**US07 -   
Implementar Login com Sessão  
Eu, como usuário registrado, quero fazer login no sistema, para acessar meu painel de notas e outras funcionalidades restritas ao meu perfil.

**US08 -   
Diferenciar Papéis (Usuário e Admin)  
Eu, como administrador, quero acessar funcionalidades exclusivas de moderação e controle, para que eu possa gerenciar os usuários e manter o sistema organizado e seguro.

**US09 -  
Criar Página de Logout e Encerrar Sessão  
Eu, como usuário logado, quero poder sair do sistema com segurança, encerrando minha sessão, para proteger meus dados e evitar acessos não autorizados.

**US10 -   
Implementar Recuperação de Senha  
Eu, como usuário, quero recuperar minha senha caso eu a esqueça, para poder voltar a acessar minha conta sem precisar criar uma nova.

---

### Iteração 1 - Cadastro e Notas

**Valor:** Permitir que o usuário inicie sua experiência criando notas de forma simples e eficiente.  
**Objetivo:** Como usuário, quero poder criar e visualizar notas simples.  

**Requisitos:**
- RF01 - Cadastro de Usuário  
- RF03 - Criação de Notas  
- RF08 - Pesquisa de Notas por Título  

**Features:**
1. Criar formulário de cadastro
2. Implementar CRUD básico de notas
3. Tela de visualização de notas
4. Barra de busca para filtrar por título

Protótipos de Tela:  
`./prototipos/cadastro.png`  
`./prototipos/notas.png`

---

### Iteração 2 - Login e Controle de Sessão

**Valor:** Permitir acesso seguro ao sistema por meio de autenticação e controle de sessões.  
**Objetivo:** Como usuário, quero poder fazer login e acessar minhas funcionalidades.  

**Requisitos:**
- RF02 - Login do Usuário e Administrador  
- RF12 - Recuperação de Senha  
- RF11 - Gerenciamento de Usuários pelo Administrador  

**Features:**
1. Implementar login e logout
2. Diferenciar permissões entre usuário e admin
3. Implementar tela de recuperação de senha
4. Permitir gerenciamento de usuários pelo admin

Protótipo de Tela:  
`./prototipos/login.png`

---

### Iteração 3 - Edição, Exclusão e Organização

**Valor:** Permitir a personalização e organização de notas, bem como a exclusão segura de dados.  
**Objetivo:** Como usuário, quero editar e organizar minhas notas em pastas.  

**Requisitos:**
- RF04 - Edição de Notas  
- RF05 - Exclusão de Notas  
- RF07 - Organização em Pastas  

**Features:**
1. Tela de edição de notas
2. Botão para deletar notas
3. Criar e gerenciar pastas

Protótipo de Tela:  
`./prototipos/pastas.png`

---

### Iteração 4 - Integração com API

**Valor:** Permitir que o sistema ofereça uma interface moderna e conectada, com integração a APIs RESTful.  
**Objetivo:** Como desenvolvedor, quero que as operações possam ser feitas via API.  

**Requisitos:**
- RF03 a RF08 com suporte a API

**Features:**
1. Endpoints RESTful para autenticação e notas
2. Integração com frontend via JavaScript

---

### Iteração 5 - Testes e Finalização

**Valor:** Garantir a qualidade e segurança do sistema com testes e refino das funcionalidades.  
**Objetivo:** Como desenvolvedor, quero testar as funcionalidades do sistema e refiná-lo para entrega final.  

**Requisitos:**
- Todos os anteriores

**Features:**
1. Testes unitários com PHPUnit
2. Correções finais e ajustes visuais
3. Documentação final

---

