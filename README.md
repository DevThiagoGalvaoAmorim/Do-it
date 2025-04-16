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
  Descrição: O usuário consegue de forma simples utilizar a aplicação para criar uma novo cadastro no sistema,
  sendo essa uma instância de sua utilização no sistema.

  Tarefas:   
      Criar o visual dessa tela, com um formulário enviando os dados via método POST.
      Adicionar ao banco de dados a Relação com os dados do usuário.
      Criar uma função que faz a inserção da instância do novo usuário no sistema.
      Criar o uma função que recebe como parâmetro, os dados do formulário, e, faz a autenticação, ou recusa a autenticação com uma mensagem de erro,
      de acordo com a busca feita no banco de dados.
      Mostrar o visual da mensagem de erro.

### RF01 - Cadastro de Usuário

| **Id**        | **RF01**                                                                                                                                             |
|---------------|------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Cadastro de Usuário                                                                                                                                  |
| **Descrição** | Permitir que o usuário crie um novo cadastro no sistema de forma simples, representando uma nova instância de uso.                                  |
| **Tarefas**   | - Desenvolver a interface da tela de cadastro, com formulário que envia dados via método `POST`.<br>                                                |
|               | - Criar a estrutura no banco de dados para armazenar os dados do usuário.<br>                                                                        |
|               | - Implementar função para inserir a instância do novo usuário no banco de dados.<br>                                                                |
|               | - Implementar função que:<br>&nbsp;&nbsp;&nbsp;&nbsp;• Recebe os dados do formulário;<br>&nbsp;&nbsp;&nbsp;&nbsp;• Verifica autenticidade no banco;<br>&nbsp;&nbsp;&nbsp;&nbsp;• Retorna mensagem de erro se necessário.<br> |
|               | - Exibir mensagem de erro de forma visual na interface, em caso de falha.                                                                            |


**RF02** - Login do Usuário e Administrador  
  Descrição: O usuário deve conseguir efetuar seu login (autenticação) no sistema, com seu cadastro previamente efetuado. Essa capacidade do sistema 
  deve ser simples e rápida de ser realizada pelo usuário.

  Tarefas:
      Criar o visual da tela de login, com um formulário enviando os dados via método POST.
      Utiliar a função de busca e verificação, criada em RF01.
      Criar a mensagem de erro a ser apresentada ao usuário.

### RF02 - Login do Usuário e Administrador

| **Id**        | RF02                                                                                                                                              |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Login do Usuário e Administrador                                                                                                                  |
| **Descrição** | O usuário deve conseguir efetuar seu login no sistema, com cadastro previamente efetuado. O processo deve ser simples e rápido.                  |
| **Tarefas**   | - Criar o visual da tela de login com formulário que envia dados via método `POST`.<br>                                                          |
|               | - Utilizar a função de busca e verificação criada em RF01.<br>                                                                                    |
|               | - Criar a mensagem de erro a ser apresentada ao usuário.                                                                                          |


**RF03** - Criação de Notas (Anotações, Lembretes, Listas de Tarefas)  
  Descrição: O usuário deve conseguir criar notas, de forma simples, podendo visualizá-las em um painel de  Notas. 

  Tarefas:
      Criar uma Página que seja capaz de mostrar notas de forma padronizada, independente da quantidade de notas, onde todas as notas tem o mesmo tamanho
      na exbição de seu Resumo e Título.
      Fazer um método Iterativo que faz a busca de todas as notas de um usuário no banco de dados e o carregamento dessas para exibição
      Criar um construtor de Anotação, onde faz a construção de uma anotação, mediante o tipo de anotação; Lembrete, Anotação e Lista de Tarefas.
        obs: esse método serve para salvar uma nova nota no banco de dados, quanto para recuperar uma nota do banco de dados.
      Fazer o método de carregamento de notas para o Painel de notas.
      Criar a funcionalidade de busca de notas por título.

