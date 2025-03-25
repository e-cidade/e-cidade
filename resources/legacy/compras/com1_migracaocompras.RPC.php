<?php

use App\Repositories\Patrimonial\Compras\PcmatereleRepository;
use App\Repositories\Patrimonial\Compras\PcMaterRepository;
use App\Models\PcSubGrupo;
use App\Repositories\Patrimonial\Compras\PcgrupoRepository;
use App\Services\ExcelService;
use App\Repositories\Patrimonial\Compras\PcSubGrupoRepository;
use Illuminate\Support\Facades\DB;

require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

$iInstituicao = db_getsession( 'DB_instit' );
$pcsubgrupoRepository = new PcSubGrupoRepository();
$pcgrupoRepository = new PcGrupoRepository();
$pcmaterRepository = new PcmaterRepository();
$pcmatereleRepository = new PcmaterEleRepository();

function isValidDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function StringReplaceSicom($string){
    $string = preg_replace(array("/(á|à|ã|â|ä|å|æ)/","/(Á|À|Ã|Â|Ä|Å|Æ)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö|Ø)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/","/(ý|ÿ)/","/(Ý)/"),explode(" ","a A e E i I o O u U n N c C y Y"),$string);
    $string = preg_replace('/[^A-Za-z0-9 ?|_;{}\[\]]/', '', $string);
    $string = preg_replace("/[?|?_??]/u", "-", $string);
    $string = preg_replace("/[;]/u", ".", $string);
    $string = preg_replace("/[\[<{|]/u", "(", $string);
    $string = preg_replace("/[\]>}]/u", ")", $string);
    $string = preg_replace("/[+$&]/u", "", $string);
    return $string = preg_replace('/\s{2,}/', ' ', $string);
}

