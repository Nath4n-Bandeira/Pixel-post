# 📋 Referência Rápida de Comandos

## 🚀 Deploy (Escolha um)

### Opção 1: Automática (Recomendado)
```bash
./deploy.sh
```

### Opção 2: Manual
```bash
git add .
git commit -m "chore: deploy to Render"
git push origin main
```

---

## 🧪 Testes Locais

### Iniciar Ambiente
```bash
./docker-local.sh up
# Acesse: http://localhost:8080
```

### Ver Logs
```bash
./docker-local.sh logs
# ou especificamente do DB
./docker-local.sh logs-db
```

### Executar Migrations
```bash
./docker-local.sh migrate
```

### Abrir Shell no Container
```bash
./docker-local.sh shell
# Agora você está dentro do container
php artisan tinker
```

### Parar
```bash
./docker-local.sh down
```

---

## 📊 Gerenciar Aplicação Localmente

### Comandos Artisan via Docker
```bash
./docker-local.sh artisan migrate
./docker-local.sh artisan tinker
./docker-local.sh artisan config:clear
./docker-local.sh artisan queue:listen
```

### Comandos Composer via Docker
```bash
./docker-local.sh composer require package/name
./docker-local.sh composer install
./docker-local.sh composer update
```

### Comandos NPM via Docker
```bash
./docker-local.sh npm install
./docker-local.sh npm run build
./docker-local.sh npm run dev
```

---

## 🔄 Rebuild & Reset

### Rebuild da Imagem Docker
```bash
./docker-local.sh rebuild
```

### Reset Completo (CUIDADO!)
```bash
./docker-local.sh down
docker-compose down -v
```

### Fresh Database (Migrations + Seed)
```bash
./docker-local.sh fresh
```

---

## 📖 Documentação

### Começar Rápido (5 min)
```bash
cat RENDER_QUICKSTART.md
```

### Guia Completo (20 min)
```bash
cat DEPLOYMENT_RENDER.md
```

### Sumário Técnico (15 min)
```bash
cat DOCKER_SETUP_SUMMARY.md
```

### Checklist Pré-Deploy
```bash
cat DEPLOYMENT_CHECKLIST.md
```

### Visão Geral
```bash
cat SETUP_COMPLETE.md
```

---

## 🐳 Docker Direto

### Build Manualmente
```bash
docker build -t pixel-post .
```

### Rodar Container Direto
```bash
docker run -p 8080:80 pixel-post
```

### Ver Imagens
```bash
docker images
```

### Ver Containers Rodando
```bash
docker ps
```

### Ver Todos os Containers
```bash
docker ps -a
```

---

## 🗄️ Banco de Dados

### Conexão Local
```
Host: localhost
Port: 3306
Database: pixel_post
User: pixel_user
Password: pixel_password
```

### MySQL Client (se instalado)
```bash
mysql -h 127.0.0.1 -u pixel_user -p pixel_post
```

### Via Container
```bash
docker-compose exec mysql mysql -u pixel_user -p pixel_post
```

---

## 📊 Monitoramento

### Status dos Containers
```bash
docker-compose ps
```

### Recursos Utilizados
```bash
docker stats
```

### Network
```bash
docker network ls
docker network inspect pixel-network
```

### Volumes
```bash
docker volume ls
docker volume inspect pixel-post_mysql_data
```

---

## 🔧 Troubleshooting

### Container não inicia?
```bash
docker-compose logs app
docker-compose logs mysql
```

### Porta em uso?
```bash
# Linux/Mac
lsof -i :8080

# Windows
netstat -ano | findstr :8080
```

### Reset tudo
```bash
./docker-local.sh down
docker system prune -a  # Remove imagens não usadas
docker-compose up --build
```

### Validar Docker
```bash
docker --version
docker-compose --version
```

---

## 🌐 Acessos

### Aplicação Local
```
http://localhost:8080
```

### Banco de Dados Local
```
Host: localhost:3306
Database: pixel_post
```

### Render Dashboard
```
https://dashboard.render.com
```

### Aplicação em Produção
```
https://seu-app.onrender.com
```

---

## 🎯 Workflow Comum

### 1. Desenvolvimento
```bash
./docker-local.sh up          # Iniciar
# ... escrever código ...
./docker-local.sh migrate     # Rodar migrations
./docker-local.sh logs        # Ver logs
```

### 2. Commit
```bash
git add .
git commit -m "feature: description"
```

### 3. Deploy
```bash
./deploy.sh
# ou
git push origin main
```

### 4. Verificar
```bash
# Acessar https://dashboard.render.com
# Ver logs em tempo real
# Acesso em https://seu-app.onrender.com
```

---

## 📝 Git Commands Úteis

### Status
```bash
git status
git log --oneline -10
```

### Branches
```bash
git branch
git checkout -b feature/name
git merge feature/name
```

### Push
```bash
git push origin main          # Push direto
git push origin feature/name  # Push branch
```

### Pull
```bash
git pull origin main
git fetch origin
```

---

## 🚨 Erros Comuns

### Erro: "Address already in use"
```bash
# Mude a porta em docker-compose.yml
# Ou parar containers existentes
./docker-local.sh down
```

### Erro: "Connection refused"
```bash
# MySQL ainda está iniciando
# Aguarde 5-10 segundos
./docker-local.sh logs mysql
```

### Erro: "Migrations failed"
```bash
# Ver erro completo
./docker-local.sh logs app

# Corrigir migration e tentar novamente
./docker-local.sh migrate
```

### Erro: "npm ERR!"
```bash
# Limpar cache npm
docker-compose exec -T app npm cache clean --force
docker-compose exec -T app npm install
```

---

## ✨ Scripts Úteis

### Lista de arquivos criados
```bash
bash FILES_CREATED.sh
```

### Validar Render
```bash
# Testar render.yaml
cat render.yaml

# Testar build
docker build -t test .
```

### Health Check Manual
```bash
curl http://localhost:8080
curl -v http://localhost:8080/

# Com Docker
docker-compose exec app curl http://localhost
```

---

## 🔐 Variáveis de Ambiente

### Ver variáveis locais
```bash
docker-compose exec app env | grep APP
docker-compose exec app env | grep DB
```

### Editar .env
```bash
nano .env      # Linux/Mac
notepad .env   # Windows
```

### Regenerar chave
```bash
./docker-local.sh artisan key:generate
```

---

## 📱 Teste de Responsividade

### Local
```bash
# Navegador: Abrir DevTools (F12)
# Simular diferentes devices
http://localhost:8080
```

### Produção
```bash
# Acessar em mobile/tablet
https://seu-app.onrender.com
```

---

## 🎓 Referências Rápidas

```bash
# Curl teste
curl -I http://localhost:8080        # Header only
curl -v http://localhost:8080        # Verbose
curl -X POST http://localhost:8080   # POST

# Docker comum
docker ps -q | xargs docker kill     # Kill all
docker-compose restart               # Restart
docker-compose top                   # Processos

# Git cherry-pick
git cherry-pick <commit-hash>        # Copy commit
```

---

## 📞 Suporte Rápido

```bash
# Documentação rápida
cat RENDER_QUICKSTART.md

# Full reference
cat DEPLOYMENT_RENDER.md

# Antes de fazer push
cat DEPLOYMENT_CHECKLIST.md

# Começar aqui
cat START_HERE.md
```

---

**Última atualização**: Fevereiro 2026
