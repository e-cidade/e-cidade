<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Oc23782 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $filePath = 'database/migrations/2025_03_18_094007_oc23782.json';
        $jsonDados = file_get_contents($filePath);
        
        $oDados = json_decode($jsonDados);
        $clientes = DB::table('db_config')
            ->whereIn('cgc', ['17316563000196', '18715383000140'])
            ->get();

        if ($clientes->isEmpty()) {
            $this->contasAlterar($oDados->Alterar);
            $this->contasInserir($oDados->Incluir);
            $this->orcdotacaoAlterar($oDados->Alterar);
        
        }
    }

    private function getInstit()
    {
        return DB::table('db_config')
            ->where('db21_ativo', 1)
            ->pluck('codigo');
    }

    private function contasInserir($contas)
    {
        foreach ($contas as $conta) {
           
            $existe = DB::table('conplanoorcamento')
                    ->where('c60_estrut', 'LIKE', "{$conta->Estrutural}%")
                    ->where('c60_anousu', 2025)
                    ->exists(); 

            if (!$existe) {
                    $c60_codcon = DB::selectOne("SELECT nextval('conplanoorcamento_c60_codcon_seq')")->nextval;

                    DB::table('conplanoorcamento')->insert([
                        'c60_codcon' => $c60_codcon,
                        'c60_anousu' => 2025,
                        'c60_estrut' => str_pad($conta->Estrutural, 15, "0"),
                        'c60_descr' => substr(mb_strtoupper($conta->Descricao), 0, 50),
                        'c60_finali' => $conta->Finalidade,
                        'c60_codsis' => 0,
                        'c60_codcla' => 1,
                        'c60_consistemaconta' => 0,
                        'c60_identificadorfinanceiro' => 'N',
                        'c60_naturezasaldo' => 2,
                        'c60_funcao' => null
                    ]);
                

                if ($conta->Tipo == 'Analitica') {
                    foreach ($this->getInstit() as $instit) {
                        $existe = DB::table('conplanoorcamentoanalitica')
                            ->join('conplanoorcamento', function ($join) {
                                $join->on('conplanoorcamento.c60_codcon', '=', 'conplanoorcamentoanalitica.c61_codcon')
                                    ->whereColumn('conplanoorcamento.c60_anousu', '=', 'conplanoorcamentoanalitica.c61_anousu');
                            })
                            ->where('c60_estrut', 'LIKE', "{$conta->Estrutural}%")
                            ->where('c61_instit', $instit)
                            ->where('c61_anousu', 2025)
                            ->exists();

                        if (!$existe) {
                            $c61_reduz = DB::selectOne("SELECT nextval('conplanoorcamentoanalitica_c61_reduz_seq')")->nextval;
                            
                            DB::table('conplanoorcamentoanalitica')->insert([
                                'c61_codcon' => $c60_codcon,
                                'c61_anousu' => 2025,
                                'c61_reduz' => $c61_reduz,
                                'c61_instit' => $instit,
                                'c61_codigo' => $conta->Fonte
                            ]);
   
                            $this->insertVinculo($c60_codcon, $conta->VinculoPCASP, $instit);
                        }
                    }

                    $newelemento = str_pad($conta->Estrutural, 13, "0");
                    $orcexiste = DB::table('orcelemento')
                        ->where('o56_elemento', 'LIKE', "{$newelemento}%")
                        ->where('o56_anousu', 2025)
                        ->exists();
                    
                    if (!$orcexiste) {
                        DB::table('orcelemento')->insert([
                            'o56_codele'  => $c60_codcon,
                            'o56_anousu'  => 2025,
                            'o56_elemento'=> $newelemento,
                            'o56_descr'   => substr(mb_strtoupper($conta->Descricao), 0, 50),
                            'o56_finali'  => $conta->Finalidade,
                            'o56_orcado'  => true  
                        ]);    
                    } 

                       
                } else {
                    $newelemento = str_pad($conta->Estrutural, 13, "0");
                    $orcexistes = DB::table('orcelemento')
                        ->where('o56_elemento', 'LIKE', "{$newelemento}%")
                        ->where('o56_anousu', 2025)
                        ->exists();
                    if (!$orcexistes) {    
                        DB::table('orcelemento')->insert([
                            'o56_codele'  => $c60_codcon,
                            'o56_anousu'  => 2025,
                            'o56_elemento'=> $newelemento,
                            'o56_descr'   => substr(mb_strtoupper($conta->Descricao), 0, 50),
                            'o56_finali'  => $conta->Finalidade,
                            'o56_orcado'  => true  
                        ]);
                    }
                }
            }
        }
    }

    private function insertVinculo($codconOrc, $estrutPcasp, $instit)
    {
        $contaPcasp = DB::table('conplano')
            ->where('c60_anousu', 2025)
            ->where('c60_estrut', 'LIKE', "{$estrutPcasp}%")
            ->first();

        if ($contaPcasp) {
            $existe = DB::table('conplanoconplanoorcamento')
                ->where('c72_conplanoorcamento', $codconOrc)
                ->where('c72_anousu', 2025)
                ->exists();

            if (!$existe) {
                $c72_sequencial = DB::selectOne("SELECT nextval('conplanoconplanoorcamento_c72_sequencial_seq')")->nextval;

                DB::table('conplanoconplanoorcamento')->insert([
                    'c72_sequencial' => $c72_sequencial,
                    'c72_conplano' => $contaPcasp->c60_codcon,
                    'c72_conplanoorcamento' => $codconOrc,
                    'c72_anousu' => 2025
                ]);
            }
        }
    }

    private function contasAlterar($contas)
    {
       
        foreach ($contas as $conta) {

            $contaNovo = DB::table('conplanoorcamento')
                ->where('c60_estrut', 'LIKE', "{$conta->Estruturalnovo}%")
                ->where('c60_anousu', 2025)
                ->first();

            if (!$contaNovo) {
                $contaExistente = DB::table('conplanoorcamento')
                    ->where('c60_estrut', 'LIKE', "{$conta->Estrutural}%")
                    ->where('c60_anousu', 2025)
                    ->first();
                
                if ($contaExistente) {

                    $elemento = substr($conta->Estruturalnovo, 0, 13);
                    $elementoanterior = str_pad($conta->Estrutural, 13, "0");
                    
                    DB::table('conplanoorcamento')
                        ->where('c60_codcon', $contaExistente->c60_codcon)
                        ->where('c60_anousu', 2025)
                        ->update(['c60_descr' => mb_strtoupper($conta->Descricao), 'c60_finali' => $conta->Descricao, 'c60_estrut' => $conta->Estruturalnovo]);

                    DB::table('orcelemento')
                        ->where('o56_codele', $contaExistente->c60_codcon)
                        ->where('o56_anousu', 2025)
                        ->where('o56_elemento', $elementoanterior)
                        ->update(['o56_descr' => mb_strtoupper($conta->Descricao), 'o56_finali' => $conta->Descricao, 'o56_elemento' => $elemento]);

                }
            }
        }
    }

    private function orcdotacaoAlterar($contas)
    {
        foreach ($contas as $conta) {
            $contaExistente = DB::table('orcelemento')
                ->where('o56_elemento', 'LIKE', "{$conta->Estrutural}%")
                ->where('o56_anousu', 2025)
                ->first();
            
            if ($contaExistente) {

                $elemento = substr($conta->Estruturalnovo, 0, 13);
                $novaConta = DB::table('orcdotacao')
                    ->join('orcelemento', function ($join) use ($elemento) {
                        $join->on('o56_elemento', '=', DB::raw("'".$elemento."'"));
                    })
                    ->where('o56_anousu', 2025)
                    ->where('o58_anousu', 2025)
                    ->first();

                if ($novaConta) {

                    DB::table('orcdotacao')
                        ->where('o58_codele', $novaConta->o56_codele)
                        ->where('o58_anousu', 2025)
                        ->update(['o58_codele' => $contaExistente->o56_codele]);
                }  
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
