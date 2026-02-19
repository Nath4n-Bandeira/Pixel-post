pegue está parte 

# Pixel.Post 

> Rede social minimalista com estética retro pixel-art construída em Laravel para fins educacionais.

## Descrição

Pixel.Post é uma aplicação web de estudo em PHP/Laravel que implementa uma rede social tipo feed. Desenvolvida durante estudo de conceitos fundamentais do framework Laravel, autenticação, CRUD, upload de arquivos, relacionamentos entre modelos e design responsivo com Tailwind CSS 

### Pré-requisitos

- PHP 8.1+
- Composer
- Node.js 16+ (para Tailwind CSS)
- Git

### Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/pixel-post.git
cd pixel-post
```

2. Instale as dependências PHP
```bash
composer install
```

3. Instale as dependências Node
```bash
npm install
```

4. Configure o arquivo .env
```bash
cp .env.example .env
php artisan key:generate
```

5. Execute as migrações
```bash
php artisan migrate
```

6. Crie o link simbólico para storage público
```bash
php artisan storage:link
```

7. Inicie o servidor
```bash
php artisan serve
```

8. Em outro terminal, compile os assets (opcional)
```bash
npm run dev
```

Acesse o a porta do ip correspondente que o seu CLI mostrar no seu navegador.

---

## 🐳 Deployment com Docker

### Hospedagem no Render (Recomendado)

#### Setup Rápido (5 minutos)

```bash
# 1. Fazer push do código
git add .
git commit -m "chore: setup Docker deployment"
git push origin main

# 2. No Render Dashboard (render.com)
# - Clique "New +" → "Blueprint"
# - Selecione seu repositório GitHub
# - Render detectará render.yaml automaticamente
# - Clique "Deploy"

# 3. Acompanhe em tempo real
# - Dashboard → Logs
# - Migrations executam automaticamente
# - App fica online em https://seu-app.onrender.com
```

#### Testes Locais com Docker

```bash
# Iniciar ambiente completo
./docker-local.sh up

# Acessar a aplicação
# http://localhost:8080

# Executar migrations
./docker-local.sh migrate

# Ver logs
./docker-local.sh logs

# Parar
./docker-local.sh down
```

#### Documentação Completa

- 📚 [RENDER_QUICKSTART.md](./RENDER_QUICKSTART.md) - Setup em 5 minutos
- 📖 [DEPLOYMENT_RENDER.md](./DEPLOYMENT_RENDER.md) - Guia detalhado
- 📋 [DOCKER_SETUP_SUMMARY.md](./DOCKER_SETUP_SUMMARY.md) - Sumário técnico

#### Características do Setup Docker

- ✅ **Multi-stage Build**: Otimizado para produção
- ✅ **MySQL Automático**: Criado e gerenciado pelo Render
- ✅ **Migrations Automáticas**: Executadas no startup
- ✅ **Caches Otimizados**: Config e route caching
- ✅ **Health Checks**: Monitora saúde da aplicação
- ✅ **CI/CD**: GitHub Actions automático
- ✅ **Docker Compose**: Para testes locais
