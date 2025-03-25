<?php


namespace App\Services\Licitacao\Sicom\Ano2025;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoAberlicRepository;
use App\Services\Licitacao\Sicom\StringService;
use Illuminate\Database\Capsule\Manager as DB;
use GerarAM;
use stdClass;

class ArquivoAberlicService extends GerarAM
{

    /**
     * @var ArquivoAberlicRepository
     */
    private $ArquivoAberlicRepository;

    public function __construct()
    {
        $this->ArquivoAberlicRepository = new ArquivoAberlicRepository();
    }

    public function gerarArquivo($licitacoes)
    {

        if (empty($licitacoes)) {
            $aCSV10 = array();
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->abreArquivo();
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        $stringService = new StringService();

        $aCSV10 = array();
        $aCSV11 = array();
        $aCSV12 = array();
        $aCSV13 = array();
        $aCSV14 = array();
        $aCSV15 = array();
        $aCSV20 = array();

        $this->sArquivo = "ABERLIC";
        $this->abreArquivo();

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];
        $tipoOrgao = db_gettipoinstit(db_getsession('DB_instit'));
        $dadosReg10 = $this->ArquivoAberlicRepository->getDadosRegistro10($licitacoes);

        if (empty($dadosReg10)) {
            $aCSV10['tiporegistro'] = '99';
            $this->sLinha = $aCSV10;
            $this->adicionaLinha();
            $this->fechaArquivo();
            return;
        }

        foreach ($dadosReg10 as $dadoReg10) {

            $aCSV10['tiporegistro']                             = $this->padLeftZero($dadoReg10->tiporegistro, 2);
            $aCSV10['tipocadastro']           = $this->padLeftZero("1", 1);
            $aCSV10['codorgaoresp']                            = $this->padLeftZero($dadoReg10->codorgaoresp, 3);
            $codunidadesubresp = !empty($dadoReg10->codunidsubant) ? $dadoReg10->codunidsubant : $dadoReg10->codunidadesubresp;
            $aCSV10['codunidadesub'] =  in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesubresp, 5); 
            $aCSV10['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg10->codunidadesubrespestadual, 4) : '';   
            $aCSV10['exerciciolicitacao']                  = $this->padLeftZero($dadoReg10->exerciciolicitacao, 4);
            $aCSV10['nroprocessolicitatorio']              = substr($dadoReg10->nroprocessolicitatorio, 0, 12);
            $aCSV10['dscCadastroLicitatorio']              = "";
            $aCSV10['leiLicitacao']   =   $this->padLeftZero($dadoReg10->lei, 1);
            $aCSV10['codModalidadeLicitacao']   =   $this->padLeftZero($dadoReg10->codmodalidadelicitacao, 2);
            $aCSV10['naturezaProcedimento']   =   $this->padLeftZero($dadoReg10->naturezaprocedimento, 1);
            $aCSV10['sequenciaFases'] = empty($dadoReg10->l20_inversaofases) ? '' : $dadoReg10->l20_inversaofases;
            $aCSV10['dtAberturaProcesso'] = $this->sicomDate($dadoReg10->dtabertura);
            $aCSV10['nroedital']                  = substr($dadoReg10->l20_nroedital, 0, 10);
            $aCSV10['exercicioedital']            = $dadoReg10->l20_exercicioedital ? $dadoReg10->l20_exercicioedital : intval($dadoReg10->exerciciolicitacao);
            $aCSV10['dtPublicacaoEdital'] = $this->sicomDate($dadoReg10->dtpublicacaoeditaldo);
            $aCSV10['link']                 = $dadoReg10->link == null ? '' : substr($stringService->removeCaracteres($dadoReg10->link), 0, 800);
            $aCSV10['dtpulicacaopncp']            = $this->sicomDate($dadoReg10->datapncp);
            $aCSV10['linkpncp']                   = substr($dadoReg10->linkpncp, 0, 200);
            $aCSV10['dtrecebimentodoc']           = $this->sicomDate($dadoReg10->dtrecebimentodoc);
            $aCSV10['dtAberturaEnvelopes']           = $this->sicomDate($dadoReg10->l20_dataaberproposta);
            $aCSV10['criteriojulgamento']              = $dadoReg10->tipolicitacao == 0 ? ' ' : substr($dadoReg10->tipolicitacao, 0, 1);
            $aCSV10['mododisputa']                = $dadoReg10->mododisputa;
            $aCSV10['naturezaobjeto']             = $dadoReg10->naturezaobjeto == 0 ? ' ' : substr($dadoReg10->naturezaobjeto, 0, 1);
            $aCSV10['objeto']                     = $dadoReg10->objeto == null ? '' : substr($stringService->removeCaracteres($dadoReg10->objeto), 0, 1000);
            $aCSV10['tipoorcamento']              = ($dadoReg10->l20_orcsigiloso == '' || $dadoReg10->l20_orcsigiloso == 'f') ? 1 : 2; 

            $orcamentosigiloso = DB::select("select * from liclicitem where l21_codliclicita = {$dadoReg10->codlicitacao} and l21_sigilo = true;");
            if (!empty($orcamentosigiloso)) {
                $aCSV10['tipoorcamento'] = 2;
            }

            $aCSV10['vlContratacao']              = $aCSV10['tipoorcamento'] == 2 ? ' ' : $this->sicomNumberReal($this->ArquivoAberlicRepository->getValorContrataoRegistro10($dadoReg10->codlicitacao), 2);
            $aCSV10['origemRecurso']              = $dadoReg10->origemrecurso;
            $aCSV10['dscOrigemRecurso']           = $dadoReg10->dscorigemrecurso == null ? '' : substr($stringService->removeCaracteres($dadoReg10->dscorigemrecurso), 0, 150);
            $aCSV10['nroconvidado']               = $dadoReg10->nroconvidado == 0 ? ' ' : substr($dadoReg10->nroconvidado, 0, 3);
            $aCSV10['clausulaprorrogacao']        = substr($dadoReg10->clausulaprorrogacao, 0, 250);
            $aCSV10['unidademedidaprazoexecucao'] = $this->padLeftZero($dadoReg10->unidademedidaprazoexecucao, 1);
            $aCSV10['prazoexecucao']              = substr($dadoReg10->prazoexecucao, 0, 4);
            $aCSV10['formapagamento']             = $dadoReg10->formapagamento == null ? '' : substr($stringService->removeCaracteres($dadoReg10->formapagamento), 0, 80);
            $aCSV10['criterioaceitabilidade']     = $dadoReg10->criterioaceitabilidade == null ? '' : substr($stringService->removeCaracteres($dadoReg10->criterioaceitabilidade), 0, 80);
            $aCSV10['criterioadjudicacao']        = $this->padLeftZero($dadoReg10->criterioadjudicacao, 1);
            $dadoReg10->l20_descrcriterio = $dadoReg10->l20_descrcriterio == null ? "" : $dadoReg10->l20_descrcriterio;
            $aCSV10['dscCriterioAdjudicacao']     = $dadoReg10->l20_descrcriterio == null ? '' : substr($stringService->removeCaracteres($dadoReg10->l20_descrcriterio), 0, 150);
            $aCSV10['processoporlote']            = $this->padLeftZero($dadoReg10->processoporlote, 1);
            $aCSV10['criteriodesempate']          = $this->padLeftZero($dadoReg10->criteriodesempate, 1);
            $aCSV10['emailcontato']               = $dadoReg10->emailcontato;

            $this->sLinha = $aCSV10;
            $this->adicionaLinha();

            $dadosReg11 = $this->ArquivoAberlicRepository->getDadosRegistro11($dadoReg10->codlicitacao);

            $aDadosAgrupados11 = array();
            
            foreach ($dadosReg11 as $dadoReg11) {

                $sHash11 = $dadoReg11->dsclote;
                   
                if (!isset($aDadosAgrupados11[$sHash11])) {
    
                    $oDados11 = new stdClass();
                        
                    $oDados11->tiporegistro = 11;
                    $oDados11->codorgaoresp = $dadoReg11->codorgaoresp;
                    $codUnidadeSub = !empty($dadoReg11->codunidsubant) ? $dadoReg11->codunidsubant : $dadoReg11->codunidadesubresp;
                    $oDados11->codunidadesubresp = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados11->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg11->codunidadesubrespestadual : '';     
                    $oDados11->exerciciolicitacao = $dadoReg11->exerciciolicitacao;
                    $oDados11->nroprocessolicitatorio = $dadoReg11->nroprocessolicitatorio;
                    $oDados11->nrolote = substr($dadoReg11->nrolote, -4);
                    $oDados11->dsclote = $dadoReg11->dsclote;    
                    $aDadosAgrupados11[$sHash11] = $oDados11;
    
                }
    
            }

            foreach ($aDadosAgrupados11 as $oDadosAgrupados) {

                $aCSV11['tiporegistro']           = $this->padLeftZero($oDadosAgrupados->tiporegistro, 2);
                $aCSV11['codorgaoresp']           = $this->padLeftZero($oDadosAgrupados->codorgaoresp, 3);
                $aCSV11['codunidadesubresp']      =  $oDadosAgrupados->codunidadesubresp == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubresp, 5);
                $aCSV11['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4);    
                $aCSV11['exerciciolicitacao']     = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
                $aCSV11['nroprocessolicitatorio'] = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);
                $aCSV11['nrolote']                =  $dadoReg10->processoporlote == "2" ? '' : substr($oDadosAgrupados->nrolote, 0, 4);
                $aCSV11['dsclote']                = $oDadosAgrupados->dsclote == null ? '' : substr($stringService->removeCaracteres($oDadosAgrupados->dsclote), 0, 250);
                $this->sLinha = $aCSV11;
                $this->adicionaLinha();
            }

            $dadosReg12 = $this->ArquivoAberlicRepository->getDadosRegistro12($dadoReg10->codlicitacao);
            $nroitem = 1;

            foreach ($dadosReg12 as $dadoReg12) {
                
                $aCSV12['tiporegistro']           = $this->padLeftZero($dadoReg12->tiporegistro, 2);
                $aCSV12['codorgaoresp']                            = $this->padLeftZero($dadoReg12->codorgaoresp, 3);
                $codunidadesubresp = !empty($dadoReg12->codunidsubant) ? $dadoReg12->codunidsubant : $dadoReg12->codunidadesubresp;
                $aCSV12['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesubresp, 5); 
                $aCSV12['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg12->codunidadesubrespestadual, 4) : '';   
                $aCSV12['exerciciolicitacao']                  = $this->padLeftZero($dadoReg12->exerciciolicitacao, 4);
                $aCSV12['nroprocessolicitatorio']              = substr($dadoReg12->nroprocessolicitatorio, 0, 12);
                $aCSV12['coditem']                = substr($dadoReg12->coditem, 0, 15);
                $aCSV12['nroitem']                = $nroitem;
                $nroitem++;
                $this->sLinha = $aCSV12;
                $this->adicionaLinha();
    
            }


            $dadosReg13 = $this->ArquivoAberlicRepository->getDadosRegistro13($dadoReg10->codlicitacao);

            foreach ($dadosReg13 as $dadoReg13) {
                
                $aCSV13['tiporegistro']           = $this->padLeftZero($dadoReg13->tiporegistro, 2);
                $aCSV13['codorgaoresp']                            = $this->padLeftZero($dadoReg13->codorgaoresp, 3);
                $codunidadesubresp = !empty($dadoReg13->codunidsubant) ? $dadoReg13->codunidsubant : $dadoReg13->codunidadesubresp;
                $aCSV13['codunidadesub'] = in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesubresp, 5); 
                $aCSV13['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg13->codunidadesubrespestadual, 4) : '';   
                $aCSV13['exerciciolicitacao']                  = $this->padLeftZero($dadoReg13->exerciciolicitacao, 4);
                $aCSV13['nroprocessolicitatorio']              = substr($dadoReg13->nroprocessolicitatorio, 0, 12);
                $aCSV13['nrolote']                = $dadoReg10->processoporlote == "2" ? '' : substr($dadoReg13->nrolote, 0, 4);
                $aCSV13['coditem']                = substr($dadoReg13->coditem, 0, 15);
                $this->sLinha = $aCSV13;
                $this->adicionaLinha();
    
            }

            $dadosReg14 = $this->ArquivoAberlicRepository->getDadosRegistro14($dadoReg10->codlicitacao);
            $aDadosAgrupados14 = array();

            foreach ($dadosReg14 as $dadoReg14) {

                $sHash14 = $dadoReg14->coditem;
                   
                if (!isset($aDadosAgrupados14[$sHash14])) {
    
                    $oDados14 = new stdClass();
                        
                    $oDados14->tiporegistro = 14;
                    $oDados14->codorgaoresp = $dadoReg14->codorgaoresp;
                    $codUnidadeSub = !empty($dadoReg14->codunidsubant) ? $dadoReg14->codunidsubant : $dadoReg14->codunidadesubresp;
                    $oDados14->codunidadesubresp = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados14->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg14->codunidadesubrespestadual : '';     
                    $oDados14->exerciciolicitacao = $dadoReg14->exerciciolicitacao;
                    $oDados14->nroprocessolicitatorio = $dadoReg14->nroprocessolicitatorio;
                    $oDados14->nrolote = substr($dadoReg14->nrolote, -4);
                    $oDados14->coditem = $dadoReg14->coditem;
                    $oDados14->dtcotacao = $dadoReg14->dtcotacao;

                    $oDados14->vlrefpercentual = isset($dadoReg14->si02_vlpercreferencia) ? number_format($dadoReg14->si02_vlpercreferencia, '2', ',', '.') : 0;
                    if ($dadoReg10->criterioadjudicacao == 1) {

                        $oDados14->vlrefpercentual = $dadoReg14->pc23_perctaxadesctabela;
                    }

                    if ($dadoReg10->criterioadjudicacao == 3 || $dadoReg10->criterioadjudicacao == 4) {
                        $oDados14->vlrefpercentual = 0;
                    }
                    
                    $oDados14->vlcotprecosunitario =  $dadoReg14->vlcotprecosunitario;
                    $oDados14->quantidade = $dadoReg14->quantidade;
                    $oDados14->vlminalienbens = $dadoReg14->vlminalienbens; 
                    $aDadosAgrupados14[$sHash14] = $oDados14;
    
                }else {
                    $aDadosAgrupados14[$sHash14]->quantidade += $dadoReg14->quantidade;
                }
    
            }

            foreach ($aDadosAgrupados14 as $oDadosAgrupados) {

                $aCSV14['tiporegistro']           = $this->padLeftZero($oDadosAgrupados->tiporegistro, 2);
                $aCSV14['codorgaoresp']           = $this->padLeftZero($oDadosAgrupados->codorgaoresp, 3);
                $aCSV14['codunidadesubresp']      =  $oDadosAgrupados->codunidadesubresp ==  '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubresp, 5);
                $aCSV14['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4);   
                $aCSV14['exerciciolicitacao']     = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
                $aCSV14['nroprocessolicitatorio'] = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);
                $aCSV14['nrolote']                = $dadoReg10->processoporlote == "2" ? '' : substr($oDadosAgrupados->nrolote, 0, 4);
                $aCSV14['coditem']                = substr($oDadosAgrupados->coditem, 0, 15);
                $aCSV14['dtcotacao']              = $this->sicomDate($oDadosAgrupados->dtcotacao);
                $aCSV14['vlrefpercentual']        = $this->sicomNumberReal($oDadosAgrupados->vlrefpercentual, 2);

                if ($dadoReg10->criterioadjudicacao == 1 || $dadoReg10->criterioadjudicacao == 2) {
                    $aCSV14['vlcotprecosunitario']    = $this->sicomNumberReal(0, 4);
                } else {
                    $aCSV14['vlcotprecosunitario']    = $this->sicomNumberReal($oDadosAgrupados->vlcotprecosunitario, 4);
                }
                $aCSV14['quantidade']             = $this->sicomNumberReal($oDadosAgrupados->quantidade, 4);
                $aCSV14['vlminalienbens']         = $this->sicomNumberReal($oDadosAgrupados->vlminalienbens, 2);
                $this->sLinha = $aCSV14;
                $this->adicionaLinha();
            }

            $dadosReg15 = $this->ArquivoAberlicRepository->getDadosRegistro15($dadoReg10->codlicitacao);

            foreach ($dadosReg15 as $dadoReg15) {
                
                $aCSV15['tiporegistro']           = $this->padLeftZero($dadoReg15->tiporegistro, 2);
                $aCSV15['codorgaoresp']                            = $this->padLeftZero($dadoReg15->codorgaoresp, 2);
                $codunidadesubresp = !empty($dadoReg15->codunidsubant) ? $dadoReg15->codunidsubant : $dadoReg15->codunidadesubresp;
                $aCSV15['codunidadesub'] =  in_array($tipoOrgao, $tiposOrgaos) ? '' : $this->padLeftZero($codunidadesubresp, 5); 
                $aCSV15['codunidadesubrespestadual'] = in_array($tipoOrgao, $tiposOrgaos) ? $this->padLeftZero($dadoReg15->codunidadesubrespestadual, 4) : '';   
                $aCSV15['exerciciolicitacao']                  = $this->padLeftZero($dadoReg15->exerciciolicitacao, 4);
                $aCSV15['nroprocessolicitatorio']              = substr($dadoReg15->nroprocessolicitatorio, 0, 12);
                $aCSV15['nrolote']                =  $dadoReg10->processoporlote == "2" ? '' : substr($dadoReg15->nrolote, 0, 4);
                $aCSV15['coditem']                = substr($dadoReg15->coditem, 0, 15);
                $aCSV15['vlitem']                 = $this->sicomNumberReal($dadoReg15->vlitem, 4);
                $this->sLinha = $aCSV15;
                $this->adicionaLinha();
    
            }

        }

        $dadosReg20 = $this->ArquivoAberlicRepository->getDadosRegistro20($licitacoes);
        $aDadosAgrupados20 = array();

        foreach ($dadosReg20 as $dadoReg20) {

                $sHash20 = $dadoReg20->julgamento == 3 ? $dadoReg20->lote : $dadoReg20->sequencialendereco;

                if (!isset($aDadosAgrupados20[$sHash20])) {


                    $oDados20 = new stdClass();

                    $oDados20->tiporegistro = 20; 
                    $oDados20->codorgaoresp = $dadoReg20->codorgaoresp;
                    $codUnidadeSub = !empty($dadoReg20->codunidsubant) ? $dadoReg20->codunidsubant : $dadoReg20->codunidadesubresp;
                    $oDados20->codunidadesubresp = in_array($tipoOrgao, $tiposOrgaos) ? '' : $codUnidadeSub;
                    $oDados20->codunidadesubrespestadual = in_array($tipoOrgao, $tiposOrgaos) ? $dadoReg20->codunidadesubrespestadual : '';     
                    $oDados20->exerciciolicitacao = $dadoReg20->exerciciolicitacao;
                    $oDados20->nroprocessolicitatorio = $dadoReg20->nroprocessolicitatorio;
                    $oDados20->classeobjeto = $dadoReg20->classeobjeto;
                    $oDados20->tipoatividadeobra = $dadoReg20->tipoatividadeobra;
                    $oDados20->dscTipoAtividadeObra = "";
                    $oDados20->tipoatividadeservico = $dadoReg20->tipoatividadeservico == 0 ? '' : $dadoReg20->tipoatividadeservico;
                    $oDados20->dscatividadeservico = $dadoReg20->dscatividadeservico;
                    $oDados20->tipoatividadeservespecializado = $dadoReg20->tipoatividadeservespecializado;
                    $oDados20->dscatividadeservespecializado = $dadoReg20->dscatividadeservespecializado;
                    $oDados20->codfuncao = $dadoReg20->codfuncao;
                    $oDados20->codsubfuncao = $dadoReg20->codsubfuncao;
                    $oDados20->codbempublico = $dadoReg20->codbempublico ? $dadoReg20->codbempublico : 9900;
                    $oDados20->nrolote = $dadoReg20->processoporlote == "2" ? '' : substr($dadoReg20->nrolote, 0, 4);
                    $oDados20->regimeexecucaoobras = $dadoReg20->regimeexecucaoobras;
                    $oDados20->bdi = $dadoReg20->bdi;
                    $oDados20->mesreforc = $dadoReg20->mesreforc;
                    $oDados20->exercicioreforc = $dadoReg20->exercicioreforc; 
                    $oDados20->planilhamodelo = $dadoReg20->planilhamodelo;

                    $oDados20->logradouro = $dadoReg20->logradouro == null ? '' : $stringService->removeCaracteres(utf8_decode($dadoReg20->logradouro));
                    $oDados20->numero = $dadoReg20->numero;
                    $oDados20->bairro = $dadoReg20->bairro;
                    $oDados20->distrito = $dadoReg20->distrito;
                    $tipoInstituicao = db_gettipoinstit(db_getsession('DB_instit'));
                    $oDados20->municipio = $dadoReg20->municipio == null ? '' :  $stringService->removeCaracteres($dadoReg20->municipio);
                    $oDados20->cep = $dadoReg20->cep;
                    $oDados20->latitude = $dadoReg20->latitude;
                    $oDados20->longitude = $dadoReg20->longitude;
                    $oDados20->codmunicipioibge = $tipoInstituicao == "51" ? $dadoReg20->codmunicipioibge : "";

                    $oDados20->naturezaobjeto = $dadoReg20->naturezaobjeto;

                    $aDadosAgrupados20[$sHash20] = $oDados20;

                }


        }

        foreach ($aDadosAgrupados20 as $oDadosAgrupados) {

            $aCSV20['tiporegistro']           = $this->padLeftZero($oDadosAgrupados->tiporegistro, 2);
            $aCSV20['codorgaoresp']           = $this->padLeftZero($oDadosAgrupados->codorgaoresp, 3);
            $aCSV20['codunidadesubresp']      = $oDadosAgrupados->codunidadesubresp  == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubresp, 5);
            $aCSV20['codunidadesubrespestadual'] = $oDadosAgrupados->codunidadesubrespestadual == '' ? '' : $this->padLeftZero($oDadosAgrupados->codunidadesubrespestadual, 4);   
            $aCSV20['exerciciolicitacao']     = $this->padLeftZero($oDadosAgrupados->exerciciolicitacao, 4);
            $aCSV20['nroprocessolicitatorio'] = substr($oDadosAgrupados->nroprocessolicitatorio, 0, 12);
            $aCSV20['classeobjeto']           = $oDadosAgrupados->classeobjeto;
            $aCSV20['tipoatividadeobra']      = !trim($oDadosAgrupados->tipoatividadeobra) ? '' : $this->padLeftZero($oDadosAgrupados->tipoatividadeobra, 2);
            $aCSV20['dscTipoAtividadeObra'] = "";
            $aCSV20['tipoatividadeservico']   = !trim($oDadosAgrupados->tipoatividadeservico) ? '' : $this->padLeftZero($oDadosAgrupados->tipoatividadeservico, 2);
            $aCSV20['dscatividadeservico']    = utf8_decode($oDadosAgrupados->dscatividadeservico);
            $aCSV20['tipoatividadeservespecializado']  = !trim($oDadosAgrupados->tipoatividadeservespecializado) ? '' : $this->padLeftZero($oDadosAgrupados->tipoatividadeservespecializado, 2);
            $aCSV20['dscatividadeservespecializado']   = utf8_decode($oDadosAgrupados->dscatividadeservespecializado);
            $aCSV20['codfuncao']                       = $this->padLeftZero(intval($oDadosAgrupados->codfuncao), 2);
            $aCSV20['codsubfuncao']                    = $this->padLeftZero(intval($oDadosAgrupados->codsubfuncao), 3);
            $aCSV20['codbempublico']                   = $this->padLeftZero($oDadosAgrupados->codbempublico, 4);
            $aCSV20['nrolote']                         = $oDadosAgrupados->nrolote;
            $aCSV20['regimeexecucaoobras']        = $oDadosAgrupados->regimeexecucaoobras == 0 ? '' : $oDadosAgrupados->regimeexecucaoobras;
            $aCSV20['bdi'] = in_array($oDadosAgrupados->naturezaobjeto, ['1', '7']) ? $this->sicomNumberReal($oDadosAgrupados->bdi, 2) : '';
            $aCSV20['mesRefOrc']                     = $this->padLeftZero($oDadosAgrupados->mesreforc, 2);
            $aCSV20['exercicioRefOrc']               = $oDadosAgrupados->exercicioreforc;
            $aCSV20['utilizacaoplanilhamodelo']        = $oDadosAgrupados->planilhamodelo;
            $aCSV20['logradouro']                  = $oDadosAgrupados->logradouro;
            $aCSV20['numero']                      = !$oDadosAgrupados->numero ? '' : $oDadosAgrupados->numero;
            $aCSV20['bairro']                      = utf8_decode($oDadosAgrupados->bairro);
            $aCSV20['distrito']                    = utf8_decode($oDadosAgrupados->distrito);
            $aCSV20['municipio']                   = $oDadosAgrupados->municipio;
            $aCSV20['cep']                         = $oDadosAgrupados->cep;
            $aCSV20['latitude']                    = $this->sicomNumberReal($oDadosAgrupados->latitude, 6);
            $aCSV20['longitude']                   = $this->sicomNumberReal($oDadosAgrupados->longitude, 6);
            $aCSV20['codMunicipioIBGE']                   = $oDadosAgrupados->codmunicipioibge;

            $this->sLinha = $aCSV20;
            $this->adicionaLinha();

        }

        $this->fechaArquivo();
    }

}
