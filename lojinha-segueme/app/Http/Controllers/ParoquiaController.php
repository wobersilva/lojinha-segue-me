<?php

namespace App\Http\Controllers;

use App\Models\Paroquia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ParoquiaController extends BaseController
{
    /**
     * Tempo de cache para o arquivo paroquias.txt (1 hora)
     */
    private const CACHE_TTL = 3600;

    /**
     * Carrega paróquias e cidades do arquivo paroquias.txt
     * Retorna também um mapa: [nome => cidade]
     *
     * OTIMIZAÇÃO: Cache de 1 hora para evitar leitura repetida do arquivo
     */
    private function carregarParoquiasDoArquivo(): array
    {
        return Cache::remember('paroquias_txt_data', self::CACHE_TTL, function () {
            $paroquiasTxt = collect();

            // Tenta ler do disco 'public' (storage/app/public/paroquias.txt)
            $disk = Storage::disk('public');

            if ($disk->exists('paroquias.txt')) {
                $conteudo = $disk->get('paroquias.txt');

                // Verifica se o arquivo não está vazio
                if (!empty(trim($conteudo))) {
                    // Normaliza quebras de linha (Windows \r\n, Unix \n, Mac \r)
                    $conteudo = str_replace(["\r\n", "\r"], "\n", $conteudo);

                    $paroquiasTxt = collect(explode("\n", $conteudo))
                        ->map(fn($linha) => trim($linha))
                        ->filter()
                        ->map(function ($linha) {
                            // Remove ponto e vírgula no final se existir
                            $linha = rtrim($linha, ';');

                            // Aceita " | " ou "|" com espaços variáveis
                            if (strpos($linha, ' | ') !== false) {
                                $partes = explode(' | ', $linha, 2);
                            } else {
                                $partes = preg_split('/\s*\|\s*/', $linha, 2);
                            }

                            $nome = trim($partes[0] ?? '');
                            $cidade = trim($partes[1] ?? '');

                            return [
                                'nome' => $nome,
                                'cidade' => $cidade,
                            ];
                        })
                        ->filter(fn($p) => !empty($p['nome']) && !empty($p['cidade']))
                        ->values();
                } else {
                    \Log::warning('Arquivo paroquias.txt está vazio');
                }
            } else {
                \Log::warning('Arquivo paroquias.txt não encontrado em: ' . $disk->path('paroquias.txt'));
            }

            // mapa: "PARÓQUIA X" => "CIDADE Y"
            $mapaParoquiaCidade = $paroquiasTxt
                ->mapWithKeys(fn($p) => [$p['nome'] => $p['cidade']])
                ->toArray();

            return [
                'paroquiasTxt' => $paroquiasTxt,
                'mapaParoquiaCidade' => $mapaParoquiaCidade,
            ];
        });
    }

    /**
     * Retorna cidade do TXT para a paróquia informada (ou null)
     */
    private function cidadeDaParoquiaNoArquivo(string $nome): ?string
    {
        $dados = $this->carregarParoquiasDoArquivo();
        $mapa = $dados['mapaParoquiaCidade'] ?? [];

        return $mapa[$nome] ?? null;
    }

    public function index(Request $request)
    {
        $busca = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', 'todas');  // 'todas' | 'ativa' | 'inativa'

        $query = Paroquia::query();

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q
                    ->where('nome', 'like', "%{$busca}%")
                    ->orWhere('cidade', 'like', "%{$busca}%");
            });
        }

        if (in_array($status, ['ativa', 'inativa'], true)) {
            $query->where('status', $status);
        }

        $paroquias = $query
            ->orderBy('nome')
            ->paginate(50)
            ->appends($request->only(['q', 'status']));

        return view('paroquias.index', compact('paroquias', 'busca', 'status'));
    }

    public function create()
    {
        // Busca paróquias do BANCO DE DADOS (compatível com Vercel)
        $paroquiasBanco = Paroquia::where('status', 'ativa')
            ->orderBy('nome')
            ->get(['id', 'nome', 'cidade']);
        
        // Log para debug (pode ser removido depois)
        \Log::info('ParoquiaController@create - Total de paróquias carregadas do banco', [
            'total' => $paroquiasBanco->count(),
        ]);

        return view('paroquias.create', [
            'paroquiasBanco' => $paroquiasBanco,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:150'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'contato' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:ativa,inativa'],
        ]);

        Paroquia::create($data);

        return redirect()
            ->route('paroquias.index')
            ->with('success', 'Paróquia cadastrada com sucesso!');
    }

    public function edit(Paroquia $paroquia)
    {
        // Busca paróquias do BANCO DE DADOS (compatível com Vercel)
        $paroquiasBanco = Paroquia::where('status', 'ativa')
            ->orderBy('nome')
            ->get(['id', 'nome', 'cidade']);

        return view('paroquias.edit', [
            'paroquia' => $paroquia,
            'paroquiasBanco' => $paroquiasBanco,
        ]);
    }

    public function update(Request $request, Paroquia $paroquia)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:150'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'contato' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:ativa,inativa'],
        ]);

        $paroquia->update($data);

        return redirect()
            ->route('paroquias.index')
            ->with('success', 'Paróquia atualizada com sucesso!');
    }

    public function destroy(Paroquia $paroquia)
    {
        $paroquia->delete();

        return redirect()
            ->route('paroquias.index')
            ->with('success', 'Paróquia excluída com sucesso!');
    }
}