try {

    switch( $oParam->sExecuta ) {

        case 'migrarsubgrupos':

            if(empty( $oParam->sCaminhoArquivo )) {
                throw new ParameterException('Caminho do arquivo não Encontrado !');
            }

            $excelService = new ExcelService();
            $nome_arquivo = $oParam->sCaminhoArquivo;

            $aDadosImportar = [];
            $aDadosPlanilha = array_slice($excelService->importFile($nome_arquivo),1);

            foreach ($aDadosPlanilha as $key => $dados){
                $oGrupo = $pcgrupoRepository->getGrupo($dados['C']);

                if(!$oGrupo){
                    throw new ParameterException("Grupo " . $dados['C'] ." não existe na base de dados.");
                }

                $oSubgrupo = $pcsubgrupoRepository->getSubgrupo($dados['A']);
                if($oSubgrupo){
                    throw new ParameterException("Subgrupo " . $dados['A'] ." ja existe na base de dados.");
                }

                if($dados['A'] == null || $dados['A'] == ""){
                    throw new ParameterException('Código do subgrupo não informado. '.$dados['A']);
                }

                if($dados['B'] == null || $dados['B'] == ""){
                    throw new ParameterException('Descricão do subgrupo não informado. '.$dados['A']);
                }

                if (!is_int((int)$dados['A'])) {
                    throw new ParameterException('Código do SubGrupo inválido! '.$dados['A']);
                }

                if (!is_int((int)$dados['C'])) {
                    throw new ParameterException('Código do Grupo inválido! '.$dados['C']);
                }

                $aDadosImportar [] = [
                    'pc04_codsubgrupo' => $dados['A'],
                    'pc04_descrsubgrupo' => StringReplaceSicom(strtoupper(utf8_decode($dados['B']))),
                    'pc04_codgrupo' => $dados['C'],
                    'pc04_codtipo' => $dados['C'],
                    'pc04_ativo' => 't',
                    'pc04_tipoutil' => 3,
                    'pc04_instit' => db_getsession('DB_instit'),
                ];
            }

            DB::beginTransaction();
                foreach ($aDadosImportar as $iSubGrupos){
                    $pcsubgrupoRepository->salvarSubgrupos($iSubGrupos);
                }

            db_query("select setval('pcsubgrupo_pc04_codsubgrupo_seq',(select max(pc04_codsubgrupo)+1 as codigo from pcsubgrupo))");

            DB::commit();

            break;

        case 'migrarMateriais':

            if(empty( $oParam->sCaminhoArquivo )) {
                throw new ParameterException('Caminho do arquivo não Encontrado !');
            }

            $excelService = new ExcelService();
            $nome_arquivo = $oParam->sCaminhoArquivo;

            $aDadosImportar = [];
            $aDadosPlanilha = array_slice($excelService->importFile($nome_arquivo),1);

            foreach ($aDadosPlanilha as $key => $dados){

                $oPcmater = $pcmaterRepository->getPcmaterAnterior($dados['I']);

                if($oPcmater){
                    throw new ParameterException("Já Existe uma material com mesmo codigo anterior " . $dados['I'] ." na base de dados.");
                }

                if($dados['C'] == null || $dados['C'] == ""){
                    throw new ParameterException('Codigo do Grupo não informado. Material: '.$dados['I']);
                }

                $oSubgrupo = $pcsubgrupoRepository->getSubgrupo($dados['C']);

                if(!$oSubgrupo){
                    throw new ParameterException("Subgrupo " . $dados['C'] ." não existe na base de dados ou pode estar vinculado a outra instituição.");
                }

                if (!is_int((int)$dados['C'])) {
                    throw new ParameterException('Código do SubGrupo inválido! '.$dados['A']);
                }

                if($dados['A'] == null || $dados['A'] == ""){
                    throw new ParameterException('Descricão do Material não informado. '.$dados['I']);
                }

                if (utf8_decode($dados['D']) != "não" && $dados['D'] != "sim") {
                    throw new ParameterException('Coluna pc01_servico com formato Invalido, "sim" pra serviço ou "não" para material. '.utf8_decode($dados['D']));
                }

                $explode = explode('/', $dados['E']);
                $dia = str_pad($explode[1], 2, '0', STR_PAD_LEFT);
                $mes = str_pad($explode[0], 2, '0', STR_PAD_LEFT);
                $ano = $explode[2];
                $pc01_data = $ano.'-'.$mes.'-'.$dia;

                if (!isValidDate($pc01_data)) {
                    throw new ParameterException('Data Invalida! '.$dados['E']);
                }

                if (utf8_decode($dados['F']) != "não" && $dados['F'] != "sim") {
                    throw new ParameterException('Coluna pc01_tabela com formato Invalido, "sim" para Tabela ou "não" para não e Tabela. '.utf8_decode($dados['F']));
                }

                if (utf8_decode($dados['G']) != "não" && $dados['G'] != "sim") {
                    throw new ParameterException('Coluna pc01_taxa com formato Invalido, "sim" para Taxa ou "não" para não e Taxa. '.utf8_decode($dados['G']));
                }

                if (utf8_decode($dados['H']) != "não" && $dados['H'] != "sim") {
                    throw new ParameterException('Coluna pc01_obras com formato Invalido, "sim" para Obra ou "não" para não e Obra. '.utf8_decode($dados['H']));
                }
                if (!is_int((int)$dados['I'])) {
                    throw new ParameterException('Código do Material Anterior inválido! '.$dados['I']);
                }

                $aDadosImportar [] = [
                    'pc01_codmater' => $pcmaterRepository->getNextval(),
                    'pc01_descrmater' => strtoupper(StringReplaceSicom(utf8_decode(substr($dados['A'], 0, 79)))),
                    'pc01_complmater' => strtoupper(StringReplaceSicom(utf8_decode($dados['B']))),
                    'pc01_codsubgrupo' => $dados['C'],
                    'pc01_servico' => utf8_decode($dados['D']) == "não" ? 'f': 't',
                    'pc01_data' => $pc01_data,
                    'pc01_tabela' => utf8_decode($dados['F']) == "não" ? 'f': 't',
                    'pc01_taxa' => utf8_decode($dados['G']) == "não" ? 'f': 't',
                    'pc01_obras' => utf8_decode($dados['H']) == "não" ? 'f': 't',
                    'pc01_codmaterant' => $dados['I'],
                    'pc01_regimobiliario' => strtoupper(utf8_decode($dados['J'])),
                    'pc07_codele' => $dados['K']
                ];
            }

            DB::beginTransaction();
                foreach ($aDadosImportar as $dados){
                    $pcmater [] =[
                        'pc01_codmater' => $dados['pc01_codmater'],
                        'pc01_descrmater' => $dados['pc01_descrmater'],
                        'pc01_complmater' => $dados['pc01_complmater'],
                        'pc01_codsubgrupo' => $dados['pc01_codsubgrupo'],
                        'pc01_ativo' => 'f',
                        'pc01_conversao' => 'f',
                        'pc01_id_usuario' => db_getsession('DB_id_usuario'),
                        'pc01_libaut' => 't',
                        'pc01_servico' => $dados['pc01_servico'],
                        'pc01_veiculo' => 'f',
                        'pc01_validademinima' => 'f',
                        'pc01_obrigatorio' => 'f',
                        'pc01_fraciona' => 'f',
                        'pc01_liberaresumo' => 'f',
                        'pc01_data' => $dados['pc01_data'],
                        'pc01_tabela' => $dados['pc01_tabela'],
                        'pc01_taxa' => $dados['pc01_taxa'],
                        'pc01_obras' => $dados['pc01_obras'],
                        'pc01_instit' => db_getsession('DB_instit'),
                        'pc01_codmaterant' => $dados['pc01_codmaterant'],
                        'pc01_regimobiliario' => $dados['pc01_regimobiliario']
                    ];

                    $pcmaterRepository->salvarPcmater($pcmater);

                    $pcmaterele = [
                        'pc07_codmater' => $dados['pc01_codmater'],
                        'pc07_codele' => $dados['pc07_codele']
                    ];

                    $pcmatereleRepository->salvarPcmaterele($pcmaterele);
                }
            DB::commit();

            break;
    }
} catch ( Exception $oErro ) {
    DB::rollBack();

    $oRetorno->iStatus   = 2;
    $oRetorno->sMensagem =  utf8_encode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
