<?php
namespace App\Services\LicLicita;

use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\PccfEditalNum;
use App\Repositories\Configuracoes\ManutencaoLicitacaoRepository;
use App\Repositories\Contabilidade\CondataconfRepository;
use App\Repositories\Patrimonial\Licitacao\CflicitaRepository;
use App\Repositories\Patrimonial\Licitacao\CredenciamentoRepository;
use App\Repositories\Patrimonial\Licitacao\CredenciamentoSaldoRepository;
use App\Repositories\Patrimonial\Licitacao\DecretoPregaoRepository;
use App\Repositories\Patrimonial\Licitacao\EditalDocumentosRepository;
use App\Repositories\Patrimonial\Licitacao\LicAnexoPncpDocumentoRepository;
use App\Repositories\Patrimonial\Licitacao\LicAnexoPncpRepository;
use App\Repositories\Patrimonial\Licitacao\LicComissaoCgmRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LicLancEditalRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaParamRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaProcRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaSituacaoRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaWebRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicItemAnuRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use App\Repositories\Patrimonial\Licitacao\LicObrasRepository;
use App\Repositories\Patrimonial\Licitacao\LicPregaoCgmRepository;
use App\Repositories\Patrimonial\Licitacao\ObrasCodigosRepository;
use App\Repositories\Patrimonial\Licitacao\ObrasDadosComplementaresRepository;
use App\Repositories\Patrimonial\Licitacao\PccfEditalNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaNumRepository;
use App\Repositories\Patrimonial\Licitacao\PccfLicitaParRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamitemlicRepository;
use App\Repositories\Patrimonial\Licitacao\SituacaoItemCompraRepository;
use App\Repositories\Patrimonial\Licitacao\SituacaoItemLicRepository;
use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use DateTime;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteLicLicitaService{

    private LiclicitaRepository $liclicitaRepository;
    private CflicitaRepository $cflicitaRepository;
    private LicLicitaParamRepository $licLicitaParamRepository;
    private DecretoPregaoRepository $decretoPregaoRepository;
    private PccfLicitaParRepository $pccfLicitaParRepository;
    private PccfLicitaNumRepository $pccfLicitaNumRepository;
    private PccfEditalNumRepository $pccfEditalNumRepository;
    private CondataconfRepository $condataconfRepository;
    private LicPregaoCgmRepository $licPregaoCgmRepository;
    private EditalDocumentosRepository $editalDocumentosRepository;
    private LicAnexoPncpRepository $licAnexoPncpRepository;
    private LicAnexoPncpDocumentoRepository $licAnexoPncpDocumentosRepository;
    private ObrasCodigosRepository $obrasCodigosRepository;
    private ObrasDadosComplementaresRepository $obrasDadosComplementaresRepository;
    private LicLancEditalRepository $licLancEditalRepository;
    private LicLicitaWebRepository $licLicitaWebRepository;
    private LicLicitaSituacaoRepository $licLicitaSituacaoRepository;
    private LicComissaoCgmRepository $licComissaoCgmRepository;
    private SituacaoItemCompraRepository $situacaoItemCompraRepository;
    private SituacaoItemLicRepository $situacaoItemLicRepository;
    private CredenciamentoSaldoRepository $credenciamentoSaldoRepository;
    private CredenciamentoRepository $credenciamentoRepository;
    private LiclicitemLoteRepository $licilicitemloteRepository;
    private PcorcamitemlicRepository $pcorcamitemlicRepository;
    private LicLicItemAnuRepository $licLicItemAnuRepository;
    private LicilicitemRepository $licilicitemRepository;
    private LicLicitaLotesRepository $liclicitalotesRepository;
    private LicLicitaProcRepository $licLicitaProcRepository;
    private LicObrasRepository $licObrasRepository;
    private ManutencaoLicitacaoRepository $manutencaoLicitacaoRepository;

    private LiclicitemService $liclicitemService;
    private LiclicitemLoteService $liclicitemLoteService;

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
        $this->editalDocumentosRepository = new EditalDocumentosRepository();
        $this->licAnexoPncpRepository = new LicAnexoPncpRepository();
        $this->licAnexoPncpDocumentosRepository = new LicAnexoPncpDocumentoRepository();
        $this->obrasCodigosRepository = new ObrasCodigosRepository();
        $this->obrasDadosComplementaresRepository = new ObrasDadosComplementaresRepository();
        $this->licLancEditalRepository = new LicLancEditalRepository();
        $this->licLicitaWebRepository = new LicLicitaWebRepository();
        $this->licLicitaSituacaoRepository = new LicLicitaSituacaoRepository();
        $this->licComissaoCgmRepository = new LicComissaoCgmRepository();
        $this->situacaoItemCompraRepository = new SituacaoItemCompraRepository();
        $this->situacaoItemLicRepository = new SituacaoItemLicRepository();
        $this->credenciamentoSaldoRepository = new CredenciamentoSaldoRepository();
        $this->credenciamentoRepository = new CredenciamentoRepository();
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
        $this->pcorcamitemlicRepository = new PcorcamitemlicRepository();
        $this->licLicItemAnuRepository = new LicLicItemAnuRepository();
        $this->licilicitemRepository = new LicilicitemRepository();
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
        $this->licLicitaProcRepository = new LicLicitaProcRepository();
        $this->licObrasRepository = new LicObrasRepository();
        $this->manutencaoLicitacaoRepository = new ManutencaoLicitacaoRepository();

        $this->liclicitemService = new LiclicitemService();
        $this->liclicitemLoteService = new LiclicitemLoteService();
    }

    public function execute(object $data){
        $oDispensa = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo);
        $oParam = $this->licLicitaParamRepository->getDados($data->instit);

        DB::beginTransaction();
        try{

            if(!empty($oDispensa->has_fornecedor)){
                throw new \Exception("Usuário: A exclusão da licitação não é permitida, pois há fornecedores vinculados a ela. Verifique e ajuste antes de prosseguir.");
            }

            if($oParam->l12_numeracaomanual){
                $oNumeracaoEdital = $this->pccfLicitaNumRepository->getEditalByNumero($oDispensa->l20_instit, $oDispensa->l20_anousu, $oDispensa->l20_edital);
                if(!empty($oNumeracaoEdital)){
                    $this->pccfLicitaNumRepository->updateByInstitAnoUsuEdital(
                        $oDispensa->l20_instit,
                        $oDispensa->l20_anousu,
                        $oDispensa->l20_edital,
                        [
                            'l24_numero' => $oDispensa->l20_edital - 1
                        ]
                    );
                }

                $oNumeracao = $this->pccfLicitaParRepository->getNumeracaoByNumero($oDispensa->l20_anousu, $oDispensa->l20_numero, $oDispensa->l20_codtipocom);
                if(!empty($oNumeracao)){
                    $this->pccfLicitaParRepository->updateByInstitAnoUsuNumero(
                        $oDispensa->l20_anousu,
                        $oDispensa->l20_numero,
                        $oDispensa->l20_codtipocom,
                        [
                            'l25_numero' => $oDispensa->l20_numero - 1
                        ]
                    );
                }

                if(!empty($oDispensa->l20_nroedital)){
                    $oNumeracaoEdital = $this->pccfEditalNumRepository->getNroEditalByNroEdital($oDispensa->l20_instit, $oDispensa->l20_anousu, $oDispensa->l20_nroedital);
                    if(!empty($oNumeracaoEdital)){
                        $this->pccfEditalNumRepository->deleteNroEdital($oDispensa->l20_nroedital);
                    }
                }
            }

            $editalDocumentos = $this->editalDocumentosRepository->getEditalDocumentosByLicitacao($oDispensa->l20_codigo);
            if(!empty($editalDocumentos)){
                foreach ($editalDocumentos as $editaldocumento) {
                    $this->editalDocumentosRepository->delete($editaldocumento);
                }
            }

            $licAnexos = $this->licAnexoPncpRepository->getDadosByLicitacao($oDispensa->l20_codigo);
            if(!empty($licAnexos)){
                foreach ($licAnexos as $value) {
                    $licAnexosDocumentos = $this->licAnexoPncpDocumentosRepository->getDadosByLicAnexoPncp($value['l215_sequencial']);
                    if(!empty($licAnexosDocumentos)){
                        foreach ($licAnexosDocumentos as $valueDocumento) {
                            $valueAnexoDocumento = $this->licAnexoPncpDocumentosRepository->getByCodigo($valueDocumento['l216_sequencial']);
                            $this->licAnexoPncpDocumentosRepository->delete($valueAnexoDocumento);
                        }
                    }

                    $anexoPncp = $this->licAnexoPncpRepository->getByCodigo($value['l215_sequencial']);
                    $this->licAnexoPncpRepository->delete($anexoPncp);
                }
            }

            $codigoObras = $this->obrasCodigosRepository->getByLicLicita($oDispensa->l20_codigo);
            if(!empty($codigoObras)){
                foreach($codigoObras as $codigoObra){
                    $this->obrasDadosComplementaresRepository->delete($codigoObra->db151_codigoobra);
                    $this->obrasCodigosRepository->delete($codigoObra);
                }
            }

            $oLanceEdital = $this->licLancEditalRepository->getLicEditalByLicitacao($oDispensa->l20_codigo);
            if(!empty($oLanceEdital)){
                foreach($oLanceEdital as $edital){
                    $this->licLancEditalRepository->delete($edital);
                }
            }

            $oDataLicWeb = $this->licLicitaWebRepository->getDadosByFilter($oDispensa->l20_codigo);
            if(!empty($oDataLicWeb)){
                throw new \Exception("Licitação já publicada ou baixada. Não pode ser Excluida!", 400);
            }

            $oSituacao = $this->licLicitaSituacaoRepository->findSituacao($oDispensa->l20_codigo);
            if(!empty($oSituacao)){
                $this->licLicitaSituacaoRepository->deleteByLiclicita($oDispensa->l20_codigo);
            }

            $this->licComissaoCgmRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $this->situacaoItemLicRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $this->situacaoItemCompraRepository->deleteAllByLicitacao($oDispensa->l20_codigo);

            $this->credenciamentoSaldoRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $this->credenciamentoRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $this->pcorcamitemlicRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $this->licilicitemloteRepository->deleteByLicitacao($oDispensa->l20_codigo);
            
            $this->licLicItemAnuRepository->deleteByLicitacao($oDispensa->l20_codigo);
            
            $this->licilicitemRepository->deleteByLicitacao($oDispensa->l20_codigo);

            $aDataLotes = $this->liclicitalotesRepository->getLotesByLicLicita($oDispensa->l20_codigo);
            if(!empty($aDataLotes)){
                foreach($aDataLotes as $value){
                    $oLote = $this->liclicitalotesRepository->getLoteByCodigo($value['l24_codigo']);
                    $this->liclicitalotesRepository->delete($oLote);
                }
            }

            $this->licLicitaProcRepository->deleteByLicitacao($oDispensa->l20_codigo);
            if(!empty($oDispensa->l20_dtpubratificacao)){
                $aDataEnceramento = $this->condataconfRepository->getEncerramentoPatrimonial($oDispensa->l20_anousu, $oDispensa->l20_instit);
                $dataEncerramentoPartrimonial = \DateTime::createFromFormat('Y-m-d', $aDataEnceramento->c99_datapat);
                if($dataEncerramentoPartrimonial >= $oDispensa->l20_dtpubratificacao){
                    throw new \Exception("O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte");
                }
            }

            $licObras = $this->licObrasRepository->findByLicitacao($oDispensa->l20_codigo);
            if(!empty($licObras)){
                throw new \Exception("Licitação vinculada a uma obra!", 400);
            }

            $manutencao = $this->manutencaoLicitacaoRepository->getByLiclicita($oDispensa->l20_codigo);
            if(!empty($manutencao)){
                foreach ($manutencao as $value) {
                    $oManutencao = $this->manutencaoLicitacaoRepository->getManutencaoByCodigo($value['manutlic_sequencial']);
                    $this->manutencaoLicitacaoRepository->delete($oManutencao);
                }
            }

            $this->liclicitaRepository->delete($oDispensa);

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Dispensa/Inexigibilidade removida com sucesso!'
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
