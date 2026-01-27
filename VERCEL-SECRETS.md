# Configuração de Secrets no Vercel

Este projeto usa Secrets do Vercel para proteger informações sensíveis.

## Secrets Necessários

Você precisa criar 2 secrets no Vercel:

1. **app-key** - A chave de criptografia do Laravel
2. **db-password** - A senha do banco de dados

## Como Criar os Secrets

### Opção 1: Via CLI do Vercel (Recomendado)

1. Instale o Vercel CLI (se ainda não tiver):
```bash
npm i -g vercel
```

2. Faça login:
```bash
vercel login
```

3. Navegue até a pasta do projeto:
```bash
cd lojinha-segueme
```

4. Adicione os secrets:
```bash
vercel secrets add app-key "base64:pxFWIbelFziKZ5nFvSd4rl9qzEgkUmq8uwdYi+0RubM="
vercel secrets add db-password "QwzboE1502@"
```

### Opção 2: Via Dashboard do Vercel

1. Acesse https://vercel.com/dashboard
2. Selecione seu projeto **lojinha-segue-me**
3. Vá em **Settings** → **Environment Variables**
4. Clique em **Add New**
5. Para cada secret:
   - **Nome**: APP_KEY
   - **Valor**: `base64:pxFWIbelFziKZ5nFvSd4rl9qzEgkUmq8uwdYi+0RubM=`
   - Selecione os ambientes: **Production**, **Preview**, **Development**
   - Clique em **Save**
6. Repita para DB_PASSWORD:
   - **Nome**: DB_PASSWORD
   - **Valor**: `QwzboE1502@`
   - Selecione os ambientes: **Production**, **Preview**, **Development**
   - Clique em **Save**

## Verificar se está funcionando

Após adicionar os secrets:

1. O Vercel fará redeploy automático
2. Aguarde o deploy completar
3. Acesse: https://lojinha-segue-me.vercel.app
4. A aplicação deve funcionar normalmente

## Nota de Segurança

✅ As informações sensíveis agora estão protegidas como secrets
✅ O arquivo vercel.json pode ser commitado no GitHub com segurança
✅ Os secrets só são acessíveis pelo Vercel durante o deploy
