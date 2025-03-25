<?php

namespace App\Services\LicLicita;

use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\PccfEditalNum;
use App\Repositories\Contabilidade\CondataconfRepository;
use App\Repositories\Patrimonial\Licitacao\CflicitaRepository;
use App\Repositories\Patrimonial\Licitacao\DecretoPregaoRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaParamRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LicPregaoCgmRepository;
use App\Repositories\Patrimonial\Licitacao\PccfEditalNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaParRepository;
use App\Services\LicComissaoCgm\UpdateLicComissaoCgmService;
use App\Services\LicComissaoCgm\DeleteLicComissaoCgmService;
use App\Services\LicLicitaProc\DeleteLicLicitaProcService;
use App\Services\LicLicitaProc\UpdateLicLicitaProcService;
use App\Services\LicLicitaSituacao\UpdateLicLicitaSituacaoService;
use DateTime;
use Illuminate\Database\Capsule\Manager as DB;

class UpdateLicLicitaService{
    private LiclicitaRepository $liclicitaRepository;
    private CflicitaRepository $cflicitaRepository;
    private LicLicitaParamRepository $licLicitaParamRepository;
    private DecretoPregaoRepository $decretoPregaoRepository;
    private PccfLicitaParRepository $pccfLicitaParRepository;
    private PccfLicitaNumRepository $pccfLicitaNumRepository;
    private PccfEditalNumRepository $pccfEditalNumRepository;
    private CondataconfRepository $condataconfRepository;
    private LicPregaoCgmRepository $licPregaoCgmRepository;
    private LicilicitemRepository $licilicitemRepository;

    private UpdateLicLicitaProcService $updateLicLicitaProcService;
    private DeleteLicLicitaProcService $deleteLicLicitaProcService;

    private UpdateLicComissaoCgmService $updateLicComissaoCgmService;
    private DeleteLicComissaoCgmService $deleteLicComissaoCgmService;

    private UpdateLicLicitaSituacaoService $updateLicLicitaSituacaoService;

    public function __construct(){
        $this->liclicitaRepository = new LiclicitaRepository();
        $this->cflicitaRepository = new CflicitaRepository();
        $this->licLicitaParamRepository = new LicLicitaParamRepository();
        $this->decretoPregaoRepository = new DecretoPregaoRepository();
        $this->pccfLicitaParRepository = new PccfLicitaParRepository();
        $this->pccfLicitaNumRepository = new PccfLicitaNumRepository();
        $this->pccfEditalNumRepository = new PccfEditalNumRepository();
        $this->condataconfRepository = new CondataconfRepository();
        $this->licPregaoCgmRepository = new LicPregaoCgmRepository();
        $this->licilicitemRepository = new LicilicitemRepository();

        $this->updateLicLicitaProcService = new UpdateLicLicitaProcService();
        $this->deleteLicLicitaProcService = new DeleteLicLicitaProcService();

        $this->updateLicComissaoCgmService = new UpdateLicComissaoCgmService();
        $this->deleteLicComissaoCgmService = new DeleteLicComissaoCgmService();

        $this->updateLicLicitaSituacaoService = new UpdateLicLicitaSituacaoService();
    }

    public function execute(object $data){
        $oDispensa = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo);
        if(!$data->is_contass && $oDispensa->l20_licsituacao != 0){
            throw new \Exception("A Licitação não pode ser removida devido sua situação. Situação atual: ${$oDespensa->dl_situacao}", 400);
        }

        $oTipoProcedimento = $this->cflicitaRepository->getDadosTipoProcesso($data->l20_codtipocom);
        $oParam = $this->licLicitaParamRepository->getDados($oDispensa->l20_instit);

        $data->l20_tipoprocesso = $oTipoProcedimento->l03_pctipocompratribunal;

        if(!is_null($data->l20_edital) && ($data->l20_edital <= 0 || trim($data->l20_edital) == '')){
            throw new \Exception("O número informado para o processo não pode ser 0!", 400);
        }

        if(!is_null($data->l20_numero) && ($data->l20_numero <= 0 || trim($data->l20_numero) == '')){
            throw new \Exception("O número informado para o processo não pode ser 0!", 400);
        }

