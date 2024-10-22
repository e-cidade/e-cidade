<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\Licproposta;
use Illuminate\Database\Capsule\Manager as DB;

class LicpropostaRepository
{
    private Licproposta $model;

    public function __construct()
    {
        $this->model = new Licproposta();
    }

    public function insert(array $dados): ?Licproposta
    {
        $l244_sequencial = $this->model->getNextval();
        $dados['l224_sequencial'] = $l244_sequencial;
        return $this->model->create($dados);
    }

    public function delete($l224_codigo)
    {
        $sql = "DELETE FROM licproposta WHERE l224_codigo = $l224_codigo";
        return DB::statement($sql);
    }

    public function update(array $dados):bool
    {
        return DB::table('licproposta')->where('l224_sequencial',$dados['l224_sequencial'])->update($dados);
    }
    public function getProposta($l223_codigo,$l223_fornecedor)
    {
        $sql = "SELECT
                    l224_porcent,
                    l224_vlrun,
                    l224_valor,
                    l224_marca,
                    l223_fornecedor,
                    l224_codigo
                FROM licproposta
                INNER JOIN licpropostavinc ON licproposta.l224_codigo = licpropostavinc.l223_codigo
                WHERE l223_fornecedor = $l223_fornecedor AND l223_liclicita = $l223_codigo ";
                
                
        return DB::select($sql);
    }

    public function getSequencial($l224_codigo,$l224_propitem)
    {

       $sql = " SELECT 
                l224_sequencial
                FROM licproposta
                WHERE l224_codigo = $l224_codigo 
                AND l224_propitem = $l224_propitem";
                return DB::select($sql);
               
    }
    public function getCriterio($l20_codigo)
    {
        $sql = "SELECT
                l20_criterioadjudicacao,
                l20_tipojulg
                FROM liclicita
                WHERE l20_codigo = $l20_codigo";
                return DB::select($sql);
        
    }

    public function getLote($l20_codigo)
    {
        $sql = "SELECT DISTINCT
                l04_descricao
                FROM liclicitem
                LEFT JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                WHERE l20_codigo = $l20_codigo";
                return DB::select($sql);
    }
}




