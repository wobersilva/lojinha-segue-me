# Como Adicionar a Logo

## 📁 Passos para adicionar sua logo:

### 1. Prepare suas imagens

Você precisa de **2 imagens**:

- **`logo.png`** - Logo completa (recomendado: 400x400px ou proporção quadrada)
  - Esta logo aparecerá:
    - Na tela de login/registro (centralizada, tamanho grande)
    - No sidebar quando expandido
    - No menu mobile
  
- **`logo-icon.png`** - Ícone/versão simplificada (recomendado: 100x100px)
  - Esta logo aparecerá quando o sidebar estiver colapsado
  - Geralmente é apenas o símbolo/ícone da logo, sem texto

### 2. Coloque as imagens nesta pasta

Salve os arquivos aqui:
```
public/images/logo.png
public/images/logo-icon.png
```

### 3. Locais onde a logo aparece

✅ **Tela de Login/Registro** - Logo grande centralizada (128x128px)
✅ **Sidebar Desktop** - Logo ao lado do nome (40x40px)
✅ **Sidebar Mobile** - Logo no menu mobile (40x40px)

### 4. Formatos recomendados

- **PNG** com fundo transparente (recomendado)
- **JPG** se preferir fundo sólido
- **SVG** para melhor qualidade (altere a extensão no código)

### 5. Tamanhos recomendados

- Logo completa: **400x400px** (para melhor qualidade na tela de login)
- Logo ícone: **100x100px** a **200x200px**

## 🎨 Dica de Design

Se você só tem uma logo completa:
1. Use a mesma imagem para ambos os arquivos
2. Ou crie uma versão simplificada usando ferramentas como:
   - Canva
   - Figma
   - Photoshop
   - GIMP (gratuito)

## 🔧 Se quiser usar apenas UMA imagem

Se você quer usar a mesma imagem para ambos os estados, edite o arquivo:
`resources/views/layouts/sidebar-premium.blade.php`

E altere para:

```blade
<div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-white dark:bg-gray-800">
    <img src="{{ asset('images/logo.png') }}" 
         alt="Logo Segue-me" 
         class="w-full h-full object-contain">
</div>
```

## 🛡️ Fallback automático

Se a imagem não for encontrada:
- A logo padrão (quadrado roxo com "S") será exibida automaticamente
- Não haverá erro na página

## 📝 Personalizando o fundo da logo

Se sua logo precisar de um fundo específico, altere a classe `bg-white dark:bg-gray-800` para:

- Fundo colorido: `bg-indigo-600` (ou qualquer cor do Tailwind)
- Fundo transparente: remova a classe `bg-white dark:bg-gray-800`
- Fundo com padding: adicione `p-1` ou `p-2`

---

✅ Depois de adicionar as imagens, recarregue a página para ver as mudanças!

