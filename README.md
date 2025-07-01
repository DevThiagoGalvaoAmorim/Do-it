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

### Scripts de Teste

#### Testes da API de Markdown
O projeto inclui testes para a API de markdown que podem ser executados no navegador:

**Multiplataforma (recomendado):**
```bash
npm run test:markdown
```

**Scripts específicos por sistema operacional:**
- **macOS:** `npm run test:markdown:mac`
- **Linux:** `npm run test:markdown:linux`  
- **Windows:** `npm run test:markdown:windows`

O script principal `test:markdown` detecta automaticamente o sistema operacional e usa o comando apropriado:
- macOS: `open`
- Windows: `start`
- Linux: `xdg-open`

#### Testes de Integração PHP
Para executar os testes de integração com PHPUnit:

```bash
# Instalar dependências
composer install

# Executar testes específicos de markdown
./vendor/bin/phpunit www/tests/MarkdownIntegrationTest.php

# Executar todos os testes
./vendor/bin/phpunit
```

Consulte `www/tests/README.md` para mais detalhes sobre a configuração e execução dos testes.

---
 
### Requisitos Funcionais do Projeto

### Protótipo
Link do Figma:[Clique aqui](https://www.figma.com/proto/NkrOmzGeP9iJk7x515aj9f/Do-it---Prototype001?node-id=1-3&p=f&t=rdSItcbIqmxTrU6p-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A3)

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


### RF02 - Login do Usuário e Administrador

| **Id**        | RF02                                                                                                                                              |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Login do Usuário e Administrador                                                                                                                  |
| **Descrição** | O usuário deve conseguir efetuar seu login no sistema, com cadastro previamente efetuado. O processo deve ser simples e rápido.                  |
| **Tarefas**   | - Criar o visual da tela de login com formulário que envia dados via método `POST`.<br>                                                          |
|               | - Utilizar a função de busca e verificação criada em RF01.<br>                                                                                    |
|               | - Criar a mensagem de erro a ser apresentada ao usuário.                                                                                          |


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


      

### RF04 - Edição de Notas

| **Id**        | RF04                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Edição de Notas                                                                                                                                                          |
| **Descrição** | O usuário deve conseguir editar uma nota já criada.                                                                                                                      |
| **Tarefas**   | - Permitir a modificação de dados de uma nota já criada.<br>                                                                                                              |
|               | - Garantir a persistência dessas alterações para futuras edições, exclusões ou arquivamentos.                                                                            |

   

### RF05 - Exclusão de Notas

| **Id**        | RF05                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Exclusão de Notas                                                                                                                                                        |
| **Descrição** | O usuário deve conseguir excluir uma nota já criada.                                                                                                                     |
| **Tarefas**   | - Criar método de envio para lixeira, com exclusão futura.<br>                                                                                                            |
|               | - Adicionar o atributo "Lixeira" à nota, com prazo de autoexclusão.<br>                                                                                                  |
|               | - Criar método de recuperação de nota da lixeira.<br>                                                                                                                     |
|               | - Criar método de exclusão imediata.                                                                                                                                     |



### RF06 - Arquivamento de Notas

| **Id**        | RF06                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Arquivamento de Notas                                                                                                                                                    |
| **Descrição** | O usuário deve conseguir arquivar uma nota criada. Notas na lixeira não estão arquivadas.                                                                                |
| **Tarefas**   | - Criar método para atribuir estado de arquivada à nota.<br>                                                                                                              |
|               | - Criar método para carregar notas arquivadas.<br>                                                                                                                        |
|               | - Criar método para remover o estado de arquivada da nota.                                                                                                                |



### RF07 - Organização em Módulos

| **Id**        | RF07                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Organização em Pastas                                                                                                                                                    |
| **Descrição** | O usuário deve conseguir colocar uma nota dentro de uma pasta. Notas arquivadas ou na lixeira não pertencem a pastas.                                                    |
| **Tarefas**   | - Criar método para criação de novas pastas.<br>                                                                                                                          |
|               | - Criar método para exclusão de pastas.<br>                                                                                                                               |
|               | - Criar método de atribuição de pasta a uma nota.<br>                                                                                                                     |
|               | - Criar método para carregar as notas de uma pasta.                                                                                                                       |

      

### RF08 - Pesquisa de Notas por Título

| **Id**        | RF08                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Pesquisa de Notas por Título                                                                                                                                             |
| **Descrição** | O usuário deve conseguir realizar busca nas notas carregadas no painel, com base no conteúdo digitado na barra de pesquisa.                                             |
| **Tarefas**   | - Criar método que retorna lista de notas ordenada conforme critério passado como parâmetro.<br>                                                                         |
|               | - Criar método que carrega as notas no painel conforme a lista de ordenação.                                                                                             |



### RF09 - Edição de Dados Pessoais do Usuário

| **Id**        | RF09                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Edição de Dados Pessoais do Usuário                                                                                                                                      |
| **Descrição** | O usuário deve conseguir modificar seus dados pessoais no sistema.                                                                                                       |
| **Tarefas**   | - Criar tela de edição de dados cadastrais.<br>                                                                                                                           |
|               | - Criar método de edição de dados do usuário.<br>                                                                                                                         |
|               | - Criar formulário para atualização de dados.                                                                                                                             |



### RF10 - Exclusão da Conta do Usuário

| **Id**        | RF10                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Exclusão da Conta do Usuário                                                                                                                                             |
| **Descrição** | O usuário deve conseguir excluir sua conta do sistema.                                                                                                                   |
| **Tarefas**   | - Criar método de exclusão da instância do usuário, removendo todas as tuplas relacionadas no sistema.                                                                   |



### RF11 - Gerenciamento de Usuários pelo Administrador

| **Id**        | RF11                                                                                                                                                                      |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nome**      | Gerenciamento de Usuários pelo Administrador                                                                                                                             |
| **Descrição** | O administrador deve ter permissões elevadas, podendo visualizar estatísticas do sistema, excluir usuários e acessar o log de atividades.                                |
| **Tarefas**   | - Criar método de geração e armazenamento de registros das atividades dos usuários.<br>                                                                                  |
|               | - Criar método de exclusão de usuários.<br>                                                                                                                               |
|               | - Criar método de geração de estatísticas.<br>                                                                                                                            |
|               | - Criar tela para visualização das estatísticas do sistema.                                                                                                               |




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

**US01** - Criar Estrutura da Página Inicial do Sistema  
Eu, como usuário, cadastrado ou não, quero visualizar uma página inicial simples e informativa ao acessar o sistema, para que eu entenda do que se trata o sistema e saiba como iniciar o uso ou realizar o login.

**US02** - Implementar CRUD Básico de Notas  
Eu, como usuário, logado no sistema, quero criar e visualizar notas simples, para que eu possa começar a organizar minhas informações dentro do sistema.

**US03** - Configurar Conexão com Banco de Dados  
Eu, como desenvolvedor, quero configurar a conexão com o banco de dados, para garantir que os dados sejam armazenados de forma persistente e segura no sistema.

**US04** - Prototipar Interface com HTML/CSS  
Eu, como usuário, quero interagir com uma interface funcional, mesmo que simples, para que eu possa testar as primeiras funcionalidades de criação e listagem de notas.

**US05** - Criar Navegação entre Telas  
Eu, como usuário, quero navegar entre diferentes seções do sistema, como a página inicial e o painel de notas, para acessar facilmente as funcionalidades básicas do sistema.

**US06** - Implementar Cadastro de Usuário  
Eu, como novo usuário, quero me cadastrar no sistema, fornecendo meus dados pessoais e criando uma senha, para poder acessar minha área exclusiva e utilizar o sistema de forma segura.

**US07** - Implementar Login com Sessão  
Eu, como usuário registrado, quero fazer login no sistema, para acessar meu painel de notas e outras funcionalidades restritas ao meu perfil.

**US08**  - Diferenciar Papéis (Usuário e Admin)  
Eu, como administrador, quero acessar funcionalidades exclusivas de moderação e controle, para que eu possa gerenciar os usuários e manter o sistema organizado e seguro.

**US09**  - Criar Página de Logout e Encerrar Sessão  
Eu, como usuário logado, quero poder sair do sistema com segurança, encerrando minha sessão, para proteger meus dados e evitar acessos não autorizados.

**US10**  - Implementar Recuperação de Senha  
Eu, como usuário, quero recuperar minha senha caso eu a esqueça, para poder voltar a acessar minha conta sem precisar criar uma nova.

---
## Iteração 1 – Criação, Edição, Exclusão e Pesquisa de Notas  

**Valor:**  
Permitir que o usuário gerencie suas anotações pessoais de forma simples e eficiente, com funcionalidades de criação, edição, exclusão e busca por título, facilitando a organização de informações e tarefas.

**Objetivo:**  
Como usuário, desejo criar, editar, excluir e buscar notas de forma rápida e prática, para manter minhas informações organizadas e acessíveis de acordo com minha necessidade.

**Requisitos Relacionados:**  
- RF03 – Criação de Notas  
- RF04 – Edição de Notas  
- RF05 – Exclusão de Notas  
- RF08 – Pesquisa de Notas por Título
  
![image](https://github.com/user-attachments/assets/8dccc021-b4b6-4f32-bbf1-742029f7fb22)

![image](https://github.com/user-attachments/assets/46443cc4-0a1f-46ab-aeb1-193bf4ac2c6f)

### User Stories que se enquadram na iteração 

| User Story | Descrição |
|------------|-----------|
| **US02** | Implementar CRUD Básico de Notas – Criar e visualizar notas simples para organizar informações. |
| **US04** | Prototipar Interface com HTML/CSS – Interface funcional para testar a criação e listagem de notas. |
| **US05** | Criar Navegação entre Telas – Navegar entre a página inicial e o painel de notas. |

---
## Iteração 2 - Gerenciamento das notas

**Valor:** 
Garantir que o usuário seja capaz de organizar suas notas em pastas.

**Objetivo:** 
Como usuário, desejo organizar em pastas, definir prazo e remover minhas notas criadas.

**Requisitos Relacionados:** 
- RF07 - Organização em módulos 

---
## Iteração 3 – Login, Cadastro e Controle de Sessão  

**Valor:**  
Garantir que o usuário possa acessar sua área pessoal de forma segura, por meio de cadastro, login e controle de sessão, permitindo que apenas usuários autorizados acessem suas anotações e demais funcionalidades do sistema.

**Objetivo:**  
Como usuário, desejo me cadastrar, fazer login e encerrar minha sessão no sistema, para utilizar minhas funcionalidades com segurança e manter meu acesso exclusivo às minhas informações.

**Requisitos Relacionados:**  
- RF01 – Cadastro de Usuário  
- RF02 – Login do Usuário e Administrador  
- RF09 – Edição de Dados Pessoais do Usuário  
- RF10 – Exclusão da Conta do Usuário
- RF11 - Gerenciamento de Usuários pelo Administrador


![image](https://github.com/user-attachments/assets/af094729-ba3b-4e69-9c36-726c8665a042)

![image](https://github.com/user-attachments/assets/f2795ec4-f7c5-426a-84f7-97059ad7174f)

![Tela de Administrador - Usuários-Douglas](https://github.com/user-attachments/assets/dcf2d4b4-e5c8-4866-a5cd-106b6462fc82)

![Tela de Administrador - Log De Atividades-Douglas](https://github.com/user-attachments/assets/36b62a4e-6797-4b78-93c5-043c81c2b1cf)

![Tela de Administrador - Estatísticas-Douglas](https://github.com/user-attachments/assets/36224352-9d7d-4a74-8eef-62af22f0cae5)



### User Stories que se enquadram na Iteração 2

| User Story | Descrição |
|------------|-----------|
| **US06** | Implementar Cadastro de Usuário – Criar conta com dados pessoais e senha. |
| **US07** | Implementar Login com Sessão – Fazer login no sistema e acessar painel. |
| **US09** | Criar Página de Logout e Encerrar Sessão – Encerrar sessão com segurança. |

---

## 🚀 Como Executar o Projeto

Siga os passos abaixo para colocar a aplicação para rodar localmente na sua máquina.

### Pré-requisitos

Antes de começar, certifique-se de que você tem as seguintes ferramentas instaladas:

-   [**Docker**](https://www.docker.com/products/docker-desktop/)
-   [**Git**](https://git-scm.com/)

### Passos para Instalação

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/DevThiagoGalvaoAmorim/Do-it.git
    ```

2.  **Acesse o diretório do projeto:**
    ```bash
    cd Do-it
    ```

3.  **Construa e inicie os containers Docker:**
    ```bash
    docker-compose up --build
    ```

4.  **Acesse a aplicação:**
    Abra seu navegador e acesse o seguinte endereço:
    [http://localhost:8080/public/index.php](http://localhost:8080/public/index.php)

### 🛑 Para Parar a Execução

Para parar todos os containers relacionados ao projeto, execute o comando:

```bash
docker-compose down