        if(!is_null($data->l20_nroedital) && is_numeric($data->l20_nroedital) && ($data->l20_nroedital <= 0 || trim($data->l20_nroedital) == '')){
            throw new \Exception("O número do edital informado não pode ser 0!", 400);
        }

        $aTipoLicNatProc = [50, 48, 49, 53, 52, 54];
        if($oParam->l12_numeracaomanual){
            if(!empty($data->l20_edital)){
                $oNumeracao = $this->liclicitaRepository->getNumeroByEdital($oDispensa->l20_anousu, $oDispensa->l20_instit, $data->l20_edital, $oDispensa->l20_codigo);
                if(!empty($oNumeracao)){
                    throw new \Exception("O número informado já está em uso no exercício atual pelo <b>Processo Licitatório</b> sequencial <b>". $oNumeracao->l20_codigo ."</b> . Por favor, escolha um número diferente ou revise o número inserido.", 400);
                }
            }

            if(!empty($data->l20_numero)){
                $oProcesso = $this->liclicitaRepository->getProcessoByNumero($oDispensa->l20_anousu, $oDispensa->l20_instit, $data->l20_codtipocom, $data->l20_numero, $oDispensa->l20_codigo);
                if(!empty($oProcesso)){
                    throw new \Exception("A <b>Numeração</b> informada já está em uso para a modalidade selecionada no processo sequencial <b>". $oProcesso->l20_codigo ."</b> deste exercício. Por favor, escolha um número diferente ou revise o número inserido.", 400);
                }
            }

            if(!empty($data->l20_nroedital)){
                $oEdital = $this->liclicitaRepository->getEditalByNumeroEdital($oDispensa->l20_anousu, $oDispensa->l20_instit, $data->l20_nroedital, $oDispensa->l20_codigo);
                if(!empty($oEdital)){
                    throw new \Exception("O número do <b>Edital</b> informado já está em uso no processo <b>". $oEdital->l20_codigo ."</b> deste exercício. Por favor, escolha um número diferente ou revise o número inserido.", 400);
                }
            }
        }

        if(!empty($data->l20_dispensaporvalor) && $data->l20_dispensaporvalor == 't' && !empty($data->l20_naturezaobjeto) && $data->l20_naturezaobjeto == 1){
            throw new \Exception("Não possível incluir a Dispensa por valor quando a natureza do objeto  1 - Obras e Servios de Engenharia", 400);
        }

        if(in_array($oTipoProcedimento->l20_tipoprocesso, $aTipoLicNatProc) && empty($data->l20_tipliticacao)){
            throw new \Exception("Campo Tipo de Licitacao nao informado", 400);
        }

        if(empty($data->l20_tipnaturezaproced) && !empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1){
            throw new \Exception("Campo Natureza do Procedimento nao informado", 400);
        }

        if(empty($data->l20_categoriaprocesso) && $oParam->l12_pncp && !empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1){
            throw new \Exception("Campo categoria do processo não informado", 400);
        }

        if($data->l03_presencial && $oParam->l12_pncp && $data->l20_leidalicitacao && empty($data->l20_justificativapncp)){
            throw new \Exception("Campo Justificativa PNCP não informado", 400);
        }

        if($data->l20_tipoprocesso != 51 && empty($data->l20_naturezaobjeto)){
            throw new \Exception("Campo Natureza do Objeto nao informado", 400);
        }

        if(in_array($data->l20_tipoprocesso, [100, 101, 102, 103])){
            if(empty($data->l20_razao)){
                throw new \Exception("Campo Razão não informado", 400);
            }

            if(empty($data->l20_justificativa)){
                throw new \Exception("Campo Justificativa não informado", 400);
            }

            if(empty($data->l20_tipoprocesso)){
                throw new \Exception("Campo Tipo de Procedimento nao informado", 400);
            }

            if(empty($data->respAutocodigo)){
                throw new \Exception("Responsável pela condução do processo não informado", 400);
            }

            if($data->l20_naturezaobjeto == 1 && empty($data->respObrascodigo)){
                throw new \Exception("Responsável pelos orçamentos, obras e serviços não informado", 1);
            }
        }

