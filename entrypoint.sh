#!/bin/bash

# Script de Inicialização do Render
# Este script é executado após o Docker container ser iniciado

set -e

echo "🚀 Iniciando aplicação Pixel Post..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Esperar um pouco para ter certeza que o banco está pronto
echo "⏳ Aguardando banco de dados ficar pronto..."
for i in {1..30}; do
    if php artisan db:check > /dev/null 2>&1; then
        echo "✅ Banco de dados conectado!"
        break
    fi
    echo "   Tentativa $i/30..."
    sleep 2
done

# Executar migrations
echo ""
echo "📊 Executando migrations..."
if php artisan migrate --force; then
    echo "✅ Migrations executadas com sucesso!"
else
    echo "⚠️  Erro nas migrations, continuando..."
fi

# Verificar se é primeira execução e fazer seed (opcional)
if [ "$SEED_DATABASE" = "true" ]; then
    echo ""
    echo "🌱 Seedando banco de dados..."
    if php artisan db:seed --force; then
        echo "✅ Banco seedado!"
    else
        echo "⚠️  Erro ao seedar, continuando..."
    fi
fi

# Limpar caches
echo ""
echo "🧹 Limpando caches..."
php artisan config:clear > /dev/null 2>&1 || true
php artisan route:clear > /dev/null 2>&1 || true
php artisan view:clear > /dev/null 2>&1 || true

# Regenerar caches otimizados
echo ""
echo "⚡ Regenerando caches otimizados..."
php artisan config:cache > /dev/null 2>&1 || true
php artisan route:cache > /dev/null 2>&1 || true
php artisan view:cache > /dev/null 2>&1 || true

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Aplicação pronta para produção!"
echo "🌐 URL: $APP_URL"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Executar comando passado (apache2-foreground)
exec "$@"
