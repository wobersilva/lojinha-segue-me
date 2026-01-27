# Configuração de Secrets no Vercel

Este projeto usa Secrets do Vercel para proteger informações sensíveis.

## ⚠️ IMPORTANTE: Configure antes de fazer o deploy!

Você precisa configurar as variáveis de ambiente no Vercel **ANTES** de fazer o push para o GitHub.

## Secrets Necessários

Você precisa criar 2 variáveis de ambiente no Vercel:

1. **APP_KEY** - A chave de criptografia do Laravel
   - Valor: `base64:pxFWIbelFziKZ5nFvSd4rl9qzEgkUmq8uwdYi+0RubM=`

2. **DB_PASSWORD** - A senha do banco de dados ⚠️ ATUALIZADA!
   - Valor: `QwzboE1502!`

## Como Configurar (Via Dashboard do Vercel)

### Passo 1: Acesse o Dashboard

1. Abra: **https://vercel.com/dashboard**
2. Faça login na sua conta
3. Selecione o projeto **lojinha-segue-me**

### Passo 2: Adicione as Variáveis

1. Clique em **Settings** (no menu superior)
2. No menu lateral esquerdo, clique em **Environment Variables**

### Passo 3: Adicione APP_KEY

Clique em **Add New** ou **Edit**:

- **Key**: `APP_KEY`
- **Value**: `base64:pxFWIbelFziKZ5nFvSd4rl9qzEgkUmq8uwdYi+0RubM=`
- Selecione: ✅ **Production** ✅ **Preview** ✅ **Development**
- Clique em **Save**

### Passo 4: Adicione DB_PASSWORD

Clique em **Add New** novamente:

- **Key**: `DB_PASSWORD`
- **Value**: `QwzboE1502!`
- Selecione: ✅ **Production** ✅ **Preview** ✅ **Development**
- Clique em **Save**

## Arquivos Alterados

Os seguintes arquivos foram modificados para funcionar no Vercel:

1. ✅ `api/index.php` - Ponto de entrada do Laravel no Vercel (com tratamento de erros)
2. ✅ `vercel.json` - Configuração do Vercel com referências a secrets
3. ✅ `config/filesystems.php` - Corrigido erro de rtrim com null
4. ✅ `.vercelignore` - Define arquivos ignorados no deploy
5. ✅ `package.json` - Script vercel-build que instala composer e cria diretórios
6. ✅ `storage/framework/*/.gitkeep` - Garante que diretórios necessários existam

## Configuração do Build

O Vercel vai executar automaticamente o script `vercel-build` do `package.json`:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && vite build && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
```

Este script:
- Instala as dependências do Composer (Laravel e pacotes)
- Compila os assets do frontend com Vite
- Cria os diretórios necessários do Laravel

## Depois de Configurar

1. Faça commit das mudanças:
```bash
git add .
git commit -m "Configure Laravel for Vercel deployment"
git push
```

2. O Vercel fará redeploy automático
3. Aguarde o deploy completar (2-5 minutos)
4. Acesse: **https://lojinha-segue-me.vercel.app**

## Verificação

Se tudo estiver configurado corretamente:

✅ As variáveis de ambiente estarão protegidas como secrets  
✅ O arquivo vercel.json pode ser commitado com segurança  
✅ Os secrets só são acessíveis pelo Vercel durante o deploy  
✅ A aplicação Laravel vai funcionar normalmente

## Troubleshooting

Se ainda der erro após configurar os secrets:

1. Vá em **Deployments** no Vercel
2. Clique no último deployment
3. Veja os logs de erro
4. Verifique se todas as variáveis foram configuradas corretamente
