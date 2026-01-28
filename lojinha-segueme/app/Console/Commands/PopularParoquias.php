<?php

namespace App\Console\Commands;

use App\Models\Paroquia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopularParoquias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paroquias:popular {--force : Força a re-popular mesmo se já existirem dados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popula a tabela de paróquias a partir do arquivo TXT';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Populando tabela de paróquias...');
        $this->newLine();

        // Verifica se já existem paróquias
        $count = Paroquia::count();
        
        if ($count > 0 && !$this->option('force')) {
            $this->warn("⚠️  Já existem {$count} paróquias cadastradas.");
            
            if (!$this->confirm('Deseja limpar e re-popular?', false)) {
                $this->info('Operação cancelada.');
                return Command::SUCCESS;
            }
            
            // Limpa a tabela
            $this->info('🗑️  Limpando tabela...');
            DB::table('paroquias')->truncate();
        }

        // Define as paróquias
        $paroquiasData = $this->getParoquiasData();
        
        $this->info("📋 Total de paróquias a importar: " . count($paroquiasData));
        $this->newLine();

        // Barra de progresso
        $bar = $this->output->createProgressBar(count($paroquiasData));
        $bar->start();

        $inserted = 0;
        $errors = 0;

        foreach ($paroquiasData as $data) {
            try {
                Paroquia::create([
                    'nome' => $data['nome'],
                    'cidade' => $data['cidade'],
                    'status' => 'ativa',
                    'responsavel' => null,
                    'contato' => null,
                ]);
                $inserted++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Erro ao inserir: {$data['nome']} | {$data['cidade']}");
                $this->error($e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Resultado
        $this->info("✅ Importação concluída!");
        $this->table(
            ['Resultado', 'Quantidade'],
            [
                ['Inseridas', $inserted],
                ['Erros', $errors],
                ['Total', count($paroquiasData)],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Retorna os dados das paróquias
     */
    private function getParoquiasData(): array
    {
        return [
            ['nome' => 'ÁREA PASTORAL DE NOSSA SENHORA DOS IMPOSSÍVEIS', 'cidade' => 'NATAL-RN'],
            ['nome' => 'ÁREA PASTORAL DE SANTO EXPEDITO', 'cidade' => 'SÃO GONÇALO DO AMARANTE-RN'],
            ['nome' => 'PARÓQUIA BOM JESUS DAS DORES', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA BOM JESUS DOS NAVEGANTES', 'cidade' => 'TOUROS-RN'],
            ['nome' => 'PARÓQUIA CRISTO REI', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA DIVINO ESPÍRITO SANTO', 'cidade' => 'VERA CRUZ-RN'],
            ['nome' => 'PARÓQUIA IMACULADA CONCEIÇÃO', 'cidade' => 'LAGOA SALGADA-RN'],
            ['nome' => 'PARÓQUIA IMACULADA CONCEIÇÃO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA IMACULADA CONCEIÇÃO', 'cidade' => 'NOVA CRUZ-RN'],
            ['nome' => 'PARÓQUIA JESUS BOM PASTOR', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA APARECIDA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA AUXILIADORA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA APRESENTAÇÃO (ANTIGA CATEDRAL)', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA APRESENTAÇÃO (CATEDRAL METROPOLITANA)', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA ASSUNÇÃO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CANDELÁRIA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'CAIÇARA DO RIO DO VENTO / LAJES / PEDRA PRETA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'CANGUARETAMA / VILA FLOR-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'CEARÁ-MIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'GUAMARÉ-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'MACAÍBA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'MACAU-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'MAXARANGUAPE-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'SANTA MARIA / IELMO MARINHO-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'SANTO ANTÔNIO-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'SERRA CAIADA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'SÃO RAFAEL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO', 'cidade' => 'SÃO TOMÉ-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA CONCEIÇÃO (NOVA PARNAMIRIM)', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DAS DORES', 'cidade' => 'BREJINHO / PASSAGEM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DAS DORES', 'cidade' => 'CEARÁ-MIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DAS GRAÇAS', 'cidade' => 'AFONSO BEZERRA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DAS GRAÇAS E SANTA TEREZINHA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE FÁTIMA', 'cidade' => 'PASSA E FICA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE FÁTIMA', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE FÁTIMA (PAJUÇARA)', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE FÁTIMA (VILAR)', 'cidade' => 'MACAÍBA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE LOURDES', 'cidade' => 'CAMPO REDONDO / LAJES PINTADAS-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE LOURDES', 'cidade' => 'IPANGUAÇU-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE LOURDES', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DE NAZARÉ', 'cidade' => 'PARAZINHO / PEDRA GRANDE-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO AMPARO', 'cidade' => 'CORONEL EZEQUIEL / JAÇANÃ-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO CARMO', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO LIVRAMENTO', 'cidade' => 'POÇO BRANCO / TAIPU-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO Ó', 'cidade' => 'NÍSIA FLORESTA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO PERPÉTUO SOCORRO', 'cidade' => 'BARCELONA / RUY BARBOSA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO PERPÉTUO SOCORRO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA PENHA', 'cidade' => 'MONTE ALEGRE-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA PIEDADE', 'cidade' => 'ESPÍRITO SANTO-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA PUREZA', 'cidade' => 'PUREZA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DA SAÚDE', 'cidade' => 'BOA SAÚDE-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DOS NAVEGANTES', 'cidade' => 'NATAL (REDINHA)-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DOS NAVEGANTES', 'cidade' => 'RIO DO FOGO-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DOS PRAZERES', 'cidade' => 'GOIANINHA-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO ROSÁRIO', 'cidade' => 'ALTO DO RODRIGUES-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA DO ROSÁRIO DE FÁTIMA', 'cidade' => 'CEARÁ-MIRIM-RN'],
            ['nome' => 'PARÓQUIA NOSSA SENHORA MÃE DOS HOMENS', 'cidade' => 'JARDIM DE ANGICOS / JOÃO CÂMARA / BENTO FERNANDES-RN'],
            ['nome' => 'PARÓQUIA SAGRADA FAMÍLIA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SAGRADO CORAÇÃO DE JESUS', 'cidade' => 'BOM JESUS / SENADOR ELÓI DE SOUZA-RN'],
            ['nome' => 'PARÓQUIA SAGRADO CORAÇÃO DE JESUS', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SAGRADO CORAÇÃO DE JESUS', 'cidade' => 'RIACHUELO-RN'],
            ['nome' => 'PARÓQUIA SANTA CLARA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTA LUZIA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTA RITA DE CÁSSIA', 'cidade' => 'SANTA CRUZ-RN'],
            ['nome' => 'PARÓQUIA SANTA RITA DE CÁSSIA DOS IMPOSSÍVEIS', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTA TERESINHA', 'cidade' => 'LAGOA D\'ANTA-RN'],
            ['nome' => 'PARÓQUIA SANTA TERESINHA', 'cidade' => 'SÍTIO NOVO / TANGARÁ-RN'],
            ['nome' => 'PARÓQUIA SANT\'ANA', 'cidade' => 'SANTANA DO MATOS-RN'],
            ['nome' => 'PARÓQUIA SANT\'ANA', 'cidade' => 'NATAL (CAPIM MACIO)-RN'],
            ['nome' => 'PARÓQUIA SANT\'ANA', 'cidade' => 'NATAL (SOLEDADE II)-RN'],
            ['nome' => 'PARÓQUIA SANT\'ANA E SÃO JOAQUIM', 'cidade' => 'SÃO JOSÉ DO MIPIBU-RN'],
            ['nome' => 'PARÓQUIA SANTUÁRIO DE NOSSA SENHORA DA ESPERANÇA E SANTO INÁCIO DE LOYOLA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTUÁRIO DOS SANTOS MÁRTIRES DE CUNHAÚ E URUAÇU', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO BENTO ABADE', 'cidade' => 'MONTE DAS GAMELEIRAS / SERRA DE SÃO BENTO-RN'],
            ['nome' => 'PARÓQUIA SÃO CAMILO DE LÉLLIS', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO FRANCISCO DE ASSIS', 'cidade' => 'LAGOA DE PEDRAS-RN'],
            ['nome' => 'PARÓQUIA SÃO FRANCISCO DE ASSIS', 'cidade' => 'NATAL (CIDADE SATÉLITE)-RN'],
            ['nome' => 'PARÓQUIA SÃO FRANCISCO DE ASSIS', 'cidade' => 'PEDRO VELHO-RN'],
            ['nome' => 'PARÓQUIA SÃO FRANCISCO DE ASSIS E SÃO JOÃO LOSTAU NAVARRO', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA SÃO GONÇALO DO AMARANTE', 'cidade' => 'SÃO GONÇALO DO AMARANTE-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'AREZ / SENADOR GEORGINO AVELINO-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'MONTANHAS-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'NATAL (PONTA NEGRA)-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'PENDÊNCIAS-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BATISTA', 'cidade' => 'EXTREMOZ (PRAIA DE PITANGUI)-RN'],
            ['nome' => 'PARÓQUIA SÃO JOÃO BOSCO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO JOSÉ', 'cidade' => 'ANGICOS / FERNANDO PEDROZA-RN'],
            ['nome' => 'PARÓQUIA SÃO JOSÉ', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO JOSÉ', 'cidade' => 'SÃO JOSÉ DO CAMPESTRE-RN'],
            ['nome' => 'PARÓQUIA SÃO JOSÉ DE ANCHIETA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO JOSÉ OPERÁRIO', 'cidade' => 'GALINHOS / JANDAÍRA-RN'],
            ['nome' => 'PARÓQUIA SÃO LUCAS', 'cidade' => 'SÃO GONÇALO DO AMARANTE-RN'],
            ['nome' => 'PARÓQUIA SÃO MATEUS MOREIRA', 'cidade' => 'PARNAMIRIM-RN'],
            ['nome' => 'PARÓQUIA SÃO MIGUEL', 'cidade' => 'EXTREMOZ-RN'],
            ['nome' => 'PARÓQUIA SÃO MIGUEL ARCANJO', 'cidade' => 'SÃO MIGUEL DO GOSTOSO-RN'],
            ['nome' => 'PARÓQUIA SÃO PAULO APÓSTOLO', 'cidade' => 'LAGOA DE VELHOS / SÃO PAULO DO POTENGI-RN'],
            ['nome' => 'PARÓQUIA SÃO PAULO APÓSTOLO', 'cidade' => 'PEDRO AVELINO-RN'],
            ['nome' => 'PARÓQUIA SÃO PEDRO APÓSTOLO', 'cidade' => 'JUNDIÁ / VÁRZEA-RN'],
            ['nome' => 'PARÓQUIA SÃO PEDRO APÓSTOLO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO PEDRO APÓSTOLO', 'cidade' => 'SÃO PEDRO-RN'],
            ['nome' => 'PARÓQUIA SÃO PEDRO PESCADOR', 'cidade' => 'BAÍA FORMOSA-RN'],
            ['nome' => 'PARÓQUIA SÃO SEBASTIÃO', 'cidade' => 'JAPI / SÃO BENTO DO TRAIRI-RN'],
            ['nome' => 'PARÓQUIA SÃO SEBASTIÃO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO TIAGO MENOR', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SÃO VICENTE FERRER', 'cidade' => 'ITAJÁ-RN'],
            ['nome' => 'PARÓQUIA SANTO AFONSO MARIA DE LIGÓRIO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTO AMBRÓSIO FRANCISCO FERRO', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTO ANTÔNIO', 'cidade' => 'SÃO GONÇALO DO AMARANTE-RN'],
            ['nome' => 'PARÓQUIA SANTO ANTÔNIO', 'cidade' => 'SERRINHA-RN'],
            ['nome' => 'PARÓQUIA SANTO ANTÔNIO DE LISBOA', 'cidade' => 'TIBAU DO SUL-RN'],
            ['nome' => 'PARÓQUIA SANTO ANTÔNIO DE PÁDUA', 'cidade' => 'NATAL-RN'],
            ['nome' => 'PARÓQUIA SANTO ANTÃO ABADE', 'cidade' => 'CAIÇARA DO NORTE / SÃO BENTO DO NORTE-RN'],
            ['nome' => 'PARÓQUIA VIRGEM E MÁRTIR SANTA LUZIA', 'cidade' => 'SANTA LUZIA / TOUROS-RN'],
        ];
    }
}

