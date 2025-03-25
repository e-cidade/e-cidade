<?php
namespace App\Services\SicomAcodBasico;

require_once("model/contabilidade/arquivos/sicom/mensal/geradores/GerarAM.model.php");

use App\Models\Sicom\SicomAcodBasico;
use App\Repositories\Configuracoes\DbConfigRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025\ArquivoIdeRepository;
use App\Repositories\Patrimonial\Licitacao\Sicom\SicomAcodBasicoRepository;
use App\Repositories\Patrimonial\Materiais\HistoricoMaterialRepository;
use App\Repositories\Patrimonial\Protocolo\CgmRepository;
use Illuminate\Database\Capsule\Manager as DB;
use App\Support\String\StringHelper;
use App\Support\Validation\DocumentsValidatorHelper;
use GerarAM;
use ZipArchive;


class GerarArquivosService extends GerarAM{
    private SicomAcodBasicoRepository $sicomAcodBasicoRepository;
    private DbConfigRepository $dbConfigRepository; 
    private HistoricoMaterialRepository $historicoMaterialRepository;
    private CgmRepository $cgmRepository;

    public function __construct()
    {
        $this->sicomAcodBasicoRepository = new SicomAcodBasicoRepository();
        $this->dbConfigRepository = new DbConfigRepository();
        $this->historicoMaterialRepository = new HistoricoMaterialRepository();
        $this->cgmRepository = new CgmRepository();
    }

    public function execute(object $data){
      $remessa = $data->l228_seqremessa ?? null;
      if(empty($data->l228_seqremessa)){
        $remessa = $this->sicomAcodBasicoRepository->getNextRemessa($data->instit)->l228_seqremessa;
      }

      if(empty($data->itens)){
        return [
          'status' => 500,
          'message' => 'Por favor informe o arquivos a serem gerados'
        ];
      }
      DB::beginTransaction();
      try{

        $oDbConfig = $this->dbConfigRepository->getDadosComplementaresByCodigo($data->instit);

        $pasta = 'tmp/' . $remessa . '/';
        $arquivos = array_diff(scandir($pasta), ['.', '..']);

        foreach ($arquivos as $arquivo) {
            $caminho = $pasta . DIRECTORY_SEPARATOR . $arquivo;
            unlink($caminho);
        }

        mkdir('tmp/' . $remessa);
        
        if(!in_array($oDbConfig->tipoorgao, [50,51,52,53,54,55,56,57,58])){
          $codIdentificador = str_pad($oDbConfig->codmunicipio, 5, '0', STR_PAD_LEFT);
        } else {
          $codIdentificador = str_pad($oDbConfig->codmunicipio, 4, '0', STR_PAD_LEFT);
        }
        
        if(in_array($oDbConfig->tipoorgao, [50,51,52,53,54,55,56,57,58])){
          $aux = $oDbConfig->codorgao;
        } else if(!in_array($oDbConfig->tipoorgao, [1,2,3,4,5,8,9])){
          $aux = str_pad($oDbConfig->codorgao, 3, '0', STR_PAD_LEFT);
        } else {
          $aux = str_pad($oDbConfig->codorgao, 2, '0', STR_PAD_LEFT);
        }

        $nomeArquivoZip = "BASICO_{$codIdentificador}_{$aux}_{$data->anousu}.zip";
        
        $filePathZip = "tmp/{$remessa}/" . $nomeArquivoZip;

        $filePaths = [];

        if (file_exists($filePathZip)) {
          unlink($filePathZip);
        }

        $zip = new ZipArchive();
        $zip->open($filePathZip, ZipArchive::CREATE);

        $generateIDE99 = false;
        $generateITEM99 = false;
        $generatePESSOA99 = false;

        if(in_array('IDE', $data->itens)){
          $generateIDE99 = $this->generateFileIDE($remessa, $data->instit, $data->anousu);
          $filePaths[] = 'IDE';
          $zip->addFile('tmp/'.$remessa.'/IDE.csv', 'IDE.csv');
        }
        if(in_array('ITEM', $data->itens)){
          $generateITEM99 = $this->generateFileITEM($remessa, $data->instit, $data->l228_datainicial, $data->l228_datafim);
          $filePaths[] = 'ITEM';
          $zip->addFile('tmp/'.$remessa.'/ITEM.csv', 'ITEM.csv');
        }
        if(in_array('PESSOA', $data->itens)){
          $generatePESSOA99 = $this->generateFilePESSOA($remessa, $data->instit, $data->l228_datainicial, $data->l228_datafim);
          $filePaths[] = 'PESSOA';
          $zip->addFile('tmp/'.$remessa.'/PESSOA.csv', 'PESSOA.csv');
        }

        $zip->close();
        
        $message = 'Arquivos gerados com sucesso!';
        $status = 200;
        if((!empty($generatePESSOA99) && !is_array($generatePESSOA99)) && !empty($generateITEM99)){
          $message = 'Não há nenhum ITEM ou PESSOA pendente para envio no momento, portanto não é necessário enviar uma nova remessa do Cadastro Básico!';
          $status  = 400;
        }else if(!empty($generatePESSOA99) && is_array($generatePESSOA99)){
          $message = 'O(s) cgm(s) "'. join(', ', $generatePESSOA99) .'" possui(em) cpf/cnpj inválido. Verifique!';
          $status  = 400;
        }

        DB::commit();
        return [
          'status'  => $status,
          'message' => $message,
          'data' => [
            'remessa' => $remessa,
            'csv'     => $filePaths,
            'zip'     => $filePathZip,
            'nomeZip' => $nomeArquivoZip
          ]
        ];
      } catch(\Throwable $e){
        DB::rollBack();
        return [
            'status' => 500,
            'message' => $e->getMessage(),
            'data' => []
        ];
      }
    }


