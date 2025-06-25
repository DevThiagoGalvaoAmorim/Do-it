# Testes da API de Markdown

Este diretório contém os testes unitários e de integração para a funcionalidade de markdown do projeto.

## Estrutura dos Testes

### 1. Testes PHP (PHPUnit)
- **MarkdownIntegrationTest.php**: Testes de integração que verificam o armazenamento e processamento de conteúdo markdown no banco de dados.

### 2. Testes JavaScript (Navegador)
- **test_markdown.html**: Testes unitários para as funções JavaScript de markdown executados diretamente no navegador.

## Como Executar os Testes

### Pré-requisitos
1. Instalar dependências do Composer:
   ```bash
   cd /Users/raphael/Do-it-2
   composer install
   ```

2. Configurar banco de dados de teste (opcional - usar banco de desenvolvimento)

### Executar Testes PHP
```bash
# No diretório raiz do projeto
cd /Users/raphael/Do-it-2
./vendor/bin/phpunit www/tests/MarkdownIntegrationTest.php

# Ou executar todos os testes
./vendor/bin/phpunit
```

### Executar Testes JavaScript
```bash
# Usando o script npm
npm run test:markdown

# Ou abrir diretamente no navegador
open www/tests/test_markdown.html
```

## Funcionalidades Testadas

### Testes PHP (MarkdownIntegrationTest)
1. **testMarkdownContentStorage**: Verifica se conteúdo markdown é armazenado corretamente no banco
2. **testMarkdownPatternDetection**: Testa detecção de padrões markdown (headers, bold, italic, etc.)
3. **testMarkdownSanitization**: Verifica tratamento de conteúdo potencialmente malicioso
4. **testMarkdownTruncation**: Testa truncamento de texto markdown para previews

### Testes JavaScript (test_markdown.html)
1. **markdownToHTML**: Conversão de markdown para HTML com sanitização
2. **hasMarkdownSyntax**: Detecção de sintaxe markdown
3. **truncateMarkdown**: Truncamento de texto para previews
4. **renderMarkdownInElement**: Renderização de markdown em elementos DOM

## Estrutura do Banco de Dados

Os testes assumem as seguintes tabelas:
- **usuarios**: (id, nome, email, senha)
- **notas**: (id, titulo, descricao, pasta, tipo, id_usuario)

## Observações

- Os testes PHP criam e removem dados de teste automaticamente
- ID de usuário de teste: 999
- A sanitização de conteúdo malicioso ocorre no frontend (JavaScript)
- Os testes são independentes e podem ser executados em qualquer ordem