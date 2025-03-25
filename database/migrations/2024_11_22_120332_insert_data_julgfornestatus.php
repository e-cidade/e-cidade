<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertDataJulgfornestatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            ['l35_label' => 'Normal', 'l35_desc' => 'Cada lance que será gravado'],
            ['l35_label' => 'Melhor Proposta', 'l35_desc' => 'Último lance de cada fornecedor'],
            ['l35_label' => 'Sem lance', 'l35_desc' => 'Fornecedor não dará mais lance não volta para a etapa de lances'],
            ['l35_label' => 'Desclassificado', 'l35_desc' => 'Não listará e não pode participar dos lances para o item'],
            ['l35_label' => 'Inabilitado para o item', 'l35_desc' => 'Fornecedor fica impedido de dar lance no item e não pode ser ganhador'],
        ];

        foreach ($data as &$item) {
            $item['l35_label'] = mb_convert_encoding($item['l35_label'], 'UTF-8', 'ISO-8859-1');
            $item['l35_desc'] = mb_convert_encoding($item['l35_desc'], 'UTF-8', 'ISO-8859-1');
        }

        DB::table('licitacao.julgfornestatus')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('licitacao.julgfornestatus')->whereIn('l35_label', [
            mb_convert_encoding('Normal', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Melhor Proposta', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Sem lance', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Desclassificado', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Inabilitado para o item', 'UTF-8', 'ISO-8859-1')
        ])->delete();
    }
}
