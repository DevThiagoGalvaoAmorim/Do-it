# Testes Manuais da API Markdown

## Checklist de Funcionalidades

### markdownToHTML()
- [ ] Headers (# ## ###)
- [ ] Negrito (**texto**)
- [ ] Itálico (*texto*)
- [ ] Links [texto](url)
- [ ] Código inline `código`
- [ ] Blocos de código ```código```
- [ ] Listas (- item)
- [ ] Blockquotes (> texto)
- [ ] Entrada vazia/null
- [ ] Sanitização XSS

### hasMarkdownSyntax()
- [ ] Detecta todos os padrões markdown
- [ ] Retorna false para texto simples
- [ ] Trata entradas vazias

### truncateMarkdown()
- [ ] Trunca texto longo
- [ ] Remove sintaxe markdown
- [ ] Preserva texto curto
- [ ] Adiciona "..." quando necessário

### renderMarkdownInElement()
- [ ] Renderiza em elemento DOM
- [ ] Adiciona classe markdown-content
- [ ] Trata elemento null

## Casos de Teste Específicos

1. **Teste de Headers:**
   - Input: `# Título Principal`
   - Esperado: `<h1>Título Principal</h1>`

2. **Teste de Negrito:**
   - Input: `**texto importante**`
   - Esperado: `<strong>texto importante</strong>`

3. **Teste de Links:**
   - Input: `[Google](https://google.com)`
   - Esperado: `<a href="https://google.com">Google</a>`

4. **Teste de Sanitização:**
   - Input: `<script>alert('xss')</script># Título`
   - Esperado: Script removido, título preservado