### RF03 - Criação de Notas

| **Id**        | RF03                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Criação de Notas (Anotações, Lembretes, Listas de Tarefas)                                                                                                               |
| **Descrição** | O usuário deve conseguir criar notas de forma simples, podendo visualizá-las em um painel de notas.                                                                     |
| **Tarefas**   | - Criar uma página que exiba as notas de forma padronizada, independente da quantidade.<br>                                                                             |
|               | - Criar método iterativo para buscar todas as notas do usuário no banco e carregá-las para exibição.<br>                                                                |
|               | - Criar um construtor de anotação para os tipos: Lembrete, Anotação e Lista de Tarefas (salvar e recuperar).<br>                                                        |
|               | - Desenvolver o método de carregamento de notas para o painel de notas.<br>                                                                                              |
|               | - Criar a funcionalidade de busca de notas por título.                                                                                                                   |


      
**RF04** - Edição de Notas  
  Descrição: O usuário deve conseguir editar uma nota já criada.

  Tarefas:
      Com uma nota já criada, o usuário deve conseguir mudar os dados daquela nota, e ainda, salvá-las para futura modificação, exclusão, arquivamento ou nova
      Edição.

### RF04 - Edição de Notas

| **Id**        | RF04                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Edição de Notas                                                                                                                                                          |
| **Descrição** | O usuário deve conseguir editar uma nota já criada.                                                                                                                      |
| **Tarefas**   | - Permitir a modificação de dados de uma nota já criada.<br>                                                                                                              |
|               | - Garantir a persistência dessas alterações para futuras edições, exclusões ou arquivamentos.                                                                            |

   
**RF05** - Exclusão de Notas  
  Descrição: O usuário deve conseguir excluir uma nota já criada.

  Tarefas:
      Criar o método de Enviar a lixeira, para futura exclusão da nota.
      Criar o atributo Lixeira, pertencente a cada nota, e nesta contém a validade da nota. Assim que inicializado, terá um prazo para sua autoexclusão.
      Criar o método que recupera uma nota da lixeira.
      Criar o método de exclusão imediata de uma nota.

### RF05 - Exclusão de Notas

| **Id**        | RF05                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Exclusão de Notas                                                                                                                                                        |
| **Descrição** | O usuário deve conseguir excluir uma nota já criada.                                                                                                                     |
| **Tarefas**   | - Criar método de envio para lixeira, com exclusão futura.<br>                                                                                                            |
|               | - Adicionar o atributo "Lixeira" à nota, com prazo de autoexclusão.<br>                                                                                                  |
|               | - Criar método de recuperação de nota da lixeira.<br>                                                                                                                     |
|               | - Criar método de exclusão imediata.                                                                                                                                     |


**RF06** - Arquivamento de Notas  
  Descrição: O usuário deve conseguir arquivar uma nota criada. Uma nota na lixeira não está arquiva.

  Tarefas: 
      Criar um método que atribua o estado de arquivada a uma nota.
      Criar uma método que carregue as notas no estado arquivadas.
      Criar um método que retire a nota do estado de arquivada.

### RF06 - Arquivamento de Notas

| **Id**        | RF06                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Arquivamento de Notas                                                                                                                                                    |
| **Descrição** | O usuário deve conseguir arquivar uma nota criada. Notas na lixeira não estão arquivadas.                                                                                |
| **Tarefas**   | - Criar método para atribuir estado de arquivada à nota.<br>                                                                                                              |
|               | - Criar método para carregar notas arquivadas.<br>                                                                                                                        |
|               | - Criar método para remover o estado de arquivada da nota.                                                                                                                |


