<?php

namespace App\Services\Licitacao\Sicom;

use App\Repositories\Patrimonial\Licitacao\Sicom\SicomLicitacaoRepository;
use App\Support\String\StringHelper;
use Carbon\Carbon;
use Yasumi\Yasumi;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class SicomLicitacaoService
{
    /**
     * @var SicomLicitacaoRepository
     */
    private $sicomLicitacaoRepository;
    private $remessaSicomService;

    public function __construct()
    {
        $this->sicomLicitacaoRepository = new SicomLicitacaoRepository();
        $this->remessaSicomService = new RemessasicomService();
    }

    public function getProcessos($agrupamento)
    {
        // Obter os processos, convertendo os dados para UTF-8
        $processos = StringHelper::convertToUtf8($this->sicomLicitacaoRepository->getProcessos($agrupamento));

        // Obter o ano atual para usar no Yasumi
        $ano = Carbon::now()->year;
        $feriados = Yasumi::create('Brazil', $ano);

        // Obter os feriados como um array de objetos Carbon e converte para array de strings no formato 'Y-m-d'
        $feriados = array_map(function ($feriado) {
            return $feriado->format('Y-m-d');
        }, $feriados->getHolidays());

        // Adicionar campo 'prazo' em cada processo
        foreach ($processos as &$processo) {
            // Verificar se a 'datareferencia' é '-'
            if ($processo->datareferencia == '-') {
                $processo->prazo = '-';
            } else {
                // Converte a 'datareferencia' para um objeto Carbon
                $dataReferencia = Carbon::createFromFormat('d/m/Y', $processo->datareferencia); // Considerando o formato dd/mm/yyyy

                // Calcular o número de dias úteis entre 'datareferencia' e a data atual
                $processo->prazo = $this->calcularDiasUteis($dataReferencia, Carbon::now(), $feriados);
            }
        }

        $criterios = [
            "nenhum" => "prazo",
            "remessa" => "remessa",
            "status" => "codigostatus",
            "data" => "datareferencia",
        ];
        
        if (isset($criterios[$agrupamento])) {
            usort($processos, function ($a, $b) use ($criterios, $agrupamento) {
                return $b->{$criterios[$agrupamento]} <=> $a->{$criterios[$agrupamento]};
            });
        }

        return $processos;
    }

    private function calcularDiasUteis($dataInicio, $dataFim, $feriados)
    {
        // Inicializa o contador de dias úteis
        $diasUteis = 0;

        // Itera de $dataInicio até $dataFim
        while ($dataInicio <= $dataFim) {
            // Verifica se o dia é útil (não é sábado nem domingo)
            if ($dataInicio->isWeekday()) {
                // Verifica se a data não é um feriado
                $dataStr = $dataInicio->format('Y-m-d');
                if (!in_array($dataStr, $feriados)) {
                    $diasUteis++;
                }
            }

            // Avança para o próximo dia
            $dataInicio->addDay();
        }

        return $diasUteis;
    }

    public function getCodigoRemessa(){

        $codigoRemessa = $this->remessaSicomService->getCodigoRemessa();
        return $codigoRemessa;

    }

    public function validacaoCadastroInicial(Request $request){
        parse_str($request->getContent(), $params);
        $processos = json_decode($params['processos'], true);

        $licitacoes = implode(",", array_map(function($processo) {
            return $processo['l227_licitacao'];
        }, $processos));

        // Validação cadastro inicial
        if(!empty($licitacoes)){
            $validacaoCadastroInicial = DB::select("select l20_codigo,l20_cadinicial from liclicita inner join cflicita on l03_codigo = l20_codtipocom where l20_codigo in ($licitacoes) and l03_pctipocompratribunal not in (100, 101, 102, 103) and l20_cadinicial not in (2,3)");
            if (!empty($validacaoCadastroInicial)) {
                throw new Exception("Usuário: Para gerar os arquivos, é necessário realizar o cadastro do edital na rotina Licitação > Procedimentos > Cadastro de Edital > Inclusão, das seguintes licitações [$licitacoes].");
            }
        
        }

    }

    public function salvarRemessa(Request $request){

        parse_str($request->getContent(), $params);
        $processos = json_decode($params['processos'], true);
        $remessa = json_decode($params['remessa'], true);
        $data = $params['dataenvio'];

        $statusMap = ['Abertura Pendente' => "1",'Julgamento Pendente' => "2",'Homologação Pendente' => "3",'Envio Pendente' => "4",'Enviado' => "5"];
        foreach ($processos as $processo) {


            switch ($processo['status']) {
                case $statusMap['Abertura Pendente']:
                    $this->remessaSicomService->salvarAbertura($processo, $remessa,$data);
                    break;

                case $statusMap['Julgamento Pendente']:
                    $this->remessaSicomService->salvarJulgamento($processo, $remessa,$data);
                    break;

                case $statusMap['Homologação Pendente']:
                    $this->remessaSicomService->salvarHomologacao($processo, $remessa,$data);
                    break;

                case $statusMap['Envio Pendente']:
                    $this->remessaSicomService->salvarEnvio($processo, $remessa,$data);
                    break;

                default:

                    break;
            }

            $this->updateStatus($processo);

        }

    }

    public function updateStatus($processo){

        if (isset($processo["l227_licitacao"])) {

            $statusAtual = DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->value('l20_statusenviosicom');
            $situacaoAtual = DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->value('l20_licsituacao');

            if($statusAtual == 5) return;
            
            if ($statusAtual == 3 || $statusAtual == 4) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 5]);
                return;
            } 
            
            if ($statusAtual == 1 && $situacaoAtual == 0) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 2]);
                return;
            }

            if ($statusAtual == 1 && in_array($situacaoAtual, [1,13])) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 3]);
                return;
            }

            if ($statusAtual == 1 && $situacaoAtual == 10) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 5]);
                return;
            }

            if ($statusAtual == 2 && $situacaoAtual == 1) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 3]);
                return;
            }

            if ($statusAtual == 2 && $situacaoAtual == 10) {
                DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->update(['l20_statusenviosicom' => 5]);
                return;
            }


        }

        if (isset($processo["l227_adesao"])) {

            $statusAtual = DB::table('sicom.adesaoregprecos')
            ->where('si06_sequencial', $processo["l227_adesao"])
            ->value('si06_statusenviosicom');

            if($statusAtual == 5) return;

            DB::table('sicom.adesaoregprecos')
                ->where('si06_sequencial', $processo["l227_adesao"])
                ->increment('si06_statusenviosicom', 1);
        }
    }

    
}