        if(in_array($data->l20_tipoprocesso, [48, 49, 50, 52, 53, 54])){
            if(empty($data->respConducodigo)){
                throw new \Exception("Responsável pela condução do processo não informado", 400);
            }
            if(empty($data->respAutocodigo)){
                throw new \Exception("Responsável pela abertura do processo não informado", 400);
            }
            if(empty($data->respEditalcodigo)){
                throw new \Exception("Responsável pela emissão do edital não informado", 400);
            }
            if($data->l20_naturezaobjeto == 1 && empty($data->respObrascodigo)){
                throw new \Exception("Responsável pelos orçamentos, obras e serviços não informado", 400);
            }
            if($data->l20_tipoprocesso == 54 && empty($data->respAvaliBenscodigo)){
                throw new \Exception("Responsável pela avaliação de bens não informado", 400);
            }
        }

        if(in_array($data->l20_naturezaobjeto, [1, 7]) && empty($data->l20_regimexecucao)){
            throw new \Exception("Campo Regime da Execução não selecionado", 400);
        }

        if(in_array($oTipoProcedimento->l03_pctipocompratribunal, [52, 53])){
            $totalPregao = $this->decretoPregaoRepository->getTotalDecretoPregao();
            if(empty($totalPregao)){
                throw new \Exception("Não há decreto pregão", 400);
            }
        }

        $aDataModalidade = $this->pccfLicitaParRepository->getModalidadeByParam($data->l20_codtipocom, $oDispensa->l20_anousu, $oDispensa->l20_instit);
        if(empty($aDataModalidade)){
            throw new \Exception("Verifique se esta configurado a numeração de licitação por modalidade", 400);
        }

        $aDataNumGeral = $this->pccfLicitaNumRepository->getEdital($oDispensa->l20_instit, $oDispensa->l20_anousu);
        if(empty($aDataNumGeral)){
            throw new \Exception("Verifique se esta configurado a numeração de licitação por edital", 400);
        }

        $aDataNumEdital = $this->pccfEditalNumRepository->getNroEdital($oDispensa->l20_instit, $oDispensa->l20_anousu);
        if(empty($aDataNumEdital) && in_array($data->l20_tipoprocesso, [48, 49, 50, 52, 53, 54])){
            throw new \Exception("Verifique se esta configurado a numeração do edital por licitação", 400);
        }

        if($data->l20_codtipocom == 99){
            throw new \Exception("Selecione uma modalidade para a licitação", 400);
        }

        if(!in_array($data->l20_tipoprocesso, [100, 101, 102, 103]) && $data->l20_leidalicitacao == 1 && empty($data->l20_mododisputa)){
            throw new \Exception("Selecione um modo de disputa para a licitação", 400);
        }

        if($data->l20_numero != $oDispensa->l20_numero){
            $aDataVerifyLicitaModalidade = $this->pccfLicitaParRepository->getModalidadeLicita($data->l20_codtipocom, $data->l20_numero, $oDispensa->l20_anousu, $oDispensa->l20_instit);
            if(!empty($aDataVerifyLicitaModalidade)){
                throw new \Exception("Ja existe licitação numero {$data->l20_numero}.Verificar o cadastro por modalidade", 400);
            }
        }

        if($data->l20_edital != $oDispensa->l20_edital){
            $aDataVerifyLicitaEdital = $this->pccfLicitaNumRepository->getEditalLicita($oDispensa->l20_instit, $oDispensa->l20_anousu, $data->l20_edital);
            if(!empty($aDataVerifyLicitaEdital)){
                throw new \Exception("Ja existe licitação numero {$data->l20_edital}.Verificar numeração por edital", 400);
            }
        }

        if(!empty($data->l20_nroedital) && $data->l20_nroedital != $oDispensa->l20_nroedital){
            $aDataVerifyLicitaNroEdital = $this->pccfEditalNumRepository->getNroEditalLicita($oDispensa->l20_instit, $oDispensa->l20_anousu, $data->l20_nroedital);
            if(!empty($aDataVerifyLicitaNroEdital)){
                throw new \Exception("Ja existe edital da licitação com numero {$data->l20_nroedital}.Verificar numeração por edital", 400);
            }
        }

