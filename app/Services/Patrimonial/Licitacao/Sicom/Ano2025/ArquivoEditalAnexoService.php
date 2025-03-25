<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");
require_once 'dbforms/db_funcoes.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoAberlicRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoDispensaRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoEditalAnexoRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoJulglicRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoRegadesaoRepository;
use GerarAM;
use db_utils;

class ArquivoEditalAnexoService extends GerarAM
{

    /**
     * @var ArquivoEditalAnexoRepository
     */
    private $ArquivoEditalAnexoRepository;

    public function __construct()
    {
        $this->ArquivoEditalAnexoRepository = new ArquivoEditalAnexoRepository();
    }

    public function gerarZip($licitacoes,$adesoes)
    {

        //if(empty($licitacoes)){
          //  return "";
        //}
        
        $anexos = [];
        $anexosJulgamento = [];
        $codmunicipio = "";
        $codorgao = "";
        $prefixoZero = "";

        $arquivoAberlicRepository = new ArquivoAberlicRepository();
        $registrosAberlic = !empty($licitacoes) ? $arquivoAberlicRepository->getDadosRegistro10($licitacoes) : null;
        $registrosAberlic = is_array($registrosAberlic) ? $registrosAberlic : [];
        $aAberlic = array_column($registrosAberlic, 'codlicitacao');

        $arquivoDispensaRepository = new ArquivoDispensaRepository();
        $registrosDispensa = !empty($licitacoes) ? $arquivoDispensaRepository->getDadosRegistro10($licitacoes) : null;
        $registrosDispensa = is_array($registrosDispensa) ? $registrosDispensa : [];
        $aDispensa = array_column($registrosDispensa, 'codlicitacao');

        $aProcessos = array_merge($aAberlic, $aDispensa);
        $seqlicitacoes = implode(',', $aProcessos);


        if($seqlicitacoes != ""){
            $anexos = $this->ArquivoEditalAnexoRepository->getAnexosDispensaEdital($seqlicitacoes);
        }

        $aListaAnexos = " ";

        $tiposAnexos = ['mc' => 'MINUTA_CONTRATO_','po' => 'PLANILHA_ORCAMENTARIA_','cr' => 'CRONOGRAMA_','cb' => 'COMPOSICAO_BDI_','fl' => 'FOTO_LOCAL_',];
        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $conn = pg_connect("host={$_SESSION['DB_servidor']} dbname={$_SESSION['DB_base']} port={$_SESSION['DB_porta']} user={$_SESSION['DB_user']} password={$_SESSION['DB_senha']}");

        foreach ($anexos ?? [] as $anexo) {

            if($anexo->naturezaobjeto == "1" && in_array($anexo->tipo,["td"])){
                continue;
            }

            $nomeArquivo = "";
            $unidade = in_array($anexo->si09_tipoinstit, $tiposOrgaos) ? $anexo->si09_codunidadesubunidade : ($anexo->unidade !== '' ? $anexo->unidade : '0');
            $extensao = pathinfo($anexo->nomearquivo, PATHINFO_EXTENSION);
            $codmunicipio = $anexo->codmunicipio;
            $codorgao = $anexo->codorgao;

            $prefixo = in_array($anexo->codigotribunal, [100, 101, 102, 103]) ? "DISPENSA_" : "EDITAL_";
            $prefixoZero = in_array($anexo->si09_tipoinstit, $tiposOrgaos) ? "" : "0";

            $tipoDocumento = $anexo->naturezaobjeto == "1" && isset($tiposAnexos[$anexo->tipo]) ? $tiposAnexos[$anexo->tipo] : "";

            $tipoprocesso = $prefixo == "DISPENSA_" ? "_{$anexo->tipoprocesso}" : "";

            $nomeArquivo .= "{$prefixo}{$tipoDocumento}$prefixoZero{$anexo->codmunicipio}_$prefixoZero{$anexo->codorgao}_{$unidade}_{$anexo->exercicio}_{$anexo->nroprocesso}$tipoprocesso.$extensao";
            db_inicio_transacao();      
            pg_lo_export($conn, $anexo->arquivo, $nomeArquivo);
            db_fim_transacao();
            $aListaAnexos .= $nomeArquivo . ' ';

        }

        $arquivoJulglicRepository = new ArquivoJulglicRepository();
        $registrosJulglic = !empty($licitacoes) ? $arquivoJulglicRepository->getDadosRegistro10($licitacoes) : null;
        $registrosJulglic = is_array($registrosJulglic) ? $registrosJulglic : [];
        $aJulglic = array_column($registrosJulglic, 'codlicitacao');
        $seqlicitacoes = implode(',', $aJulglic);

        if($seqlicitacoes != ""){
            $anexosJulgamento = $this->ArquivoEditalAnexoRepository->getAnexosJulgamento($seqlicitacoes);
        }

        foreach ($anexosJulgamento ?? [] as $anexoJulgamento) {

            $unidade = in_array($anexoJulgamento->si09_tipoinstit, $tiposOrgaos) ? $anexoJulgamento->si09_codunidadesubunidade : ($anexoJulgamento->unidade !== '' ? $anexoJulgamento->unidade : '0');
            $extensao = pathinfo($anexoJulgamento->nomearquivo, PATHINFO_EXTENSION);
            $codmunicipio = $anexoJulgamento->codmunicipio;
            $codorgao = $anexoJulgamento->codorgao;
            $prefixoZero = in_array($anexoJulgamento->si09_tipoinstit, $tiposOrgaos) ? "" : "0";

            $nomeArquivo = "ATA_JULGAMENTO_$prefixoZero{$anexoJulgamento->codmunicipio}_$prefixoZero{$anexoJulgamento->codorgao}_{$unidade}_{$anexoJulgamento->exercicio}_{$anexoJulgamento->nroprocesso}.$extensao";

            db_inicio_transacao();      
            pg_lo_export($conn, $anexoJulgamento->arquivo, $nomeArquivo);
            db_fim_transacao();
            $aListaAnexos .= $nomeArquivo . ' ';

        }

        $arquivoRegAdesaoRepository = new ArquivoRegadesaoRepository();
        $registrosRegAdesao = !empty($adesoes) ? $arquivoRegAdesaoRepository->getDadosRegistro10($adesoes) : null;
        $registrosRegAdesao = is_array($registrosRegAdesao) ? $registrosRegAdesao : [];
        $aRegAdesao = array_column($registrosRegAdesao, 'si06_sequencial');
        $seqadesoes = implode(',', $aRegAdesao);

        if($seqadesoes != ""){
            $anexosAdesao = $this->ArquivoEditalAnexoRepository->getAnexosRegAdesao($seqadesoes);
        }

        foreach ($anexosAdesao ?? [] as $anexoAdesao) {

            $unidade = in_array($anexoAdesao->si09_tipoinstit, $tiposOrgaos) ? $anexoAdesao->si09_codunidadesubunidade : ($anexoAdesao->unidade !== '' ? $anexoAdesao->unidade : '0');
            $codmunicipio = $anexoAdesao->codmunicipio;
            $codorgao = $anexoAdesao->codorgao;
            $prefixoZero = in_array($anexoAdesao->si09_tipoinstit, $tiposOrgaos) ? "" : "0";

            if (is_resource($anexoAdesao->arquivo)) {
                $anexoAdesao->arquivo = stream_get_contents($anexoAdesao->arquivo);
            }

            $nomeArquivo = "TERMO_ADESAO_REGISTRO_PRECOS_$prefixoZero{$anexoAdesao->codmunicipio}_$prefixoZero{$anexoAdesao->codorgao}_{$unidade}_{$anexoAdesao->exercicio}_{$anexoAdesao->nroprocesso}.pdf";            
            file_put_contents($nomeArquivo, base64_decode($anexoAdesao->arquivo));

            $aListaAnexos .= $nomeArquivo . ' ';

        }

        if (trim($aListaAnexos)) {
            
            $exercicioReferencia = db_getsession("DB_anousu");
            system("rm -f EDITAL_ANEXO_$prefixoZero{$codmunicipio}_$prefixoZero{$codorgao}_{$exercicioReferencia}.zip");
            system("bin/zip -q EDITAL_ANEXO_$prefixoZero{$codmunicipio}_$prefixoZero{$codorgao}_{$exercicioReferencia}.zip $aListaAnexos");
            
            $aAnexos = explode(' ', $aListaAnexos);

            foreach ($aAnexos as $arquivo) {
                if (!empty($arquivo)) {
                    unlink($arquivo);  // Tenta excluir o arquivo sem retorno de mensagens
                }
            }

            return "EDITAL_ANEXO_$prefixoZero{$codmunicipio}_$prefixoZero{$codorgao}_{$exercicioReferencia}.zip";

        }

        return "";

    }
}
