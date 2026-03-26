# 🚀 Guia de Deploy no Render para Pixel-Post

## 📋 Passos para Colocar no Render

### 1️⃣ Preparar o Repositório
```bash
# Certifique-se de que tudo está commitado
git status
git add .
git commit -m "Preparar para deploy no Render"
git push origin main
```

### 2️⃣ Criar Conta no Render
- Acesse https://render.com
- Faça signup com GitHub
- Autorize Render a acessar seus repositórios

### 3️⃣ Conectar Repositório
1. No Dashboard do Render, clique em "New +"
2. Selecione "Web Service"
3. Conecte seu repositório `Pixel-post`
4. Configure:
   - **Name**: `pixel-post`
   - **Environment**: `Docker`
   - **Region**: `Oregon` (ou sua preferência)
   - **Plan**: `Free`

### 4️⃣ Configurar Variáveis de Ambiente

No painel do Render, vá até a seção "Environment" e adicione TODAS estas variáveis:

```
APP_NAME = PixelPost
APP_ENV = production
APP_DEBUG = false
APP_KEY = (deixa vazio - será gerado automaticamente)
APP_URL = https://seu-app-name.onrender.com

DB_CONNECTION = pgsql
DB_HOST = ep-tight-surf-a8wjxpjy-pooler.eastus2.azure.neon.tech
DB_PORT = 5432
DB_DATABASE = neondb
DB_USERNAME = neondb_owner
DB_PASSWORD = npg_UIliZqHxJX92
DB_SSLMODE = require
DB_STATEMENT_TIMEOUT = 0

SESSION_DRIVER = database
SESSION_SECURE_COOKIE = true
SESSION_HTTP_ONLY = true
SESSION_SAME_SITE = lax

FILESYSTEM_DISK = local

CACHE_STORE = database

QUEUE_CONNECTION = database

MAIL_MAILER = smtp
MAIL_HOST = smtp.mailtrap.io
MAIL_PORT = 465
MAIL_USERNAME = (seu username do Mailtrap - opcional)
MAIL_PASSWORD = (sua password do Mailtrap - opcional)
MAIL_FROM_ADDRESS = noreply@pixel-post.com
MAIL_FROM_NAME = Pixel Post

LOG_CHANNEL = stack
LOG_LEVEL = debug
```

### 5️⃣ Deploy
1. Clique em "Deploy"
2. Acompanhe o build nos logs
3. Quando terminar, você receberá uma URL: `https://seu-app.onrender.com`

---

## ✅ Checklist de Variáveis Essenciais

### Database (PostgreSQL Neon)
```
✓ DB_CONNECTION = pgsql
✓ DB_HOST = ep-tight-surf-a8wjxpjy-pooler.eastus2.azure.neon.tech
✓ DB_PORT = 5432
✓ DB_DATABASE = neondb
✓ DB_USERNAME = neondb_owner
✓ DB_PASSWORD = npg_UIliZqHxJX92
✓ DB_SSLMODE = require
```

### Application
```
✓ APP_ENV = production
✓ APP_DEBUG = false
✓ APP_KEY = (gerado automaticamente)
✓ APP_URL = (sua URL do Render)
```

### Session & Cache
```
✓ SESSION_DRIVER = database
✓ SESSION_SECURE_COOKIE = true
✓ CACHE_STORE = database
✓ QUEUE_CONNECTION = database
```

### Storage (Avatares)
```
✓ FILESYSTEM_DISK = local
(O arquivo render-start.sh cria o symlink automaticamente)
```

---

## 🔧 Configurações por Variável

### Sérias ⚠️ (NÃO MUDE)
- `DB_SSLMODE = require` → Necessário para PostgreSQL Neon
- `SESSION_DRIVER = database` → Sessões no banco para distribuído
- `CACHE_STORE = database` → Cache no banco

### Segurança 🔐
- `APP_DEBUG = false` → Nunca true em produção
- `SESSION_SECURE_COOKIE = true` → HTTPS apenas
- `SESSION_HTTP_ONLY = true` → Protege contra XSS
- `SESSION_SAME_SITE = lax` → Proteção CSRF

### Performance 🚀
- Render cacheará rotas e views automaticamente
- O banco PostgreSQL Neon é rápido
- Avatares serão servidos via `public/storage`

---

## 📝 O que Mudou no Seu Projeto

### ✅ Dockerfile
- Adicionado suporte a `pdo_pgsql` (PostgreSQL)
- Mantém `pdo_sqlite` como fallback

### ✅ render.yaml
- Configuração para PostgreSQL Neon
- Variáveis mapeadas corretamente
- Sem banco de dados integrado (usa Neon)

### ✅ render-start.sh
- Simplificado para PostgreSQL
- Cria symlink de storage
- Roda migrações automaticamente
- Caches configurações

---

## 🆘 Troubleshooting

### Build falha?
```bash
# Verificar logs
- Clique em "Logs" no painel
- Procure por erro em "Failed to build"
- Geralmente é permissão de arquivo
```

### Página não carrega?
```
1. Verifique se URL_APP está correto
2. Confira variáveis de banco
3. Veja em "Logs" se migration rodou
```

### Avatares não aparecem?
```
1. Rode: php artisan storage:link (render-start.sh faz isso)
2. Verifique se public/storage existe
3. Permissões devem ser 775
```

### Erro 419 (CSRF)?
```
- SESSION_DRIVER deve ser "database"
- SESSION_SECURE_COOKIE deve ser "true"
```

---

## 💡 Dicas Importantes

1. **Primeiro Deploy**: Pode levar alguns minutos
2. **Migrações**: Rodam automaticamente no start
3. **Static Files**: CSS/JS são servidos pelo Apache
4. **Avatares**: Salvos em `storage/app/public/avatars/`
5. **Sessões**: Armazenadas no PostgreSQL Neon

---

## 🔗 Links Úteis

- Render Dashboard: https://dashboard.render.com
- PostgreSQL Neon: https://console.neon.tech
- Documentação Laravel: https://laravel.com/docs
- Render Docs: https://render.com/docs

