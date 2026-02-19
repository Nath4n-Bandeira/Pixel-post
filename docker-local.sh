#!/bin/bash

# Script para gerenciar Docker Compose localmente
# Use: ./docker-local.sh [up|down|logs|rebuild|fresh]

set -e

COMMAND=${1:-help}

case $COMMAND in
    up)
        echo "🚀 Iniciando containers..."
        docker-compose up -d
        echo "✅ Containers iniciados!"
        echo "🌐 Acesse: http://localhost:8080"
        echo "💾 Banco de dados: localhost:3306"
        ;;
    
    down)
        echo "🛑 Parando containers..."
        docker-compose down
        echo "✅ Containers parados!"
        ;;
    
    logs)
        echo "📋 Logs da aplicação:"
        docker-compose logs -f app
        ;;
    
    logs-db)
        echo "📋 Logs do banco de dados:"
        docker-compose logs -f mysql
        ;;
    
    rebuild)
        echo "🔨 Reconstruindo imagem Docker..."
        docker-compose down
        docker-compose build --no-cache
        docker-compose up -d
        echo "✅ Imagem reconstruída e containers iniciados!"
        ;;
    
    fresh)
        echo "🧹 Resetando aplicação (migrations e seeds)..."
        docker-compose exec -T app php artisan migrate:fresh --seed
        echo "✅ Aplicação resetada!"
        ;;
    
    migrate)
        echo "📊 Executando migrations..."
        docker-compose exec -T app php artisan migrate
        echo "✅ Migrations executadas!"
        ;;
    
    seed)
        echo "🌱 Seedando banco de dados..."
        docker-compose exec -T app php artisan db:seed
        echo "✅ Banco seedado!"
        ;;
    
    shell)
        echo "🔧 Abrindo shell no container..."
        docker-compose exec app bash
        ;;
    
    artisan)
        shift
        echo "▶️ Executando artisan: $@"
        docker-compose exec -T app php artisan "$@"
        ;;
    
    composer)
        shift
        echo "▶️ Executando composer: $@"
        docker-compose exec -T app composer "$@"
        ;;
    
    npm)
        shift
        echo "▶️ Executando npm: $@"
        docker-compose exec -T app npm "$@"
        ;;
    
    *)
        echo "🐳 Pixel Post - Docker Local Manager"
        echo ""
        echo "Uso: ./docker-local.sh [COMANDO]"
        echo ""
        echo "Comandos disponíveis:"
        echo "  up              - Inicia os containers"
        echo "  down            - Para os containers"
        echo "  logs            - Mostra logs da app"
        echo "  logs-db         - Mostra logs do banco"
        echo "  rebuild         - Reconstrói a imagem Docker"
        echo "  fresh           - Reset completo (migrate + seed)"
        echo "  migrate         - Executa migrations"
        echo "  seed            - Executa seeders"
        echo "  shell           - Abre bash no container da app"
        echo "  artisan [...]   - Executa comando artisan"
        echo "  composer [...]  - Executa comando composer"
        echo "  npm [...]       - Executa comando npm"
        echo ""
        echo "Exemplos:"
        echo "  ./docker-local.sh artisan migrate"
        echo "  ./docker-local.sh composer require package/name"
        echo "  ./docker-local.sh npm install"
        ;;
esac
