<?php

namespace App\Services\LicLicita;

use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\PccfEditalNum;
use App\Repositories\Contabilidade\CondataconfRepository;
use App\Repositories\Patrimonial\Licitacao\CflicitaRepository;
use App\Repositories\Patrimonial\Licitacao\DecretoPregaoRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaParamRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LicPregaoCgmRepository;
use App\Repositories\Patrimonial\Licitacao\PccfEditalNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaParRepository;
use App\Services\LicComissaoCgm\InsertLicComissaoCgmService;
use App\Services\LicLicitaProc\InsertLicLicitaProcService;
use App\Services\LicLicitaSituacao\InsertLicLicitaSituacaoService;
use DateTime;
use Illuminate\Database\Capsule\Manager as DB;

class InsertLicLicitaService{

    private LiclicitaRepository $liclicitaRepository;
    private CflicitaRepository $cflicitaRepository;
    private LicLicitaParamRepository $licLicitaParamRepository;
    private DecretoPregaoRepository $decretoPregaoRepository;
    private PccfLicitaParRepository $pccfLicitaParRepository;
    private PccfLicitaNumRepository $pccfLicitaNumRepository;
    private PccfEditalNumRepository $pccfEditalNumRepository;
    private CondataconfRepository $condataconfRepository;
    private LicPregaoCgmRepository $licPregaoCgmRepository;

    private InsertLicLicitaProcService $insertLicLicitaProcService;
    private InsertLicComissaoCgmService $insertLicComissaoCgmService;
    private InsertLicLicitaSituacaoService $insertLicLicitaSituacaoService;

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