        if(!empty($data->l20_dtpubratificacao)){
            $aDataEnceramento = $this->condataconfRepository->getEncerramentoPatrimonial($oDispensa->l20_anousu, $oDispensa->l20_instit);
            $dataEncerramentoPartrimonial = \DateTime::createFromFormat('Y-m-d', $aDataEnceramento->c99_datapat);
            if($dataEncerramentoPartrimonial >= $data->l20_dtpubratificacao){
                throw new \Exception("O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte");
            }
        }

        $oCriteriosAdjudicacao = $this->licilicitemRepository->getCriterioAdjudicacaoByProcessoDecompra($oDispensa->l20_codigo);
        if(
            !empty($oCriteriosAdjudicacao->pluck('pc80_criterioadjudicacao')->toArray())
            && !empty($data->l20_criterioadjudicacao)
            && !in_array($data->l20_criterioadjudicacao, $oCriteriosAdjudicacao->pluck('pc80_criterioadjudicacao')->toArray())
        ){
            throw new \Exception("Critério de adjudicação não corresponde aos itens de compras já inseridos");
        }

        if(in_array($data->l20_tipoprocesso, [52, 53]) && $data->l20_leidalicitacao == 2){
            $aDataVerifyPregao = $this->licPregaoCgmRepository->getModalidadePregao($data->l20_equipepregao);
            if(empty($aDataVerifyPregao)){
                throw new \Exception("Para as modalidades Pregao presencial e Pregao eletronico necessario\nque a Comissao de Licitacao tenham os tipos Pregoeiro e Membro da Equipe de Apoio", 400);
            }
        } else if(in_array($data->l20_tipoprocesso, [48, 49, 50]) && $data->l20_leidalicitacao == 2){
            $aDataVerifyOutros = $this->licPregaoCgmRepository->getModalidadeOutros($data->l20_equipepregao);
            if(empty($aDataVerifyOutros)){
                throw new \Exception("Para as modalidades Tomada de Preços, Concorrencia e Convite  necessario\nque a Comissao de Licitacao tenham os tipos Secretario, Presidente e Membro da Equipe de Apoio", 400);
            }
        }

