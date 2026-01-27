<?php

namespace App\Http\Controllers;

use App\Models\Paroquia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class ParoquiaController extends BaseController
{
    /**
     * Carrega paróquias e cidades do arquivo paroquias.txt
     * Retorna também um mapa: [nome => cidade]
     */
    private function carregarParoquiasDoArquivo(): array
    {
        $paroquiasTxt = collect();

        if (Storage::exists('paroquias.txt')) {
            $conteudo = Storage::get('paroquias.txt');

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
        }

        // mapa: "PARÓQUIA X" => "CIDADE Y"
        $mapaParoquiaCidade = $paroquiasTxt
            ->mapWithKeys(fn($p) => [$p['nome'] => $p['cidade']])
            ->toArray();

        return [
            'paroquiasTxt' => $paroquiasTxt,
            'mapaParoquiaCidade' => $mapaParoquiaCidade,
        ];
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
        $dados = $this->carregarParoquiasDoArquivo();

        return view('paroquias.create', [
            'paroquiasTxt' => $dados['paroquiasTxt'],
            'mapaParoquiaCidade' => $dados['mapaParoquiaCidade'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            // cidade vem do TXT; não confie no front
            'cidade' => ['nullable', 'string', 'max:150'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'contato' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:ativa,inativa'],
        ]);

        // Processa o nome antes de salvar
        if (str_contains($data['nome'], '||')) {
            [$nome, $cidade] = array_pad(explode('||', $data['nome'], 2), 2, null);
            $data['nome'] = trim((string) $nome);
            $data['cidade'] = trim((string) $cidade);
        }

        // Força cidade correta pelo TXT
        $cidade = $this->cidadeDaParoquiaNoArquivo($data['nome']);
        if ($cidade) {
            $data['cidade'] = $cidade;
        }

        Paroquia::create($data);

        return redirect()
            ->route('paroquias.index')
            ->with('success', 'Paróquia cadastrada com sucesso!');
    }

    public function edit(Paroquia $paroquia)
    {
        $dados = $this->carregarParoquiasDoArquivo();

        return view('paroquias.edit', [
            'paroquia' => $paroquia,
            'paroquiasTxt' => $dados['paroquiasTxt'],
            'mapaParoquiaCidade' => $dados['mapaParoquiaCidade'],
        ]);
    }

    public function update(Request $request, Paroquia $paroquia)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:150'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'contato' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:ativa,inativa'],
        ]);

        // Processa o nome antes de salvar
        if (str_contains($data['nome'], '||')) {
            [$nome, $cidade] = array_pad(explode('||', $data['nome'], 2), 2, null);
            $data['nome'] = trim((string) $nome);
            $data['cidade'] = trim((string) $cidade);
        }

        // Força cidade correta pelo TXT
        $cidade = $this->cidadeDaParoquiaNoArquivo($data['nome']);
        if ($cidade) {
            $data['cidade'] = $cidade;
        }

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
