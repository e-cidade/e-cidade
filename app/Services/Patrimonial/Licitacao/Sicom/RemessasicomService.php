<?php


namespace App\Services\Licitacao\Sicom;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;
require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';

class RemessasicomService
{

    public function getCodigoRemessa(){

        $instituicao =  db_getsession("DB_instit");
        $valor = DB::table('licitacao.remessasicom')->where('l227_instit', $instituicao)->max('l227_remessa');
        $novoValor = $valor ? $valor + 1 : 1;
        return $novoValor;

    }

    public function salvarAbertura($processo,$remessa,$data){

        $arquivo = ['ABERLIC' => 3,'RESPLIC' => 4,'PARTLIC' => 5, 'JULGLIC' => 8, 'PARELIC' => 7,'HABLIC' => 6,'HOMOLIC' => 9];
        $usuario = db_getsession('DB_id_usuario');
        $instituicao =  db_getsession("DB_instit");

        $remessaABERLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['ABERLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        $remessaRESPLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['RESPLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        $remessaPARELIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['PARELIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        
        DB::table('licitacao.remessasicom')->insert([$remessaABERLIC,$remessaRESPLIC,$remessaPARELIC]); 
        DB::table('licitacao.parecerlicitacao')->where('l200_licitacao', $processo["l227_licitacao"])->update(['l200_enviosicom' => true]);

        $situacaoAtual = DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->value('l20_licsituacao');

        if (in_array($situacaoAtual, [1,13])){
            $remessaPARTLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['PARTLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            $remessaJULGLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['JULGLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            DB::table('licitacao.remessasicom')->insert([$remessaPARTLIC,$remessaJULGLIC]);
        }

        if($situacaoAtual == 10){
            $remessaPARTLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['PARTLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            $remessaJULGLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['JULGLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            $remessaHABLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HABLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            $remessaHOMOLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HOMOLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            DB::table('licitacao.remessasicom')->insert([$remessaPARTLIC,$remessaJULGLIC,$remessaHABLIC,$remessaHOMOLIC]);
        }



    }

    public function salvarJulgamento($processo,$remessa,$data){

        $arquivo = ['PARTLIC' => 5,'PARELIC' => 7, 'JULGLIC' => 8,'HABLIC' => 6,'HOMOLIC' => 9];
        $usuario = db_getsession('DB_id_usuario');
        $instituicao =  db_getsession("DB_instit");

        $remessaPARTLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['PARTLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        $remessaPARELIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['PARELIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        $remessaJULGLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['JULGLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];

        DB::table('licitacao.remessasicom')->insert([$remessaPARTLIC,$remessaPARELIC,$remessaJULGLIC]);

        $situacaoAtual = DB::table('licitacao.liclicita')->where('l20_codigo', $processo["l227_licitacao"])->value('l20_licsituacao');

        if($situacaoAtual == 10){
            $remessaHABLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HABLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            $remessaHOMOLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HOMOLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
    
            DB::table('licitacao.remessasicom')->insert([$remessaHABLIC,$remessaHOMOLIC]);
        }

        DB::table('licitacao.parecerlicitacao')->where('l200_licitacao', $processo["l227_licitacao"])->update(['l200_enviosicom' => true]);


    }

    public function salvarHomologacao($processo,$remessa,$data){

        $arquivo = ['HABLIC' => 6,'HOMOLIC' => 9];
        $usuario = db_getsession('DB_id_usuario');
        $instituicao =  db_getsession("DB_instit");

        $remessaHABLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HABLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
        $remessaHOMOLIC = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['HOMOLIC'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];

        DB::table('licitacao.remessasicom')->insert([$remessaHABLIC,$remessaHOMOLIC]);

    }

    public function salvarEnvio($processo,$remessa,$data){

        $arquivo = ['DISPENSA' => 10,'REGADESAO' => 11];
        $usuario = db_getsession('DB_id_usuario');
        $instituicao =  db_getsession("DB_instit");

        if(isset($processo["l227_licitacao"]) != null){
            $remessaDISPENSA = ['l227_instit' => $instituicao,'l227_licitacao' => $processo["l227_licitacao"], 'l227_arquivo' => $arquivo['DISPENSA'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            DB::table('licitacao.remessasicom')->insert($remessaDISPENSA);
        }

        if(isset($processo["l227_adesao"]) != null){
            $remessaREGADESAO = ['l227_instit' => $instituicao,'l227_adesao' => $processo["l227_adesao"], 'l227_arquivo' => $arquivo['REGADESAO'], 'l227_remessa' => $remessa,'l227_dataenvio' => $data,'l227_usuario'  => $usuario];
            DB::table('licitacao.remessasicom')->insert($remessaREGADESAO);
        }

    }

}