        if(in_array($data->l20_tipoprocesso, [100, 101, 102, 103])){
            if(
                !empty($data->l20_dataaberproposta)
                && !empty($data->l20_dataaber)
                && DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_dataaberproposta . ' ' . $data->l20_horaaberturaprop)) < DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_dataaber . ' ' . $data->l20_horaaber))
            ){
                throw new \Exception("A data informada no campo Abertura das Propostas deve ser  superior a Data Edital/Convite", 400);
            }

            if(
                !empty($data->l20_datacria)
                && !empty($data->l20_dataaber)
                && DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_dataaber . ' ' . $data->l20_horaaber)) < DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_datacria . ' ' . $data->l20_horacria))
            ){
                throw new \Exception("A data inserida no campo Data Emis/Alt Edital/Convite deverá ser maior ou igual a data inserida no campo Data Abertura Proc. Adm", 400);
            }

            if(
                !empty($data->l20_dataaberproposta)
                && !empty($data->l20_datacria)
                && DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_dataaberproposta . ' ' . $data->l20_horaaberturaprop)) < DateTime::createFromFormat('Y-m-d H:i:s', ($data->l20_datacria . ' ' . $data->l20_horacria))
            ){
                throw new \Exception("A data inserida no campo Data Abertura Proposta deverá ser maior ou igual a data inserida no campo Data Abertura Proc. Adm", 400);
            }
        }

        $oData = new Liclicita([
            'l20_codigo'                     => $oDispensa->l20_codigo,
            'l20_codepartamento'             => !empty($data->l20_codepartamento) ? $data->l20_codepartamento : null,
            'l20_leidalicitacao'             => !empty($data->l20_leidalicitacao) ? $data->l20_leidalicitacao : null,
            'l20_codtipocom'                 => !empty($data->l20_codtipocom) ? $data->l20_codtipocom : null,
            'l20_anousu'                     => !empty($oDispensa->l20_anousu) ? $oDispensa->l20_anousu : null,
            'l20_id_usucria'                 => !empty($data->id_usuario) ? $data->id_usuario : null,
            'l20_instit'                     => !empty($oDispensa->l20_instit) ? $oDispensa->l20_instit : null,
            'l20_numero'                     => !empty($data->l20_numero) ? $data->l20_numero : null,
            'l20_edital'                     => !empty($data->l20_edital) ? $data->l20_edital : null,
            'l20_nroedital'                  => !empty($data->l20_nroedital) ? $data->l20_nroedital : null,
            'l20_dispensaporvalor'           => !empty($data->l20_dispensaporvalor) ? $data->l20_dispensaporvalor : null,
            'l20_naturezaobjeto'             => !empty($data->l20_naturezaobjeto) ? $data->l20_naturezaobjeto : null,
            'l20_regimexecucao'              => !empty($data->l20_regimexecucao) ? $data->l20_regimexecucao : null,
            'l20_procadmin'                  => !empty($data->l20_procadmin) && !empty($data->lprocsis) && $data->lprocsis == 'n'? $data->l20_procadmin : null,
            'l20_datacria'                   => !empty($data->l20_datacria)? date('Y-m-d', strtotime($data->l20_datacria)) : null,
            'l20_recdocumentacao'            => !empty($data->l20_datacria)? date('Y-m-d', strtotime($data->l20_datacria)) : null,
            'l20_dataaber'                   => !empty($data->l20_dataaber)? date('Y-m-d', strtotime($data->l20_dataaber)) : null,
            'l20_dataaberproposta'           => !empty($data->l20_dataaberproposta)? date('Y-m-d', strtotime($data->l20_dataaberproposta)) : null,
            'l20_dataencproposta'            => !empty($data->l20_dataencproposta)? date('Y-m-d', strtotime($data->l20_dataencproposta)) : null,
            'l20_horacria'                   => !empty($data->l20_horacria)? date('H:i', strtotime($data->l20_horacria)) : null,
            'l20_horaaberturaprop'           => !empty($data->l20_horaaberturaprop)? date('H:i', strtotime($data->l20_horaaberturaprop)) : null,
            'l20_horaencerramentoprop'       => !empty($data->l20_horaencerramentoprop)? date('H:i', strtotime($data->l20_horaencerramentoprop)) : null,
            'l20_horaaber'                   => !empty($data->l20_horaaber)? date('H:i', strtotime($data->l20_horaaber)) : null,
            'l20_tipojulg'                   => !empty($data->l20_tipojulg) ? $data->l20_tipojulg : null,
            'l20_tipliticacao'               => !empty($data->l20_tipliticacao) ? $data->l20_tipliticacao : null,
            'l20_tipnaturezaproced'          => !empty($data->l20_tipnaturezaproced) ? $data->l20_tipnaturezaproced : null,
            'l20_criterioadjudicacao'        => !empty($data->l20_criterioadjudicacao) ? $data->l20_criterioadjudicacao : null,
            'l20_amparolegal'                => !empty($data->l20_amparolegal) ? $data->l20_amparolegal : null,
            'l20_categoriaprocesso'          => !empty($data->l20_categoriaprocesso) ? $data->l20_categoriaprocesso : null,
            'l20_receita'                    => !empty($data->l20_receita) ? $data->l20_receita : null,
            'l20_objeto'                     => !empty($data->l20_objeto) ? $data->l20_objeto : null,
            'l20_justificativa'              => !empty($data->l20_justificativa) ? $data->l20_justificativa : null,
            'l20_razao'                      => !empty($data->l20_razao) ? $data->l20_razao : null,
            'l20_lances'                     => !empty($data->l20_lances) ? $data->l20_lances : false,
            'l20_justificativapncp'          => !empty($data->l20_justificativapncp) ? $data->l20_justificativapncp : null,
            'l20_equipepregao'               => isset($data->l20_equipepregao) && !is_null($data->l20_equipepregao) ? $data->l20_equipepregao : 0,
            'l20_execucaoentrega'            => !empty($data->l20_execucaoentrega) ? $data->l20_execucaoentrega : null,
            'l20_diames'                     => !empty($data->l20_diames) ? $data->l20_diames : null,
            'l20_numeroconvidado'            => !empty($data->l20_numeroconvidado) ? $data->l20_numeroconvidado : null,
            'l20_mododisputa'                => !empty($data->l20_mododisputa) ? $data->l20_mododisputa : null,
            'l20_critdesempate'              => !empty($data->l20_critdesempate) ? $data->l20_critdesempate : null,
            'l20_destexclusiva'              => !empty($data->l20_destexclusiva) ? $data->l20_destexclusiva : null,
            'l20_subcontratacao'             => !empty($data->l20_subcontratacao) ? $data->l20_subcontratacao : null,
            'l20_limitcontratacao'           => !empty($data->l20_limitcontratacao) ? $data->l20_limitcontratacao : null,
            'l20_condicoespag'               => !empty($data->l20_condicoespag) ? $data->l20_condicoespag : null,
            'l20_clausulapro'                => !empty($data->l20_clausulapro) ? $data->l20_clausulapro : null,
            'l20_aceitabilidade'             => !empty($data->l20_aceitabilidade) ? $data->l20_aceitabilidade : null,
            'l20_validadeproposta'           => !empty($data->l20_validadeproposta) ? $data->l20_validadeproposta : null,
            'l20_prazoentrega'               => !empty($data->l20_prazoentrega) ? $data->l20_prazoentrega : null,
            'l20_local'                      => !empty($data->l20_local) ? $data->l20_local : null,
            'l20_inversaofases'              => !empty($data->l20_inversaofases) ? $data->l20_inversaofases : null,
            'l20_descrcriterio'              => !empty($data->l20_descrcriterio) ? $data->l20_descrcriterio : null,
        ]);

        switch ($data->l20_tipoprocesso) {
            case 101:
                $oData->l20_tipoprocesso = 1;
                break;
            case 100:
                $oData->l20_tipoprocesso = 2;
                break;
            case 102:
                $oData->l20_tipoprocesso = 3;
                break;
            case 103:
                $oData->l20_tipoprocesso = 4;
                break;
        }
        
        // if($oDispensa->l20_licsituacao == 0){
        //     if(($data->anousu <= date('Y'))){
        //         $oData->l20_cadinicial = 1;
        //     } else if(!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1 && $data->l20_dispensaporvalor == 'f'){
        //         $oData->l20_cadinicial = 1;
        //     } else if((empty($data->l20_leidalicitacao) || $data->l20_leidalicitacao == 2) || (!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1 && $data->l20_dispensaporvalor == 't')){
        //         $oData->l20_cadinicial = 0;
        //     }
        // }

        if(!empty($data->l20_tipoprocesso) && in_array($data->l20_tipoprocesso, [101])){
            $oData->l20_mododisputa = 4;
        } else if(!empty($data->l20_tipoprocesso) && in_array($data->l20_tipoprocesso, [100, 102, 103])){
            $oData->l20_mododisputa = 5;
        }

        if(!empty($data->l20_tipnaturezaproced)){

            if($data->l20_tipnaturezaproced == 2 && $data->l20_tipoprocesso == 100){
                $oData->l20_tipoprocesso = 5;
            } else if($data->l20_tipnaturezaproced == 2 && $data->l20_tipoprocesso == 101){
                $oData->l20_tipoprocesso = 6;
            }

            if(
                $data->l20_tipnaturezaproced == 1 && $oTipoProcedimento->l03_pctipocompratribunal == 101
                || $data->l20_tipnaturezaproced == 1 && $oTipoProcedimento->l03_pctipocompratribunal == 100
            ){
                $oData->l20_usaregistropreco = false;
                $oData->l20_formacontroleregistropreco = 0;
            } else if($data->l20_tipnaturezaproced == 2 && $oTipoProcedimento->l03_pctipocompratribunal == 101){
                if(!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao != 1){
                    throw new \Exception("As Dispensas só poderão ser feitas por Registro de Preço se a legislação que rege o processo for a Lei 14133/2021", 400);
                }
                $oData->l20_usaregistropreco = true;
                $oData->l20_formacontroleregistropreco = 1;
            } else if($data->l20_tipnaturezaproced == 2 && $oTipoProcedimento->l03_pctipocompratribunal == 100){
                if(!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao != 1){
                    throw new \Exception("As Inexigibilidades só poderão ser feitas por Registro de Preço se a legislação que rege o processo for a Lei 14133/2021", 400);
                }
                $oData->l20_usaregistropreco = true;
                $oData->l20_formacontroleregistropreco = 1;
            }else if($data->l20_tipnaturezaproced == 2){
                $oData->l20_usaregistropreco = true;
                $oData->l20_formacontroleregistropreco = 1;
            }
        }

        if(empty($oData->l20_procadmin)){
            $oData->l20_procadmin = null;
        }

        DB::beginTransaction();
        try{
            $oData = $this->liclicitaRepository->update(
                $oDispensa->l20_codigo,
                $oData->toArray()
            );

            if(!empty($data->l34_protprocesso)){
                $oResponseProcesso = $this->updateLicLicitaProcService->execute((object)[
                    'l34_protprocesso' => $data->l34_protprocesso,
                    'l20_codigo' => $oData->l20_codigo,
                ]);

                if($oResponseProcesso['status'] != 200){
                    throw new \Exception($oResponseProcesso['message'], 400);
                }
            } else {
                $this->deleteLicLicitaProcService->execute((object)[
                    'l20_codigo' => $oData->l20_codigo,
                ]);
            }

            if(!empty($data->respConducodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respConducodigo,
                    'l31_tipo'       => 5,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respConducodigo ?? null
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $oDispensa->respConducodigo,
                    'l31_tipo'       => 5,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respConducodigo ?? null
                ]);
            }

            if(!empty($data->respAutocodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respAutocodigo,
                    'l31_tipo'      => 1,
                    'l31_licitacao' => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAutocodigo ?? null
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $oDispensa->respAutocodigo,
                    'l31_tipo'      => 1,
                    'l31_licitacao' => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAutocodigo ?? null
                ]);
            }

            if(!empty($data->respEditalcodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respEditalcodigo,
                    'l31_tipo'       => 2,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respEditalcodigo ?? null
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $oDispensa->respEditalcodigo,
                    'l31_tipo'       => 2,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respEditalcodigo ?? null
                ]);
            }

            if(!empty($data->respObrascodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respObrascodigo,
                    'l31_tipo'       => 10,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respObrascodigo ?? null
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $oDispensa->respObrascodigo,
                    'l31_tipo'       => 10,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respObrascodigo ?? null
                ]);
            }

            if(!empty($data->respAvaliBenscodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respAvaliBenscodigo,
                    'l31_tipo'       => 9,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAvaliBenscodigo ?? null
                ]);
                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $oDispensa->respAvaliBenscodigo,
                    'l31_tipo'       => 9,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAvaliBenscodigo ?? null
                ]);
            }

            if(!empty($data->respAbertcodigo)){
                $oResponseCGM = $this->updateLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respAbertcodigo,
                    'l31_tipo'       => 1,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAbertcodigo
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $oDispensa->respAbertcodigo,
                    'l31_tipo'       => 1,
                    'l31_licitacao'  => $oData->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respAbertcodigo
                ]);
            }

            // $oResultSituacao = $this->updateLicLicitaSituacaoService->execute((object)[
            //     'id_usuario' => $data->id_usuario,
            //     'l20_codigo' => $oData->l20_codigo,
            //     'datausu'    => $data->datausu,
            // ]);

            // if($oResultSituacao['status'] != 200){
            //     throw new \Exception($oResultSituacao['message'], 400);
            // }

            $l20_edital = $oData->l20_edital;
            $l20_numero = $oData->l20_numero;
            $l20_nroedital = $oData->l20_nroedital;

            if($oParam->l12_numeracaomanual){
                do{
                    if($data->l20_edita == $oDispensa->l20_edital){
                        break;
                    }
                    $l20_edital = $l20_edital + 1;
                    $oLicitacao = $this->liclicitaRepository->getNumeroByEdital($oDispensa->l20_anousu, $oDispensa->l20_instit, $l20_edital);
                    if(empty($oLicitacao)){
                        $this->pccfLicitaNumRepository->updateByInstitAnoUsu(
                            $oDispensa->l20_instit,
                            $oDispensa->l20_anousu,
                            [
                                'l24_numero' => $l20_edital - 1
                            ]
                        );
                        break;
                    }
                } while(1);

                do{
                    if($data->l20_numero == $oDispensa->l20_numero){
                        break;
                    }
                    $l20_numero = $l20_numero + 1;
                    $oLicitacao = $this->liclicitaRepository->getProcessoByNumero($oDispensa->l20_anousu, $oDispensa->l20_instit, $oData->l20_codtipocom, $l20_numero);
                    if(empty($oLicitacao)){
                        $this->pccfLicitaParRepository->updateByInstitAnoUsu(
                            $oDispensa->l20_instit,
                            $oDispensa->l20_anousu,
                            $data->l20_codtipocom,
                            [
                                'l25_numero' => $l20_numero - 1
                            ]
                        );
                        break;
                    }
                } while(1);

                do{
                    if(is_null($data->l20_nroedital) || $data->l20_nroedital == $oDispensa->l20_nroedital){
                        break;
                    }
                    $l20_nroedital = $l20_nroedital + 1;
                    $oLicitacao = $this->liclicitaRepository->getEditalByNumeroEdital($oDispensa->l20_anousu, $oDispensa->l20_instit, $l20_nroedital);
                    if(empty($oLicitacao) && $oDispensa->l20_anousu >= 2020 && !is_null($data->l20_nroedital)){
                        $this->pccfEditalNumRepository->save((new PccfEditalNum([
                            'l47_numero' => $l20_nroedital - 1,
                            'l47_instit' => $oDispensa->l20_instit,
                            'l47_anousu' => $oDispensa->l20_anousu,
                            'l47_timestamp' => date('Y-m-d H:i:s')
                        ])));
                        break;
                    }
                } while(1);
            } else {
                if($data->l20_numero != $oDispensa->l20_numero){
                    $oPccfLicitaPar = $this->pccfLicitaParRepository->getNumeracao($data->l20_codtipocom, $oDispensa->l20_anousu);
                    $this->pccfLicitaParRepository->updateByInstitAnoUsu(
                        $oDispensa->l20_instit,
                        $oDispensa->l20_anousu,
                        $data->l20_codtipocom,
                        [
                            'l25_numero' => $oPccfLicitaPar->l25_numero + 1
                        ]
                    );
                }

                if($data->l20_edita != $oDispensa->l20_edital){
                    $oPccfLicitaNum = $this->pccfLicitaNumRepository->getEdital($oDispensa->l20_instit, $oDispensa->l20_anousu);
                    $this->pccfLicitaNumRepository->updateByInstitAnoUsu(
                        $oDispensa->l20_instit,
                        $oDispensa->l20_anousu,
                        [
                            'l24_numero' => $oPccfLicitaNum->l24_numero + 1
                        ]
                    );
                }

                if($oDispensa->l20_anousu >= 2020 && !empty($data->l20_nroedital) && $data->l20_nroedital != $oDispensa->l20_nroedital){
                    $oPccfEditalNum = $this->pccfEditalNumRepository->getNroEdital($oDispensa->l20_instit, $oDispensa->l20_anousu);
                    $this->pccfEditalNumRepository->save((new PccfEditalNum([
                        'l47_numero' => $oPccfEditalNum->l47_numero + 1,
                        'l47_instit' => $oDispensa->l20_instit,
                        'l47_anousu' => $oDispensa->l20_anousu,
                        'l47_timestamp' => date('Y-m-d H:i:s')
                    ])));
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Licitação salva com sucesso',
                'data' => [
                    'licitacao' => $oData->toArray()
                ]
            ];
        } catch(\Throwable $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }

}
