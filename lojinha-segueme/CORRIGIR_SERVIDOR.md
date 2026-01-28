# 🚀 GUIA DE CORREÇÃO RÁPIDA - SERVIDOR WEB

## ⚡ ESCOLHA A OPÇÃO QUE VOCÊ TEM ACESSO:

---

## 📌 OPÇÃO 1: Via SSH (Mais Rápido)

### Passo 1: Conecte via SSH
```bash
ssh usuario@seu-servidor.com
```

### Passo 2: Navegue até o projeto
```bash
cd /var/www/html/lojinha-segueme
# OU
cd ~/public_html/lojinha-segueme
# OU
cd /home/seu-usuario/lojinha-segueme
```

### Passo 3: Execute os comandos
```bash
# Sincronizar paróquias
php artisan paroquias:sync

# Limpar caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Verificar
php -r "echo 'Linhas: ' . count(file('storage/app/public/paroquias.txt'));"
```

**✅ PRONTO! Acesse o sistema e teste.**

---

## 📌 OPÇÃO 2: Usando Script Shell

### Passo 1: Faça upload do arquivo
Envie o arquivo `fix_paroquias.sh` para o servidor

### Passo 2: Execute
```bash
chmod +x fix_paroquias.sh
./fix_paroquias.sh
```

---

## 📌 OPÇÃO 3: Via Navegador (Se não tem SSH)

### Passo 1: Faça upload do arquivo
Envie o arquivo `fix_paroquias_web.php` para a **raiz do projeto** no servidor

### Passo 2: Acesse pelo navegador
```
https://seu-dominio.com.br/fix_paroquias_web.php
```

### Passo 3: Após executar, DELETE o arquivo por segurança
```bash
rm fix_paroquias_web.php
```

---

## 📌 OPÇÃO 4: Via Painel de Hospedagem (cPanel, Plesk, etc)

### Se seu painel tem "Terminal" ou "SSH Terminal":

1. Abra o Terminal no painel
2. Navegue até a pasta do projeto:
   ```bash
   cd lojinha-segueme
   ```
3. Execute:
   ```bash
   php artisan paroquias:sync
   php artisan cache:clear
   php artisan view:clear
   ```

### Se seu painel tem "File Manager":

1. Localize o arquivo: `storage/app/private/paroquias.txt`
2. Copie para: `storage/app/public/paroquias.txt`
3. Verifique se o arquivo tem 114 linhas
4. No terminal PHP ou via script, execute:
   ```php
   <?php
   require 'vendor/autoload.php';
   $app = require_once 'bootstrap/app.php';
   $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
   Illuminate\Support\Facades\Cache::forget('paroquias_txt_data');
   Illuminate\Support\Facades\Artisan::call('cache:clear');
   echo "Cache limpo!";
   ```

---

## 🔍 COMO VERIFICAR SE FUNCIONOU:

1. Acesse: **https://seu-dominio.com.br/paroquias/create**
2. Você deve ver uma caixa amarela com:
   ```
   DEBUG: Total de paróquias carregadas: 114
   ```
3. O select deve mostrar as 114 paróquias

---

## 📞 AINDA NÃO FUNCIONOU?

Se nenhuma opção funcionou, me envie:

1. Tipo de hospedagem (cPanel, AWS, VPS, etc)
2. Se você tem acesso SSH
3. Print do erro ou da página
4. Resultado de: `ls -la storage/app/public/`

---

## 🎯 RESUMO DO QUE FAZER AGORA:

1. ✅ Escolha uma das 4 opções acima
2. ✅ Execute os comandos no servidor
3. ✅ Limpe o cache
4. ✅ Acesse a página e teste

**A opção mais rápida é a 1 (SSH) se você tiver acesso!**
