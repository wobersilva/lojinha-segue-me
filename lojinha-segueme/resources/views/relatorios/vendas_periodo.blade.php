<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Vendidos por Período</title>
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
        <span class="label">Produtos Vendidos por Período</span>
        <span class="datetime">{{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }}</span>
    </div>

    <div class="header">
        <h1>Lojinha do Segue-me</h1>
        <h2>Produtos Vendidos por Período</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th class="text-center" style="width: 120px;">Quantidade</th>
                <th class="text-right" style="width: 150px;">Total Arrecadado</th>
            </tr>
        </thead>
        <tbody>
            @php($soma = 0)
            @foreach($dados as $item)
                @php($soma += $item->total)
                <tr>
                    <td>{{ $item->descricao }}</td>
                    <td class="text-center">{{ $item->quantidade }}</td>
                    <td class="text-right">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">TOTAL GERAL:</td>
                <td class="text-right">R$ {{ number_format($soma, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