    private function generateFileIDE($remessa, $instit, $anousu){
      $aCsv = [];
      $this->sArquivo = 'tmp/'. $remessa. '/IDE';
      
      $dados = $this->dbConfigRepository->getDadosFile($instit);

      $this->abreArquivo();

      if($dados->isEmpty()){
        $aCsv['tiporegistro'] = '99';
        $this->sLinha = $aCsv;
        $this->adicionaLinha();
        $this->fechaArquivo();
        return true;
      }

      foreach($dados as $dado){
        $aCSV = [];
        $aCSV['codIdentificador']    = $this->padLeftZero($dado->codmunicipio, 5);
        $aCSV['cnpj']                = $this->padLeftZero($dado->cnpjmunicipio, 14);
        $aCSV['codOrgao']            = $this->padLeftZero($dado->codorgao, 3);
        $aCSV['tipoOrgao']           = $this->padLeftZero($dado->tipoorgao, 2);
        $aCSV['exercicioReferencia'] = $this->padLeftZero($anousu, 4);
        $aCSV['dataGeracao']         = $this->sicomDate(date("Y-m-d"));
        $aCSV['codControleRemessa']  = " ";
        $aCSV['codSeqRemessa']       = $remessa;

        $this->sLinha = $aCSV;
        $this->adicionaLinha();
      }

      $this->fechaArquivo();
      return false;
    }
    private function generateFileITEM($remessa, $instit, $l228_datainicial, $l228_datafim){
      $aCsv = [];
      $this->sArquivo = 'tmp/'.$remessa.'/ITEM';
      $this->abreArquivo();

      $dados = $this->historicoMaterialRepository->getDadosFile($instit, $l228_datainicial, $l228_datafim);

      if($dados->isEmpty()){
        $aCsv['tiporegistro'] = '99';
        $this->sLinha = $aCsv;
        $this->adicionaLinha();
        $this->fechaArquivo();
        return true;
      }

      foreach($dados as $dado){
        $aCsv = [];
        $aCSV['tipoRegistro']           = 10;
        $aCSV['tipoCadastro']           = substr($dado->tipocadastro, 0, 1);
        $aCSV['codItem']                = $dado->coditem;
        $aCSV['dscItem']                = substr(
          StringHelper::convertToUtf8(StringHelper::StringReplaceSicom(mb_strtoupper($dado->dscitem))),
          0, 
          1000
        );
        
        $aCSV['unidadeMedida']          = $this->padLeftZero($dado->unidademedida, 3);
        $aCSV['justificativaAlteracao'] = $aCSV['tipoCadastro'] == 2 ? substr(StringHelper::convertToUtf8(StringHelper::StringReplaceSicom($dado->justificativaalteracao)), 0, 100) : '';
        $aCSV['dscOutrosUnidMedida']    = ($dado->unidademedida == '999')? substr(StringHelper::convertToUtf8($dado->dscoutrosunidmedida), 0, 50) : '';

        $this->sLinha = $aCSV;
        $this->adicionaLinha();
      }

      $this->fechaArquivo();
      return false;
    }
    private function generateFilePESSOA($remessa, $instit, $l228_datainicial, $l228_datafim){
      $aCsv = [];
      $this->sArquivo = 'tmp/'. $remessa .'/PESSOA';
      $this->abreArquivo();

      $dados = $this->cgmRepository->getDadosFile($instit, $l228_datainicial, $l228_datafim);

      if($dados->isEmpty()){
        $aCsv['tiporegistro'] = '99';
        $this->sLinha = $aCsv;
        $this->adicionaLinha();
        $this->fechaArquivo();
        return true;
      }

      $erros = [];
      foreach($dados as $dado){
        $iTamanhoDocumento = $dado->tipodocumento == 2 ? 14 : 11;
        $aCSV = [];
        $aCSV['tipoRegistro']  = 10;
        $aCSV['tipoDocumento'] = $this->padLeftZero($dado->tipodocumento, 1);
        $aCSV['nroDocumento']  = $this->padLeftZero(substr(preg_replace('/\D/', '', $dado->nrodocumento), 0, $iTamanhoDocumento), $iTamanhoDocumento);

        if(empty($aCSV['nroDocumento']) || !DocumentsValidatorHelper::validar($aCSV['nroDocumento'])){
          $erros[] = $dado->z01_numcgm;
        }

        $this->sLinha = $aCSV;
        $this->adicionaLinha();
      }

      $this->fechaArquivo();
    
      return $erros;
    }

}
