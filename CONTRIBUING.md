### Iteração 1 – Navegação e criação das notas da tela principal

| Feature                                                                                          | Autor                          | Revisor                        |   |
|--------------------------------------------------------------------------------------------------|--------------------------------|--------------------------------|---|
| Front-End das Telas Principais                                                                   | Ruam Marcos e Thiago Galvão    | Douglas Alves da Cruz          |✅|
| Tela do icone de usuário e alteração dos dados cadastrados                                       | Raphael Sales                  | João Sestari                   |✅|
| Banco de Dados do Sistema                                                                        | Raphael Sales                  | João Sestari                   |✅|
| Criação do rodapé da página                                                                      | João Sestari                   | Ruam Marcos Maciel dos Santos  |✅|
| Permitir ao usuário visualizar todas as suas notas cadastradas (RF03)                            | Todos os membros do grupo      | Todos os membros do grupo      |✅|
| Criação da Landing Page                                                                          | Guilherme da Silva Carvalho    | Thiago Galvão                  |✅|
| Implementar barra de pesquisa de notas por título (RF08)                                         | Guilherme da Silva Carvalho    | Ruam Marcos Maciel dos Santos  |✅|
| Conexão básica do banco de dados com tabelas de usuários e notas                                 | Douglas Alves da Cruz          | Guilherme da Silva Carvalho    |✅|
| Prototipação das telas                                                                           | Todos os membros do grupo      | Raphael Sales de Souza         |✅|

### Iteração 2 – Login, Cadastro e Controle de Sessão

| Feature                                                                                          | Autor                            | Revisor                          |   |
|--------------------------------------------------------------------------------------------------|----------------------------------|----------------------------------|---|
| Ordenação das notas                                                                              | Guilherme da Silva Carvalho      | João Sestari                     |✅|
| Criação das telas de administrador(Tela usuários)                                                | Guilherme da Silva Carvalho      | Thiago Galvão Amorim             |✅|
| Criação das telas de administrador(Estátisticas e Log de atividades)                             | João Sestari                     | Thiago Galvão Amorim             |✅|
| Controle de sessão de usuário e administrador                                                    | João Sestari                     | Ruam Marcos                      |✅|
| Criação das telas de login e cadastro                                                            | Thiago Galvão Amorim             | Raphael Sales                    |✅|
| CRUD do usuário e início de sessão                                                               | Ruam Marcos e Douglas Alves      | Douglas Alves                    |✅|
| CRUD do Administrador e início de sessão                                                         | Raphael Sales                    | Guilherme da Silva               |✅|
| Gerenciamento dos usuários da tela de administrador                                              | Raphael Sales                    | Ruam Marcos                      |✅|

### Iteração 3 – Implementação da Arquitetura MVC e Novas Features

| Feature                                                                                          | Autor                               | Revisor                            |   |
|--------------------------------------------------------------------------------------------------|-------------------------------------|------------------------------------|---|
| Implementação do padrão MVC na sessão usuario                                                    | Ruam, Thiago, Douglas               | João Sestari                       |✅|
| Assegurar a integriadade das camadas CONTROLLER e MODEL                                                            | Ruam Marcos         | Guilherme da Silva Carvalho      |✅|
| Implementação do padrão MVC na sessão administrador                                              | Guilherme, João Sestari, Douglas    | Raphael Sales                      |✅|
| Implementação do padrão MVC nos Views                                                            | João Sestari, Raphael Sales         | Guilherme da Silva Carvalho        |✅|
| Desenvolver tela de configurações do usuário                                                     | Douglas Alves da Cruz               | João Sestari                       |✅|
| Criar tela popup de informações do usuário                                                       | Guilherme da Silva Carvalho         | Thiago Galvão Amorim               |✅|
| Desenvolver estrutura de banco de dados para tela de notificações                                | Thiago Galvão Amorim                | Ruam Marcos                        |✅|
| Desenvolver estrutura de banco de dados para a tela de lixeira                                   | João Sestari                        | Guilherme da Silva Carvalho        |✅|
| Corrigir o back-end da tela de administrador e adicionar ícone na sidebar                        | Raphael Sales                       | Thiago Galvão Amorim               |✅|



<!-- precisamos melhorar a as features: do Ruam, 6°, 8°  e 9° -->



### Iteração 4 – Acrescentar API ao projeto | Exposição SaaS

| Feature                                                                     | Autor                       | Revisor                     |   |
| --------------------------------------------------------------------------- | --------------------------- | --------------------------- |---|
| **Implementar feature de renderização Markdown nas notas usando Marked.js** | Raphael Sales               | Thiago Galvão Amorim        |✅|
| **Implementar redefinição da senha**                                        | Ruam Marcos                 | João Sestari                |✅|
| **Implementar envio de notificações por e-mail com Mailgun**                | Thiago Galvão Amorim        | Guilherme da Silva Carvalho |✅|
| **Integrar Firebase Realtime Database para salvar e recuperar notas**       | João Sestari                | Raphael Sales               |✅|
| **Uso de API para correção ortográfica nos nota**                           | Guilherme da Silva Carvalho | Thiago Galvão Amorim        |✅|
| **Implementar gráficos nas telas de administrador**                         | Douglas Alves da Cruz       | Ruam Marcos                 |✅|

### Iteração 5 – Testes unitários | Exposição SaaS

| Feature                                                                     | Autor                       | Revisor                     |   |
| --------------------------------------------------------------------------- | --------------------------- | --------------------------- |---|
| **Criar testes unitários**                                                  | Todos os membros do grupo   | Todos                       |❌|
| **Corrigir feature de redefinição de senha**                                | Ruam Marcos                 | João Sestari                |❌|
| **Tela modal para usuário/ Para header**                                    | Ruam Marcos                 | Raphael Sales               |❌|
| **Criar funcionalidade das pastas**                                         | Ruam Marcos                 | Thiago Galvão Amorim        |❌|
| **Criar funcionalidade de log do admin(Externa/interna)**                   | Ruam Marcos                 | Guilherme da Silva Carvalho |❌|
| **Criação de rotas e restrição de acesso**                                  | Ruam Marcos                 | Raphael Sales               |❌|
| **Refatorar API interna/ Camada de serviço**                                | Douglas Alves               | Guilherme da Silva Carvalho |❌|
| **Refatorar API externa (markdown)/ Camada de serviço**                     | Thiago Galvão               | Douglas Alves               |❌|
| **Corrigir modo noturno**                                                   | Thiago Galvão               | Guilherme da Silva Carvalho |❌|
| **Criar notificação com prazo(para lembretes)**                             | Thiago Galvão               | Raphael Sales               |❌|
| **Refatorar API externa (Email)/ Camada de serviço**                        | Raphael Sales               | Douglas Alves               |❌|
| **Criar funcionalidade de notas arquivadas**                                | Douglas Alves               | Guilherme da Silva Carvalho |❌|
| **Refatorar API externa (Corretor ortográfico)/ Camada de serviço**         | Raphael Sales               | Guilherme da Silva Carvalho |❌|
| **Criar sessão de admin**                                                   | Guilherme da Silva Carvalho | Thiago Galvão Amorim        |❌|
| **A definir...**                                                             | Guilherme da Silva Carvalho | Thiago Galvão Amorim        |❌|
| **A definir...**                                                             |  Raphael Sales              | Thiago Galvão Amorim        |❌|







