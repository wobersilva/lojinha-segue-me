<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Saída - {{ $encontro->nome }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            background: white;
            color: #1e293b;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
            text-transform: uppercase;
            color: #1e40af;
        }

        .header h2 {
            font-size: 15px;
            font-weight: normal;
            color: #64748b;
        }

        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .info-item {
            flex: 1;
            min-width: 180px;
        }

        .info-item.wide {
            flex: 2;
            min-width: 300px;
        }

        .info-item label {
            font-weight: bold;
            color: #1e40af;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item span {
            display: block;
            margin-top: 4px;
            padding: 8px 10px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            color: #334155;
        }

        .info-item .contact-info {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .info-item .contact-info span {
            flex: 1;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #94a3b8;
            padding: 10px 8px;
            text-align: left;
        }

        th {
            background: #1e40af;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        td {
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .col-quantidade {
            width: 100px;
        }

        .col-devolvida {
            width: 120px;
            background: #fef9c3 !important;
        }

        thead th.col-devolvida {
            background: #ca8a04 !important;
        }

        .col-preco {
            width: 100px;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tfoot tr {
            background: #e2e8f0 !important;
        }

        tfoot td {
            border-top: 2px solid #1e40af;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
            padding-top: 50px;
            border-top: 1px solid #64748b;
        }

        .signature-box p {
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }

        .totals {
            margin-top: 20px;
            text-align: right;
            font-size: 13px;
            color: #334155;
        }

        .totals p {
            margin-bottom: 5px;
        }

        .totals strong {
            font-size: 15px;
            color: #1e40af;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .print-button:hover {
            background: #1d4ed8;
        }

        .back-button {
            position: fixed;
            top: 20px;
            right: 140px;
            padding: 12px 24px;
            background: #64748b;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
            text-decoration: none;
        }

        .back-button:hover {
            background: #475569;
        }

        .data-emissao {
            background: #1e40af;
            color: white;
            padding: 10px 15px;
            margin: -20px -20px 20px -20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .data-emissao .label {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-emissao .datetime {
            font-size: 14px;
            font-weight: bold;
        }

        @media print {
            .print-button, .back-button {
                display: none !important;
            }

            body {
                padding: 0;
            }

            .data-emissao {
                margin: 0 0 15px 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .header {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('encontros.show', $encontro) }}" class="back-button">Voltar</a>
    <button onclick="window.print()" class="print-button">Imprimir</button>

    <div class="data-emissao">
        <span class="label">Relatório de Saída de Produtos</span>
        <span class="datetime">{{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}</span>
    </div>

    <div class="header">
        <h1>Lojinha do Segue-me</h1>
        <h2>Relatório de Saída de Produtos</h2>
    </div>

    <div class="info-section">
        <div class="info-item">
            <label>Encontro:</label>
            <span>{{ $encontro->nome }}</span>
        </div>
        <div class="info-item wide">
            <label>Paróquia:</label>
            <span>{{ $encontro->paroquia->nome ?? 'N/A' }} - {{ $encontro->paroquia->cidade ?? '' }}</span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-item">
            <label>Contato / Responsável:</label>
            <span>{{ $encontro->paroquia->responsavel ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <label>Telefone:</label>
            <span>{{ $encontro->paroquia->contato ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <label>Data Início:</label>
            <span>{{ \Carbon\Carbon::parse($encontro->data_inicio)->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <label>Data Fim:</label>
            <span>{{ $encontro->data_fim ? \Carbon\Carbon::parse($encontro->data_fim)->format('d/m/Y') : 'N/A' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">#</th>
                <th>Produto</th>
                <th class="col-preco text-right">Preço Custo</th>
                <th class="col-quantidade text-center">Quantidade</th>
                <th class="col-devolvida text-center">Qtd. Devolvida</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantidade = 0;
                $totalValor = 0;
            @endphp
            @foreach($encontro->saidasProvisorias as $index => $saida)
                @php
                    $precoCusto = $saida->produto->preco_custo ?? 0;
                    $totalQuantidade += $saida->quantidade;
                    $totalValor += ($precoCusto * $saida->quantidade);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $saida->produto->descricao }} - {{ $saida->produto->tamanho }}</td>
                    <td class="text-right">R$ {{ number_format($precoCusto, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $saida->quantidade }}</td>
                    <td class="col-devolvida"></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-center"><strong>{{ $totalQuantidade }}</strong></td>
                <td class="col-devolvida"></td>
            </tr>
        </tfoot>
    </table>

    <div class="totals">
        <p>Total de Itens: <strong>{{ $encontro->saidasProvisorias->count() }}</strong> produto(s)</p>
        <p>Quantidade Total: <strong>{{ $totalQuantidade }}</strong> unidade(s)</p>
        <p>Valor Total (Custo): <strong>R$ {{ number_format($totalValor, 2, ',', '.') }}</strong></p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Responsável pela Entrega</p>
            <p style="margin-top: 5px; font-weight: bold; color: #1e40af;">{{ auth()->user()->name ?? 'N/A' }}</p>
        </div>
        <div class="signature-box">
            <p>Responsável pelo Recebimento</p>
        </div>
    </div>
</body>
</html>
