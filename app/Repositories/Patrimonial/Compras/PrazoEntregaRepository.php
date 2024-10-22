<?php

namespace App\Repositories\Patrimonial\Compras;
use App\Models\Patrimonial\Compras\PrazoEntrega;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class PrazoEntregaRepository
{
    private PrazoEntrega $model;

    public function __construct()
    {
        $this->model = new PrazoEntrega();
    }

    public function insert(array $dados): ?PrazoEntrega
    {
        
         $pc97_sequencial = $this->model->getNextval();
         $dados['pc97_sequencial'] = $pc97_sequencial;
   
         return $this->model->create($dados);
    }

    public function getprazoscadastradosAltera($ordem)
{
    $sql = "
    SELECT 
        m51_codordem,
        COALESCE(m51_prazoentnovo, pc97_descricao) as pc97_descricao, -- Usa o prazo novo, se houver
        '1' as is_selected
    FROM
        matordem 
    LEFT JOIN 
        prazoentrega ON pc97_sequencial = m51_sequencial
    WHERE 
        m51_codordem = ?
    
    UNION ALL
    
    SELECT 
        NULL AS m51_codordem, 
        pc97_descricao,
        '0' as is_selected
    FROM 
        prazoentrega
    WHERE 
        pc97_ativo = 't'
        AND pc97_descricao NOT IN (
            SELECT COALESCE(m51_prazoentnovo, pc97_descricao)
            FROM matordem 
            LEFT JOIN prazoentrega ON pc97_sequencial = m51_sequencial
            WHERE m51_codordem = ?
        )
    GROUP BY pc97_descricao"; 

    return DB::select($sql, [$ordem, $ordem]);
}

    public function getprazosfornecedores($pc97_sequencial)
    {
        $sql = "SELECT
                pc97_sequencial,
                pc97_descricao,
                pc97_ativo
                FROM prazoentrega
                WHERE pc97_sequencial = $pc97_sequencial";

        return DB::select($sql);
    }

    public function getprazoscadastrados()
    {
        $sql = "SELECT
                pc97_sequencial,
                pc97_descricao
                FROM prazoentrega
                WHERE pc97_ativo = 't'";
        return DB::select($sql);
    }
    
    public function update(array $dados):bool
    {
        return DB::table('prazoentrega')->where('pc97_sequencial',$dados['pc97_sequencial'])->update($dados);
    }

    public function delete($pc97_sequencial)
    {
        // Verifica se há vínculos
        $sqlvinc = "SELECT COUNT(*) as count FROM matordem WHERE m51_sequencial = ?";
        $vinculos = DB::select($sqlvinc, [$pc97_sequencial]);
    
        if ($vinculos[0]->count > 0) {
            throw new Exception('Usuário: A exclusão não poderá ser efetuada, o tipo de prazo de entrega selecionado está vinculado a ordens de compras.');
        }
    
        $sql = "DELETE FROM prazoentrega WHERE pc97_sequencial = ?";
        return DB::statement($sql, [$pc97_sequencial]);
    }

    
}




