#!/bin/bash

# ========================================
# SCRIPT DE CORREÇÃO - PARÓQUIAS
# Execute este script no servidor web
# ========================================

echo "🔧 Iniciando correção do sistema de paróquias..."
echo ""

# Ir para o diretório do projeto
cd /var/www/html/lojinha-segueme || cd ~/public_html/lojinha-segueme || cd ~/lojinha-segueme

# Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ ERRO: Não foi possível encontrar o diretório do projeto Laravel!"
    echo "Por favor, ajuste o caminho no script."
    exit 1
fi

echo "✓ Diretório do projeto encontrado"
echo ""

# 1. Sincronizar arquivo de paróquias
echo "📋 Passo 1: Sincronizando arquivo de paróquias..."
php artisan paroquias:sync

# 2. Criar link simbólico do storage (se não existir)
echo ""
echo "🔗 Passo 2: Criando link simbólico do storage..."
php artisan storage:link

# 3. Limpar todos os caches
echo ""
echo "🧹 Passo 3: Limpando caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# 4. Verificar resultado
echo ""
echo "✅ Verificando resultado..."
echo ""

if [ -f "storage/app/public/paroquias.txt" ]; then
    LINHAS=$(wc -l < storage/app/public/paroquias.txt)
    echo "✓ Arquivo paroquias.txt encontrado"
    echo "✓ Total de linhas: $LINHAS"
    echo ""
    echo "Primeiras 3 paróquias:"
    head -n 3 storage/app/public/paroquias.txt
else
    echo "❌ ERRO: Arquivo paroquias.txt não encontrado!"
    echo ""
    echo "Tentando localizar o arquivo..."
    find storage -name "paroquias.txt" -type f
fi

echo ""
echo "=========================================="
echo "✅ CORREÇÃO CONCLUÍDA!"
echo "=========================================="
echo ""
echo "Agora acesse o sistema e teste a criação de uma nova paróquia."
echo "Você deve ver uma mensagem DEBUG com o total de paróquias carregadas."
