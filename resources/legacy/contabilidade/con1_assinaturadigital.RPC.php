<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
use App\Models\Configuracao\AssinaturaDigitalParametro;
use App\Models\Configuracao\AssinaturaDigitalAssinante;
use Illuminate\Database\Capsule\Manager as DB;

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

$assintaraDigitalParametro = new AssinaturaDigitalParametro();

try {

    switch( $oParam->sExecuta ) {

        case 'salvarParamentros':

            DB::beginTransaction();

            AssinaturaDigitalParametro::insert([ 'db242_codigo' =>    $assintaraDigitalParametro->getNextval(),
                'db242_assinador_url' => $oParam->assinador_url,
                'db242_assinador_token' => $oParam->assinador_token,
                'db242_instit' => $oParam->institucao,
                'db242_assinador_ativo' => $oParam->assinador_ativo ]);

            DB::commit();

            break;

        case 'getParamentros':

            DB::beginTransaction();
            $oRetorno->oParametro = DB::table('configuracoes.assinatura_digital_parametro')
                ->select('*')
                ->where('db242_codigo',    $oParam->db242_codigo)
                ->get()->first();
            DB::commit();

            break;

        case 'alterarParamentro':

            DB::beginTransaction();
            AssinaturaDigitalParametro::updateOrCreate([ 'db242_codigo' =>    $oParam->codigo ],
                [ 'db242_codigo' =>    $oParam->codigo,
                'db242_assinador_url' => $oParam->assinador_url,
                'db242_assinador_token' => $oParam->assinador_token,
                'db242_instit' => $oParam->institucao,
                'db242_assinador_ativo' => $oParam->assinador_ativo
                ]);

            DB::commit();

            break;

        case 'excluirParamentros':

            DB::beginTransaction();
            AssinaturaDigitalParametro::destroy($oParam->db242_codigo);
            DB::commit();

            break;

        case 'getInstituicoes':
            $oRetorno->aIntituicoes = DB::table('configuracoes.db_config')
                ->select('codigo', 'nomeinst')
                ->get()->toArray();
            break;

        case 'getParametrosAssinaturaDigital':

            $oRetorno->aParamentrosAssintura = DB::table('configuracoes.assinatura_digital_parametro')
                ->select('db242_codigo' ,'db242_assinador_url', 'db242_assinador_token', 'db242_instit' , 'db242_assinador_ativo', 'nomeinst' )
                ->join('configuracoes.db_config', 'codigo' ,'=','db242_instit')
                ->get()->toArray();

            break;

        case 'getUnidades':

            $oRetorno->aUnidades = DB::table('orcamento.orcunidade')
                ->join('configuracoes.db_config', 'configuracoes.db_config.codigo','=','orcamento.orcunidade.o41_instit')
                ->select('orcamento.orcunidade.o41_orgao','orcamento.orcunidade.o41_unidade','orcamento.orcunidade.o41_descr','configuracoes.db_config.nomeinst','orcamento.orcunidade.o41_instit')
                ->where('orcamento.orcunidade.o41_anousu', '=', db_getsession("DB_anousu"))
                ->where('orcamento.orcunidade.o41_instit', '=', $oParam->iIntituicao)
                ->orderBy('orcamento.orcunidade.o41_orgao')
                ->orderBy('orcamento.orcunidade.o41_unidade')
                ->get()
                ->toArray();

            break;

        case 'getLoadForm':

            $oRetorno->aIntituicoes = DB::table('configuracoes.db_config')
                ->select('codigo', 'nomeinst')
                ->get()->toArray();

            $oRetorno->aUsuarios =  DB::table('configuracoes.db_usuarios')
                ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario','=','configuracoes.db_usuarios.id_usuario')
                ->join('protocolo.cgm', 'configuracoes.db_usuacgm.cgmlogin','=','protocolo.cgm.z01_numcgm')
                ->select('configuracoes.db_usuarios.id_usuario','protocolo.cgm.z01_nome','configuracoes.db_usuarios.login'  )
                ->orderBy('protocolo.cgm.z01_nome')
                ->get()
                ->toArray();

            $oRetorno->aCargos = AssinaturaDigitalAssinante::ASSINTAURA_CARGOS;

            $oRetorno->aDocumentos = AssinaturaDigitalAssinante::DOCUMENTOS_ASSINAVEIS;

            break;

         case 'salvarAssinantes':
             DB::beginTransaction();
             foreach ($oParam->aUnidades as $aUnidade){
                 $aOrgaoUnidade = explode('-', $aUnidade );
                 $iOrgao = $aOrgaoUnidade[0];
                 $iUnidade = $aOrgaoUnidade[1];
                 foreach ($oParam->aCargos as $iCargo){
                     foreach ($oParam->aDocumentos as $iDocumento){
                         $assinante = [
                             'db243_instit' => $oParam->oIntituicao,
                             'db243_orgao' => $iOrgao,
                             'db243_unidade' => $iUnidade,
                             'db243_usuario' => $oParam->oUsuario,
                             'db243_cargo' => $iCargo,
                             'db243_documento' => $iDocumento,
                             'db243_data_inicio' => $oParam->dataIncio,
                             'db243_data_final' => $oParam->dataFinal,
                             'db243_anousu' => db_getsession("DB_anousu"),
                         ];
                         AssinaturaDigitalAssinante::create($assinante);
                     }
                 }
             }
             DB::commit();
             break;

        case 'alterarAssinante':

            DB::beginTransaction();
            $aOrgaoUnidade = explode('-', $oParam->iUnidade );
            $iOrgao = $aOrgaoUnidade[0];
            $iUnidade = $aOrgaoUnidade[1];
            AssinaturaDigitalAssinante::updateOrCreate(
                [ 'db243_codigo' =>    $oParam->db243_codigo ],
                [ 'db243_instit' => $oParam->iIntituicao,
                    'db243_orgao' => $iOrgao,
                    'db243_unidade' => $iUnidade,
                    'db243_usuario' => $oParam->iUsuario,
                    'db243_cargo' => $oParam->iCargo,
                    'db243_documento' => $oParam->iDocumento,
                    'db243_data_inicio' => $oParam->dataIncio,
                    'db243_data_final' => $oParam->dataFinal,
                    'db243_anousu' => db_getsession("DB_anousu"),
                ]);

            DB::commit();

            break;

        case 'getAssinantes':
            $oRetorno->aAssinantes = DB::table('configuracoes.assinatura_digital_assinante')
                ->join('configuracoes.db_usuarios', 'id_usuario', '=', 'db243_usuario')
                ->join('orcamento.orcunidade', function ($join){
                            $join->on('o41_anousu', '=', 'db243_anousu')
                                ->on('o41_unidade', '=', 'db243_unidade')
                                ->on('o41_orgao', '=', 'db243_orgao')
                                ->on('o41_instit', '=', 'db243_instit');
                })
                ->select('configuracoes.assinatura_digital_assinante.*', 'orcamento.orcunidade.*', 'configuracoes.db_usuarios.login')
                ->where('configuracoes.assinatura_digital_assinante.db243_usuario', '=', $oParam->iUsuario)
                ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', db_getsession("DB_anousu"))
                ->orderBy('configuracoes.assinatura_digital_assinante.db243_codigo')
                ->get()->toArray();
            break;

        case 'getAssinante':
            $oRetorno->oAssinante = DB::table('configuracoes.assinatura_digital_assinante')
                ->select('configuracoes.assinatura_digital_assinante.*')
                ->where('configuracoes.assinatura_digital_assinante.db243_codigo', '=', $oParam->db243_codigo)
                ->first();
            break;

        case 'excluirAssinante':
            DB::beginTransaction();
            AssinaturaDigitalAssinante::destroy($oParam->db243_codigo);
            DB::commit();
            break;

    }
} catch ( Exception $oErro ) {
    DB::rollBack();

    $oRetorno->iStatus   = 2;
    $oRetorno->sMensagem =  $oErro->getMessage() ;
}
echo $oJson->encode(DBString::utf8_encode_all($oRetorno));


