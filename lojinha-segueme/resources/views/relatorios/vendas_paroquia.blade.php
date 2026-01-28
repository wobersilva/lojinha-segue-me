<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas - {{ $paroquia->nome }}</title>
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

        .header h3 {
            font-size: 13px;
            font-weight: bold;
            color: #334155;
            margin-top: 8px;
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
            font-weight: bold;
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
    <button onclick="history.back()" class="back-button">Voltar</button>
    <button onclick="window.print()" class="print-button">Imprimir</button>

    <div class="data-emissao">
        <span class="label">Relatório de Vendas por Paróquia</span>
        <span class="datetime">{{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}</span>
    </div>

    <div class="header">
        <h1>Lojinha do Segue-me</h1>
        <h2>Relatório de Vendas por Paróquia</h2>
        <h3>Paróquia: {{ $paroquia->nome }}@if($paroquia->cidade) - {{ $paroquia->cidade }}@endif</h3>
        @if($dataInicio || $dataFim)
            <p style="font-size: 12px; color: #64748b; margin-top: 5px;">
                Período:
                @if($dataInicio)
                    {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }}
                @else
                    (início)
                @endif
                até
                @if($dataFim)
                    {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}
                @else
                    (fim)
                @endif
            </p>
        @endif
    </div>

    @if($dados->isEmpty())
        <div style="padding: 40px; text-align: center; background: #f8fafc; border-radius: 8px; margin-top: 20px;">
            <p style="font-size: 16px; color: #64748b; margin-bottom: 10px;">📊</p>
            <p style="font-size: 14px; color: #64748b;">
                Nenhuma venda encontrada para esta paróquia.
            </p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">#</th>
                    <th>Encontro</th>
                    <th style="width: 100px;" class="text-center">Data</th>
                    <th>Produto</th>
                    <th class="text-center" style="width: 100px;">Quantidade</th>
                    <th class="text-right" style="width: 130px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php($soma = 0)
                @php($totalQuantidade = 0)
                @foreach($dados as $index => $item)
                    @php($soma += $item->total)
                    @php($totalQuantidade += $item->quantidade)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->encontro }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->data_encontro)->format('d/m/Y') }}</td>
                        <td>{{ $item->descricao }}</td>
                        <td class="text-center">{{ $item->quantidade }}</td>
                        <td class="text-right">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">TOTAL DE PRODUTOS:</td>
                    <td class="text-center">{{ $totalQuantidade }}</td>
                    <td class="text-right">R$ {{ number_format($soma, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="totals">
            <p>Total de Registros: <strong>{{ $dados->count() }}</strong> item(ns)</p>
            <p>Quantidade Total Vendida: <strong>{{ $totalQuantidade }}</strong> unidade(s)</p>
            <p>Valor Total Arrecadado: <strong>R$ {{ number_format($soma, 2, ',', '.') }}</strong></p>
        </div>
    @endif
</body>
</html>
