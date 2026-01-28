# 📋 Guia de Deploy - Lojinha Segue-me

## 🚀 Configuração Inicial em Produção

### 1. Após o deploy, execute os seguintes comandos:

```bash
# 1. Criar link simbólico do storage
php artisan storage:link

# 2. Sincronizar arquivo de paróquias
php artisan paroquias:sync

# 3. Limpar e otimizar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Otimizar para produção (opcional)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📁 Arquivo de Paróquias

### Localização do arquivo fonte:
- `storage/app/private/paroquias.txt`

### Formato do arquivo:
```
NOME DA PARÓQUIA | CIDADE-UF;
```

**Exemplo:**
```
PARÓQUIA NOSSA SENHORA DE FÁTIMA | NATAL-RN;
PARÓQUIA SÃO JOÃO BATISTA | PARNAMIRIM-RN;
```

### Como atualizar as paróquias:

1. Edite o arquivo `storage/app/private/paroquias.txt`
2. Execute o comando de sincronização:
```bash
php artisan paroquias:sync
```

Este comando irá:
- ✅ Copiar o arquivo para `storage/app/public/paroquias.txt`
- ✅ Limpar o cache
- ✅ Validar o conteúdo

## 🔧 Problemas Comuns

### "Paróquias não aparecem no formulário"

**Solução:**
```bash
# 1. Verificar se o link simbólico existe
php artisan storage:link

# 2. Sincronizar arquivo
php artisan paroquias:sync

# 3. Limpar cache
php artisan cache:clear
```

### "Erro de permissão ao acessar arquivos"

**Solução (em servidor Linux):**
```bash
# Ajustar permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📝 Checklist de Deploy

- [ ] Executar `php artisan storage:link`
- [ ] Executar `php artisan paroquias:sync`
- [ ] Executar `php artisan migrate --force`
- [ ] Limpar todos os caches
- [ ] Verificar arquivo `.env` está configurado corretamente
- [ ] Testar criação de paróquia no formulário
- [ ] Testar criação de encontro

## 🌐 Variáveis de Ambiente Importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com.br

DB_CONNECTION=mysql
DB_HOST=seu-host-aws
DB_PORT=3306
DB_DATABASE=lojinha_segueme
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha

CACHE_DRIVER=file
SESSION_DRIVER=file
```

## 📞 Suporte

Em caso de problemas, verifique os logs em:
- `storage/logs/laravel.log`