**RF07** - Organização em Pastas  
  Descrição: O usuário deve conseguir colocar uma nota dentro de uma pasta. As notas arquivadas ou em lixeira não estão em nenhuma pasta. Com isso, o usuário
  consegue acessar todas as suas pastas, e para cada pasta, o usuário deve conseguir visualizar todas as suas notas de maneira simples.

  Tarefas:
      Criar o metodo de Criar novas Pastas
      Criar um metodo de excluir pastas.
      Criar o metodo de atribuir pasta, que relaciona uma pasta a um metodo.
      Criar o metodo de carregar notas de uma pasta.

### RF07 - Organização em Pastas

| **Id**        | RF07                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Organização em Pastas                                                                                                                                                    |
| **Descrição** | O usuário deve conseguir colocar uma nota dentro de uma pasta. Notas arquivadas ou na lixeira não pertencem a pastas.                                                    |
| **Tarefas**   | - Criar método para criação de novas pastas.<br>                                                                                                                          |
|               | - Criar método para exclusão de pastas.<br>                                                                                                                               |
|               | - Criar método de atribuição de pasta a uma nota.<br>                                                                                                                     |
|               | - Criar método para carregar as notas de uma pasta.                                                                                                                       |

      
**RF08** - Pesquisa de Notas por Título  
  Descrição: O usuário deve conseguir fazer uma busca nas notas carregadas no painel, a busca considera o que for estiver escrito na barra de busca para 
  critério de ordenação das notas carregas no painel.

  Tarefas:
      Criar um metodo que retorna uma lista ordenada conforme um critério passado com parâmetro.
      Criar um metodo que carrega as Notas do painel conforme uma lista de ordenação.

### RF08 - Pesquisa de Notas por Título

| **Id**        | RF08                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Pesquisa de Notas por Título                                                                                                                                             |
| **Descrição** | O usuário deve conseguir realizar busca nas notas carregadas no painel, com base no conteúdo digitado na barra de pesquisa.                                             |
| **Tarefas**   | - Criar método que retorna lista de notas ordenada conforme critério passado como parâmetro.<br>                                                                         |
|               | - Criar método que carrega as notas no painel conforme a lista de ordenação.                                                                                             |


**RF09** - Edição de Dados Pessoais do Usuário  
  Descrição: O usuário deve conseguir modificar seus dados pessoais no sistema.

  Tarefas:
      Criar a tela de edição de dados cadastrais.
      Criar o metodo de edição de dados do usuario.
      Criar um formulario que faz a mudança de um dado do usuario no sistema.

### RF09 - Edição de Dados Pessoais do Usuário

| **Id**        | RF09                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Edição de Dados Pessoais do Usuário                                                                                                                                      |
| **Descrição** | O usuário deve conseguir modificar seus dados pessoais no sistema.                                                                                                       |
| **Tarefas**   | - Criar tela de edição de dados cadastrais.<br>                                                                                                                           |
|               | - Criar método de edição de dados do usuário.<br>                                                                                                                         |
|               | - Criar formulário para atualização de dados.                                                                                                                             |


**RF10** - Exclusão da Conta do Usuário  
  Descrição: O usuario deve conseguir excluir sua conta do sistema.

  Tarefas:
      Criar o metodo de exclusão de Instancia do usuario, onde todas as tuplas relacionadas a esse usuario serão deletadas do sistema.

### RF10 - Exclusão da Conta do Usuário

| **Id**        | RF10                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Exclusão da Conta do Usuário                                                                                                                                             |
| **Descrição** | O usuário deve conseguir excluir sua conta do sistema.                                                                                                                   |
| **Tarefas**   | - Criar método de exclusão da instância do usuário, removendo todas as tuplas relacionadas no sistema.                                                                   |


**RF11** - Gerenciamento de Usuários pelo Administrador  
  Descrição: O usuario administrador deve ter permissões maior em relação ao sistema e suas funcionalidades, deve conseguir gerenciar os
  usuarios, podendo ver as estatisticas de uso gerais do sistema, com permissões de exclusão de usuario, e podendo visualizar o log de 
  suas atividades do sistema.

