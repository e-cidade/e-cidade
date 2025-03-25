<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicitaSituacao;
use cl_liclicita;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicitaSituacaoRepository
{
    private LicLicitaSituacao $model;

    public function __construct()
    {
        $this->model = new LicLicitaSituacao();
    }

    public function getLicSituacaoByLiclicita($l11_liclicita){
        return $this->model
            ->where('l11_liclicita', $l11_liclicita)
            ->first();
    }

    function delete(LicLicitaSituacao $aData){
        $aData->delete();
    }

    public function save(LicLicitaSituacao $data){
        $data->save();
        return $data;
    }

    function getNextVal(){
        return $this->model->getNextval();
    }

    public function findByParams(int $l11_id_usuario, int $l11_liclicita){
        return $this->model
            ->where('l11_id_usuario', $l11_id_usuario)
            ->where('l11_liclicita', $l11_liclicita)
            ->orderBy('l11_sequencial', 'DESC')
            ->first();
    }

    public function update(int $l11_sequencial, array $data){
        $oData = $this->model->findOrFail($l11_sequencial);
        $oData->update($data);
        return $oData;
    }

    public function findSituacao(int $l20_codigo){
        return $this->model
            ->join(
                'db_usuarios',
                'db_usuarios.id_usuario',
                '=',
                'liclicitasituacao.l11_id_usuario'
            )
            ->join(
                'liclicita',
                'liclicita.l20_codigo',
                '=',
                'liclicitasituacao.l11_liclicita'
            )
            ->join(
                'licsituacao',
                'licsituacao.l08_sequencial',
                '=',
                'liclicitasituacao.l11_licsituacao'
            )
            ->join(
                'db_config',
                'db_config.codigo',
                '=',
                'liclicita.l20_instit'
            )
            ->join(
                'cflicita',
                'cflicita.l03_codigo',
                '=',
                'liclicita.l20_codtipocom'
            )
            ->join(
                'liclocal',
                'liclocal.l26_codigo',
                '=',
                'liclicita.l20_liclocal'
            )
            ->join(
                'liccomissao',
                'liccomissao.l30_codigo',
                '=',
                'liclicita.l20_liccomissao'
            )
        ->where('l11_liclicita', $l20_codigo)
        ->get();
    }

    public function deleteByLiclicita(int $l11_liclicita){
        return $this->model
            ->where('l11_liclicita', $l11_liclicita)
            ->delete();
    }

    public function findSituacaoParecer($l20_codigo, $l11_licsituacao){
        return $this->model
                ->join(
                    'db_usuarios',
                    'db_usuarios.id_usuario',
                    '=',
                    'liclicitasituacao.l11_id_usuario'
                )
                ->join(
                    'liclicita',
                    'liclicita.l20_codigo',
                    '=',
                    'liclicitasituacao.l11_liclicita'
                )
                ->join(
                    'licsituacao',
                    'licsituacao.l08_sequencial',
                    '=',
                    'liclicitasituacao.l11_licsituacao'
                )
                ->join(
                    'db_config',
                    'db_config.codigo',
                    '=',
                    'liclicita.l20_instit'
                )
                ->join(
                    'cflicita',
                    'cflicita.l03_codigo',
                    '=',
                    'liclicita.l20_codtipocom'
                )
                ->join(
                    'liclocal',
                    'liclocal.l26_codigo',
                    '=',
                    'liclicita.l20_liclocal'
                )
                ->join(
                    'liccomissao',
                    'liccomissao.l30_codigo',
                    '=',
                    'liclicita.l20_liccomissao'
                )
            ->where('l11_liclicita', $l20_codigo)
            ->where('l11_licsituacao', $l11_licsituacao)
            ->orderBy('l11_sequencial', 'DESC')
            ->first();
    }

}   
