# API do Serviço de Verificação Ortográfica

Este diretório contém o serviço de verificação ortográfica refatorado que fornece funcionalidade de verificação ortográfica do lado do servidor para a aplicação Do-it.

## Visão Geral da Arquitetura

O serviço de verificação ortográfica foi refatorado de uma implementação do lado do cliente usando Typo.js para uma arquitetura baseada em API do lado do servidor com os seguintes componentes:

### Componentes Principais

1. **SpellcheckService.php** - Classe de serviço principal que gerencia a lógica de verificação ortográfica
2. **spellcheck_api.php** - Endpoint da API REST para comunicação cliente-servidor
3. **SpellcheckConfig.php** - Gerenciamento de configuração e constantes
4. **test_spellcheck.php** - Conjunto de testes para validação

### Integração do Lado do Cliente

- **spellcheck.js** - Cliente JavaScript refatorado que se comunica com a API
- Removida dependência da biblioteca Typo.js
- Adicionado mecanismo de cache para melhor performance

## Endpoints da API

### URL Base
```
/services/spellcheck_api.php
```

### Ações Disponíveis

#### 1. Verificar Palavra
Verifica se uma única palavra está escrita corretamente.

**Requisição:**
```
POST /services/spellcheck_api.php
Content-Type: application/x-www-form-urlencoded

action=check&word=casa&language=pt_BR
```

**Resposta:**
```json
{
  "success": true,
  "data": {
    "word": "casa",
    "correct": true,
    "language": "pt_BR"
  },
  "version": "1.0"
}
```

#### 2. Obter Sugestões
Obter sugestões de ortografia para uma palavra com erro.

**Requisição:**
```
POST /services/spellcheck_api.php
Content-Type: application/x-www-form-urlencoded

action=suggest&word=xyzabc&language=pt_BR&max_suggestions=5
```

**Resposta:**
```json
{
  "success": true,
  "data": {
    "word": "xyzabc",
    "suggestions": ["palavra1", "palavra2", "palavra3"]
  },
  "version": "1.0"
}
```

#### 3. Verificar Texto
Verificar múltiplas palavras em um bloco de texto.

**Requisição:**
```
POST /services/spellcheck_api.php
Content-Type: application/x-www-form-urlencoded

action=check_text&text=Esta casa está bonita&language=pt_BR
```

**Resposta:**
```json
{
  "success": true,
  "data": {
    "text": "Esta casa está bonita",
    "results": [
      {"word": "Esta", "correct": true},
      {"word": "casa", "correct": true},
      {"word": "está", "correct": true},
      {"word": "bonita", "correct": true}
    ]
  },
  "version": "1.0"
}
```

#### 4. Obter Idiomas Disponíveis
Recuperar lista de idiomas suportados.

**Requisição:**
```
POST /services/spellcheck_api.php
Content-Type: application/x-www-form-urlencoded

action=languages
```

**Resposta:**
```json
{
  "success": true,
  "data": {
    "languages": ["pt_BR"]
  },
  "version": "1.0"
}
```

## Configuração

O serviço é configurado através do `SpellcheckConfig.php` com as seguintes configurações:

- **Idioma Padrão:** pt_BR
- **Comprimento Máximo da Palavra:** 50 caracteres
- **Comprimento Mínimo da Palavra:** 2 caracteres
- **Comprimento Máximo do Texto:** 10.000 caracteres
- **TTL do Cache:** 5 minutos
- **Máximo de Sugestões:** 10

## Recursos de Performance

1. **Cache do Lado do Cliente:** Palavras são armazenadas em cache por 5 minutos para reduzir chamadas à API
2. **Processamento em Lote:** Múltiplas palavras podem ser verificadas em paralelo
3. **Entrada com Debounce:** O destaque do texto tem debounce para prevenir chamadas excessivas à API
4. **Tratamento de Erros:** Fallback gracioso quando a API não está disponível

## Tratamento de Erros

A API retorna respostas de erro estruturadas:

```json
{
  "success": false,
  "error": "Parâmetro word é obrigatório",
  "version": "1.0"
}
```

Códigos de erro comuns:
- `WORD_REQUIRED` - Parâmetro word está ausente
- `TEXT_REQUIRED` - Parâmetro text está ausente
- `INVALID_ACTION` - Ação inválida especificada
- `LANGUAGE_NOT_SUPPORTED` - Idioma não suportado
- `WORD_TOO_LONG` - Palavra excede o comprimento máximo
- `TEXT_TOO_LONG` - Texto excede o comprimento máximo

## Testes

Execute o conjunto de testes para validar a funcionalidade:

```bash
php test_spellcheck.php
```

## Notas de Migração

### O que Mudou

1. **Dependências do Lado do Cliente Removidas:**
   - Biblioteca Typo.js removida
   - Arquivos de dicionário não são mais carregados no cliente
   - Tamanho do bundle JavaScript reduzido

2. **Processamento do Lado do Servidor Adicionado:**
   - Serviço de verificação ortográfica baseado em PHP
   - Arquitetura de API RESTful
   - Gerenciamento centralizado de dicionários

3. **Performance Melhorada:**
   - Cache do lado do cliente
   - Processamento de palavras em lote
   - Tratamento de entrada com debounce

4. **Tratamento de Erros Aprimorado:**
   - Respostas de erro estruturadas
   - Degradação graceful
   - Log abrangente

### Benefícios

- **Carga Reduzida no Cliente:** Processamento de dicionário movido para o servidor
- **Melhor Cache:** Oportunidades de cache do lado do servidor
- **Atualizações Centralizadas:** Atualizações de dicionário não requerem mudanças no cliente
- **Segurança Melhorada:** Arquivos de dicionário não expostos ao cliente
- **Escalabilidade:** Pode lidar com múltiplos usuários simultâneos eficientemente

## Melhorias Futuras

1. **Suporte Multi-idioma:** Adicionar suporte para idiomas adicionais
2. **Dicionários Personalizados:** Permitir adições de palavras específicas do usuário
3. **Sugestões Avançadas:** Implementar sugestões conscientes do contexto
4. **Monitoramento de Performance:** Adicionar métricas e monitoramento
5. **Limitação de Taxa:** Implementar limitação de taxa da API para prevenção de abuso