Tarefas:
	Criar uma forma de gerar e armazenar os registros das atividades e ações do usuario no sistema. 
	Criar o metodo de exclusão do usuario no sistema.
	Criar o metodo de gerar estatísticas
	Crira a tela de visualização de estatísticas do sistema.

### RF11 - Gerenciamento de Usuários pelo Administrador

| **Id**        | RF11                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Gerenciamento de Usuários pelo Administrador                                                                                                                             |
| **Descrição** | O administrador deve ter permissões elevadas, podendo visualizar estatísticas do sistema, excluir usuários e acessar o log de atividades.                                |
| **Tarefas**   | - Criar método de geração e armazenamento de registros das atividades dos usuários.<br>                                                                                  |
|               | - Criar método de exclusão de usuários.<br>                                                                                                                               |
|               | - Criar método de geração de estatísticas.<br>                                                                                                                            |
|               | - Criar tela para visualização das estatísticas do sistema.                                                                                                               |


**RF12** - Recuperação de Senha
  Descrição: O usuário deve ter algum suporte do sistema para conseguir recuperar sua senha, e, recuperar seu acesso ao sistema.

  Tarefas:  
      Criar a página de recuperação de senha.
      Criar o metodo de verificação de senha
      Criar a validação de verificacao.
      Criar o metodo de atualização de senha.

### RF12 - Recuperação de Senha

| **Id**        | RF12                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Recuperação de Senha                                                                                                                                                      |
| **Descrição** | O usuário deve ter suporte do sistema para recuperar sua senha e restaurar o acesso.                                                                                      |
| **Tarefas**   | - Criar página de recuperação de senha.<br>                                                                                                                               |
|               | - Criar método de verificação de identidade.<br>                                                                                                                          |
|               | - Criar validação da verificação.<br>                                                                                                                                     |
|               | - Criar método de atualização da senha.                                                                                                                                   |

---

### User Stories

**US01**
Criar Estrutura da Página Inicial do Sistema  
Eu, como usuário, cadastrado ou não, quero visualizar uma página inicial simples e informativa ao acessar o sistema, para que eu entenda do que se trata o sistema e saiba como iniciar o uso ou realizar o login.

**US02**   
Implementar CRUD Básico de Notas  
Eu, como usuário, logado no sistema, quero criar e visualizar notas simples, para que eu possa começar a organizar minhas informações dentro do sistema.

**US03**  
Configurar Conexão com Banco de Dados  
Eu, como desenvolvedor, quero configurar a conexão com o banco de dados, para garantir que os dados sejam armazenados de forma persistente e segura no sistema.

**US04**  
Prototipar Interface com HTML/CSS  
Eu, como usuário, quero interagir com uma interface funcional, mesmo que simples, para que eu possa testar as primeiras funcionalidades de criação e listagem de notas.

**US05**   
Criar Navegação entre Telas  
Eu, como usuário, quero navegar entre diferentes seções do sistema, como a página inicial e o painel de notas, para acessar facilmente as funcionalidades básicas do sistema.

**US06**  
Implementar Cadastro de Usuário  
Eu, como novo usuário, quero me cadastrar no sistema, fornecendo meus dados pessoais e criando uma senha, para poder acessar minha área exclusiva e utilizar o sistema de forma segura.

**US07**   
Implementar Login com Sessão  
Eu, como usuário registrado, quero fazer login no sistema, para acessar meu painel de notas e outras funcionalidades restritas ao meu perfil.

**US08**   
Diferenciar Papéis (Usuário e Admin)  
Eu, como administrador, quero acessar funcionalidades exclusivas de moderação e controle, para que eu possa gerenciar os usuários e manter o sistema organizado e seguro.

**US09**  
Criar Página de Logout e Encerrar Sessão  
Eu, como usuário logado, quero poder sair do sistema com segurança, encerrando minha sessão, para proteger meus dados e evitar acessos não autorizados.

**US10**   
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