        $this->insertLicLicitaProcService = new InsertLicLicitaProcService();
        $this->insertLicComissaoCgmService = new InsertLicComissaoCgmService();
        $this->insertLicLicitaSituacaoService = new InsertLicLicitaSituacaoService();
    }

    public function execute(object $data){
        $oTipoProcedimento = $this->cflicitaRepository->getDadosTipoProcesso($data->l20_codtipocom);
        $oParam = $this->licLicitaParamRepository->getDados($data->instit);
        $data->l20_tipoprocesso = $oTipoProcedimento->l03_pctipocompratribunal;

        if(!is_null($data->l20_edital) && ($data->l20_edital <= 0 || trim($data->l20_edital) == '')){
            throw new \Exception("O número do processo informado para o processo não pode ser 0!", 400);
        }

        if(!is_null($data->l20_numero) && ($data->l20_numero <= 0 || trim($data->l20_numero) == '')){
            throw new \Exception("O número informado para o processo não pode ser 0!", 400);
        }

        if(!is_null($data->l20_nroedital) && is_numeric($data->l20_nroedital) && $data->l20_nroedital <= 0){
            throw new \Exception("O número do edital informado não pode ser 0!", 400);
        }

        $aTipoLicNatProc = [50, 48, 49, 53, 52, 54];
        if($oParam->l12_numeracaomanual){
            if(!empty($data->l20_edital)){
                $oNumeracao = $this->liclicitaRepository->getNumeroByEdital($data->anousu, $data->instit, $data->l20_edital);
                if(!empty($oNumeracao)){
                    throw new \Exception("O número informado já está em uso no exercício atual pelo <b>Processo</b> sequencial <b>". $oNumeracao->l20_codigo ."</b> . Por favor, escolha um número diferente ou revise o número inserido.", 400);
                }
            }

            if(!empty($data->l20_numero)){
                $oProcesso = $this->liclicitaRepository->getProcessoByNumero($data->anousu, $data->instit, $data->l20_codtipocom, $data->l20_numero);
                if(!empty($oProcesso)){
                    throw new \Exception("A <b>Numeração</b> informada já está em uso para a modalidade selecionada no processo sequencial <b>". $oProcesso->l20_codigo ."</b> deste exercício. Por favor, escolha um número diferente ou revise o número inserido.", 400);
                }
            }

            if(!empty($data->l20_nroedital)){
                $oEdital = $this->liclicitaRepository->getEditalByNumeroEdital($data->anousu, $data->instit, $data->l20_nroedital);
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
            // if($data->l20_tipoprocesso == 54 && empty($data->respAvaliBenscodigo)){
            //     throw new \Exception("Responsável pela avaliação de bens não informado", 400);
            // }
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

        $aDataModalidade = $this->pccfLicitaParRepository->getModalidadeByParam($data->l20_codtipocom, $data->anousu, $data->instit);
        if(empty($aDataModalidade)){
            throw new \Exception("Verifique se esta configurado a numeração de licitação por modalidade", 400);
        }

        $aDataNumGeral = $this->pccfLicitaNumRepository->getEdital($data->instit, $data->anousu);
        if(empty($aDataNumGeral)){
            throw new \Exception("Verifique se esta configurado a numeração de licitação por edital", 400);
        }

        $aDataNumEdital = $this->pccfEditalNumRepository->getNroEdital($data->instit, $data->anousu);
        if(empty($aDataNumEdital) && in_array($data->l20_tipoprocesso, [48, 49, 50, 52, 53, 54])){
            throw new \Exception("Verifique se esta configurado a numeração do edital por licitação", 400);
        }

        if($data->l20_codtipocom == 99){
            throw new \Exception("Selecione uma modalidade para a licitação", 400);
        }

        if(!in_array($data->l20_tipoprocesso, [100, 101, 102, 103]) && $data->l20_leidalicitacao == 1 && empty($data->l20_mododisputa)){
            throw new \Exception("Selecione um modo de disputa para a licitação", 400);
        }

        $aDataVerifyLicitaModalidade = $this->pccfLicitaParRepository->getModalidadeLicita($data->l20_codtipocom, $data->l20_numero, $data->anousu, $data->instit);
        if(!empty($aDataVerifyLicitaModalidade)){
            throw new \Exception("Ja existe licitação numero {$data->l20_numero}.Verificar o cadastro por modalidade", 400);
        }

        $aDataVerifyLicitaEdital = $this->pccfLicitaNumRepository->getEditalLicita($data->instit, $data->anousu, $data->l20_edital);
        if(!empty($aDataVerifyLicitaEdital)){
            throw new \Exception("Ja existe licitação numero {$data->l20_edital}.Verificar numeração por edital", 400);
        }

        if(!empty($data->l20_nroedital)){
            $aDataVerifyLicitaNroEdital = $this->pccfEditalNumRepository->getNroEditalLicita($data->instit, $data->anousu, $data->l20_nroedital);
            if(!empty($aDataVerifyLicitaNroEdital)){
                throw new \Exception("Ja existe edital da licitação com numero {$data->l20_nroedital}.Verificar numeração por edital", 400);
            }
        }

        if(!empty($data->l20_dtpubratificacao)){
            $aDataEnceramento = $this->condataconfRepository->getEncerramentoPatrimonial($data->anousu, $data->instit);
            $dataEncerramentoPartrimonial = \DateTime::createFromFormat('Y-m-d', $aDataEnceramento->c99_datapat);
            if($dataEncerramentoPartrimonial >= $data->l20_dtpubratificacao){
                throw new \Exception("O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte");
            }
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

        $oData = new Liclicita(array_filter([
            'l20_codigo'                     => $this->liclicitaRepository->getNextVal(),
            'l20_codepartamento'             => $data->l20_codepartamento,
            'l20_leidalicitacao'             => $data->l20_leidalicitacao,
            'l20_codtipocom'                 => $data->l20_codtipocom,
            'l20_anousu'                     => $data->anousu,
            'l20_id_usucria'                 => $data->id_usuario,
            'l20_instit'                     => $data->instit,
            'l20_numero'                     => $data->l20_numero,
            'l20_edital'                     => $data->l20_edital,
            'l20_nroedital'                  => $data->l20_nroedital,
            'l20_dispensaporvalor'           => $data->l20_dispensaporvalor,
            'l20_naturezaobjeto'             => $data->l20_naturezaobjeto,
            'l20_regimexecucao'              => $data->l20_regimexecucao,
            'l20_procadmin'                  => !empty($data->l20_procadmin) && !empty($data->lprocsis) && $data->lprocsis == 'n'? $data->l20_procadmin : null,
            'l20_datacria'                   => !empty($data->l20_datacria)? date('Y-m-d', strtotime($data->l20_datacria)) :'',
            'l20_recdocumentacao'            => !empty($data->l20_dataaberproposta)? date('Y-m-d', strtotime($data->l20_dataaberproposta)) :'',
            'l20_dataaber'                   => !empty($data->l20_dataaber)? date('Y-m-d', strtotime($data->l20_dataaber)) :'',
            'l20_dataaberproposta'           => !empty($data->l20_dataaberproposta)? date('Y-m-d', strtotime($data->l20_dataaberproposta)) :'',
            'l20_dataencproposta'            => !empty($data->l20_dataencproposta)? date('Y-m-d', strtotime($data->l20_dataencproposta)) :'',
            'l20_horacria'                   => !empty($data->l20_horacria)? date('H:i', strtotime($data->l20_horacria)) :'',
            'l20_horaaberturaprop'           => !empty($data->l20_horaaberturaprop)? date('H:i', strtotime($data->l20_horaaberturaprop)) :'',
            'l20_horaaber'                   => !empty($data->l20_horaaber)? date('H:i', strtotime($data->l20_horaaber)) :'',
            'l20_horaencerramentoprop'       => !empty($data->l20_horaencerramentoprop)? date('H:i', strtotime($data->l20_horaencerramentoprop)) :'',
            'l20_tipojulg'                   => $data->l20_tipojulg,
            'l20_tipliticacao'               => $data->l20_tipliticacao,
            'l20_tipnaturezaproced'          => $data->l20_tipnaturezaproced,
            'l20_criterioadjudicacao'        => $data->l20_criterioadjudicacao,
            'l20_amparolegal'                => $data->l20_amparolegal,
            'l20_categoriaprocesso'          => $data->l20_categoriaprocesso,
            'l20_receita'                    => $data->l20_receita,
            'l20_objeto'                     => $data->l20_objeto,
            'l20_justificativa'              => $data->l20_justificativa,
            'l20_razao'                      => $data->l20_razao,
            'l20_tipoprocesso'               => null,
            'l20_lances'                     => !empty($data->l20_lances) ? $data->l20_lances : false,
            'l20_licsituacao'                => 0,
            'l20_justificativapncp'          => !empty($data->l20_justificativapncp) ? $data->l20_justificativapncp : null,
            'l20_equipepregao'               => isset($data->l20_equipepregao) && !is_null($data->l20_equipepregao) ? $data->l20_equipepregao : 0,
            'l20_execucaoentrega'            => $data->l20_execucaoentrega ?? null,
            'l20_diames'                     => $data->l20_diames ?? null,
            'l20_numeroconvidado'            => $data->l20_numeroconvidado ?? null,
            'l20_mododisputa'                => $data->l20_mododisputa ?? null,
            'l20_critdesempate'              => $data->l20_critdesempate ?? null,
            'l20_destexclusiva'              => $data->l20_destexclusiva ?? null,
            'l20_subcontratacao'             => $data->l20_subcontratacao ?? null,
            'l20_limitcontratacao'           => $data->l20_limitcontratacao ?? null,
            'l20_condicoespag'               => $data->l20_condicoespag ?? null,
            'l20_clausulapro'                => $data->l20_clausulapro ?? null,
            'l20_aceitabilidade'             => $data->l20_aceitabilidade ?? null,
            'l20_validadeproposta'           => $data->l20_validadeproposta ?? null,
            'l20_prazoentrega'               => $data->l20_prazoentrega ?? null,
            'l20_local'                      => $data->l20_local ?? null,
            'l20_inversaofases'              => $data->l20_inversaofases ?? null,
            'l20_descrcriterio'              => $data->l20_descrcriterio ?? null,
            'l20_statusenviosicom' => (in_array($oTipoProcedimento->l03_pctipocompratribunal, [100, 101, 102, 103]) && $data->l20_dispensaporvalor == 'f') ? 4 : 1,
        ], function($value){
            return !is_null($value) && $value != '';
        }));

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

        if(($data->anousu <= date('Y'))){
            $oData->l20_cadinicial = 1;
        } else if(
            (!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1 && $data->l20_dispensaporvalor == 'f')
        ){
            $oData->l20_cadinicial = 1;
        } else if((empty($data->l20_leidalicitacao) || $data->l20_leidalicitacao == 2) || (!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 1 && $data->l20_dispensaporvalor == 't')){
            $oData->l20_cadinicial = 0;
        }

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
            } else if($data->l20_tipnaturezaproced == 2){
                $oData->l20_usaregistropreco = true;
                $oData->l20_formacontroleregistropreco = 1;
            }
        }

        if(empty($oData->l20_procadmin)){
            $oData->l20_procadmin = null;
        }

        DB::beginTransaction();
        try{
            $oData = $this->liclicitaRepository->save($oData);

            if(!empty($data->l34_protprocesso) && !empty($oData->l20_codigo)){
                $oResponseProcesso = $this->insertLicLicitaProcService->execute((object)[
                    'l34_protprocesso' => $data->l34_protprocesso,
                    'l20_codigo' => $oData->l20_codigo,
                ]);

                if($oResponseProcesso['status'] != 200){
                    throw new \Exception($oResponseProcesso['message'], 400);
                }
            }

            if(!empty($data->respConducodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respConducodigo,
                    'l31_tipo'      => 5,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            if(!empty($data->respAutocodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respAutocodigo,
                    'l31_tipo'      => 1,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            if(!empty($data->respEditalcodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respEditalcodigo,
                    'l31_tipo'      => 2,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            if(!empty($data->respObrascodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respObrascodigo,
                    'l31_tipo'      => 10,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            if(!empty($data->respAvaliBenscodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respAvaliBenscodigo,
                    'l31_tipo'      => 9,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            if(!empty($data->respAbertcodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respAbertcodigo,
                    'l31_tipo'      => 1,
                    'l31_licitacao' => $oData->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            }

            $oResultSituacao = $this->insertLicLicitaSituacaoService->execute((object)[
                'id_usuario' => $data->id_usuario,
                'l20_codigo' => $oData->l20_codigo,
                'datausu'    => $data->datausu,
            ]);

            if($oResultSituacao['status'] != 200){
                throw new \Exception($oResultSituacao['message'], 400);
            }

            $l20_edital = $oData->l20_edital;
            $l20_numero = $oData->l20_numero;
            $l20_nroedital = $oData->l20_nroedital;

            if($oParam->l12_numeracaomanual){
                do{
                    $l20_edital = $l20_edital + 1;
                    $oLicitacao = $this->liclicitaRepository->getNumeroByEdital($data->anousu, $data->instit, $l20_edital);
                    if(empty($oLicitacao)){
                        $this->pccfLicitaNumRepository->updateByInstitAnoUsu(
                            $data->instit,
                            $data->anousu,
                            [
                                'l24_numero' => $l20_edital - 1
                            ]
                        );
                        break;
                    }
                } while(1);

                do{
                    $l20_numero = $l20_numero + 1;
                    $oLicitacao = $this->liclicitaRepository->getProcessoByNumero($data->anousu, $data->instit, $oData->l20_codtipocom, $l20_numero);
                    if(empty($oLicitacao)){
                        $this->pccfLicitaParRepository->updateByInstitAnoUsu(
                            $data->instit,
                            $data->anousu,
                            $data->l20_codtipocom,
                            [
                                'l25_numero' => $l20_numero - 1
                            ]
                        );
                        break;
                    }
                } while(1);

                do{
                    if(is_null($data->l20_nroedital) && $data->l20_nroedital == ''){
                        break;
                    }
                    $l20_nroedital = $l20_nroedital + 1;
                    $oLicitacao = $this->liclicitaRepository->getEditalByNumeroEdital($data->anousu, $data->instit, $l20_nroedital);
                    if(empty($oLicitacao) && $data->anousu >= 2020 && !is_null($data->l20_nroedital)){
                        $this->pccfEditalNumRepository->save((new PccfEditalNum([
                            'l47_numero' => $l20_nroedital - 1,
                            'l47_instit' => $data->instit,
                            'l47_anousu' => $data->anousu,
                            'l47_timestamp' => date('Y-m-d H:i:s')
                        ])));
                        break;
                    }
                } while(1);
            } else {
                $oPccfLicitaPar = $this->pccfLicitaParRepository->getNumeracao($data->l20_codtipocom, $data->anousu);
                $this->pccfLicitaParRepository->updateByInstitAnoUsu(
                    $data->instit,
                    $data->anousu,
                    $data->l20_codtipocom,
                    [
                        'l25_numero' => $oPccfLicitaPar->l25_numero + 1
                    ]
                );

                $oPccfLicitaNum = $this->pccfLicitaNumRepository->getEdital($data->instit, $data->anousu);
                $this->pccfLicitaNumRepository->updateByInstitAnoUsu(
                    $data->instit,
                    $data->anousu,
                    [
                        'l24_numero' => $oPccfLicitaNum->l24_numero + 1
                    ]
                );

                if($data->anousu >= 2020 && !empty($data->l20_nroedital)){
                    $oPccfEditalNum = $this->pccfEditalNumRepository->getNroEdital($data->instit, $data->anousu);
                    $this->pccfEditalNumRepository->save((new PccfEditalNum([
                        'l47_numero' => $oPccfEditalNum->l47_numero + 1,
                        'l47_instit' => $data->instit,
                        'l47_anousu' => $data->anousu,
                        'l47_timestamp' => date('Y-m-d H:i:s')
                    ])));
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Licitação criada com sucesso',
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
