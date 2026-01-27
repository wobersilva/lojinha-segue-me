# 🔑 Configuração de Variáveis de Ambiente no Vercel

## ⚠️ CRÍTICO: Configure ANTES de fazer o próximo deploy!

Todas as variáveis de ambiente devem ser configuradas no Dashboard do Vercel.

## Como Configurar

1. Acesse: **https://vercel.com/dashboard**
2. Selecione o projeto **lojinha-segue-me**
3. Vá em **Settings** (menu superior)
4. No menu lateral, clique em **Environment Variables**
5. Para cada variável abaixo, clique em **Add New**

## ✅ Variáveis que você DEVE adicionar:

### Aplicação Laravel

| Key | Value | Environments |
|-----|-------|--------------|
| `APP_NAME` | `Lojinha-Segue-me` | ✅ Production ✅ Preview ✅ Development |
| `APP_ENV` | `production` | ✅ Production ✅ Preview ✅ Development |
| `APP_KEY` | `base64:pxFWIbelFziKZ5nFvSd4rl9qzEgkUmq8uwdYi+0RubM=` | ✅ Production ✅ Preview ✅ Development |
| `APP_DEBUG` | `false` | ✅ Production ✅ Preview ✅ Development |
| `APP_URL` | `https://lojinha-segue-me.vercel.app` | ✅ Production ✅ Preview ✅ Development |

### Localização

| Key | Value | Environments |
|-----|-------|--------------|
| `APP_LOCALE` | `br` | ✅ Production ✅ Preview ✅ Development |
| `APP_FALLBACK_LOCALE` | `br` | ✅ Production ✅ Preview ✅ Development |
| `APP_FAKER_LOCALE` | `pt_BR` | ✅ Production ✅ Preview ✅ Development |

### Banco de Dados (AWS RDS)

| Key | Value | Environments |
|-----|-------|--------------|
| `DB_CONNECTION` | `mysql` | ✅ Production ✅ Preview ✅ Development |
| `DB_HOST` | `lojinhasegueme.c7miqcugyquv.sa-east-1.rds.amazonaws.com` | ✅ Production ✅ Preview ✅ Development |
| `DB_PORT` | `3306` | ✅ Production ✅ Preview ✅ Development |
| `DB_DATABASE` | `lojinha_segueme` | ✅ Production ✅ Preview ✅ Development |
| `DB_USERNAME` | `root` | ✅ Production ✅ Preview ✅ Development |
| `DB_PASSWORD` | `QwzboE1502!` | ✅ Production ✅ Preview ✅ Development |

### Sessão e Cache

| Key | Value | Environments |
|-----|-------|--------------|
| `SESSION_DRIVER` | `database` | ✅ Production ✅ Preview ✅ Development |
| `SESSION_LIFETIME` | `120` | ✅ Production ✅ Preview ✅ Development |
| `SESSION_ENCRYPT` | `false` | ✅ Production ✅ Preview ✅ Development |
| `CACHE_STORE` | `database` | ✅ Production ✅ Preview ✅ Development |
| `FILESYSTEM_DISK` | `local` | ✅ Production ✅ Preview ✅ Development |
| `QUEUE_CONNECTION` | `database` | ✅ Production ✅ Preview ✅ Development |
| `BROADCAST_CONNECTION` | `log` | ✅ Production ✅ Preview ✅ Development |

### Logs

| Key | Value | Environments |
|-----|-------|--------------|
| `LOG_CHANNEL` | `stack` | ✅ Production ✅ Preview ✅ Development |
| `LOG_STACK` | `single` | ✅ Production ✅ Preview ✅ Development |
| `LOG_LEVEL` | `error` | ✅ Production ✅ Preview ✅ Development |

## Depois de Configurar

1. Faça commit das mudanças:
```bash
git add .
git commit -m "Remove env vars from vercel.json - configure in dashboard"
git push
```

2. O Vercel vai fazer redeploy automático
3. Aguarde 2-5 minutos
4. Acesse: https://lojinha-segue-me.vercel.app

## ✅ Checklist

- [ ] Todas as 24 variáveis configuradas no Vercel
- [ ] Todos os ambientes selecionados (Production, Preview, Development)
- [ ] Senha do banco correta: `QwzboE1502!` (com exclamação)
- [ ] APP_KEY completa com o prefixo `base64:`
- [ ] Commit e push feitos
- [ ] Deploy completado

## 🐛 Se ainda der erro

Verifique os logs de build e runtime no Vercel Dashboard:
1. Deployments → Último deployment → View Function Logs
2. Procure por mensagens de erro específicas
3. Me mostre a mensagem de erro completa
