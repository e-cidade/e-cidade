<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertDataJulgitemstatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            ['l31_label' => 'Em aberto', 'l31_desc' => 'Disponível para receber lances.'],
            ['l31_label' => 'Julgado', 'l31_desc' => 'Etapa de lances concluída para o item.'],
            ['l31_label' => 'Frustrado', 'l31_desc' => 'Falta de Propostas: Não houve empresas interessadas em fornecer o item ou serviço e, portanto, nenhuma proposta foi apresentada. Propostas Desclassificadas: Todas as propostas apresentadas foram desclassificadas por não atenderem aos requisitos técnicos ou legais estabelecidos no edital. Preços Inviáveis: As propostas apresentadas tiveram preços superiores aos valores de referência ou ao orçamento disponível, inviabilizando a contratação. Falta de Documentação: Os licitantes não apresentaram a documentação exigida corretamente, levando à desclassificação das propostas.'],
            ['l31_label' => 'Recurso', 'l31_desc' => 'Significa que uma ou mais partes envolvidas no processo apresentaram um recurso administrativo contestando alguma decisão relacionada a esse item específico.'],
            ['l31_label' => 'Cancelado', 'l31_desc' => 'Alterações nas Necessidades: A administração pode ter identificado que o item não é mais necessário ou que as especificações precisam ser revisadas. Erro no Edital: Pode ter sido detectado algum erro ou inconsistência no edital que comprometa a legalidade ou a clareza do processo licitatório. Questões Orçamentárias: Pode ocorrer uma reavaliação do orçamento disponível, levando à decisão de cancelar o item para adequação financeira. Mudanças de Planejamento: Alterações estratégicas ou de planejamento que influenciam as prioridades e necessidades da administração. Impugnação ou Resultado de Recurso: O item pode ter sido objeto de impugnação por parte dos licitantes ou terceiros, resultando na necessidade de cancelamento para evitar futuros problemas legais.'],
            ['l31_label' => 'Sem acordo', 'l31_desc' => 'Preços Incompatíveis: As propostas apresentadas não atenderam às expectativas de preço da administração pública ou estavam acima do valor de referência estabelecido. Negociações Mal Sucedidas: Durante as fases de lances e negociações, não foi possível obter um preço que fosse considerado justo ou vantajoso para a administração. Condições Inadequadas: Às condições propostas pelos licitantes (prazo de entrega, qualidade do produto, etc.) não foram consideradas adequadas ou aceitáveis.'],
            ['l31_label' => 'Aguardando Readequação', 'l31_desc' => 'O lote está pendente de ajustes na proposta antes de seguir para a próxima etapa']
        ];

        foreach ($data as &$item) {
            $item['l31_label'] = mb_convert_encoding($item['l31_label'], 'UTF-8', 'ISO-8859-1');
            $item['l31_desc'] = mb_convert_encoding($item['l31_desc'], 'UTF-8', 'ISO-8859-1');
        }

        DB::table('licitacao.julgitemstatus')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('licitacao.julgitemstatus')->whereIn('l31_label', [
            mb_convert_encoding('Em aberto', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Julgado', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Frustrado', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Recurso', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Cancelado', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Sem acordo', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Melhor Proposta', 'UTF-8', 'ISO-8859-1'),
            mb_convert_encoding('Aguardando Readequação', 'UTF-8', 'ISO-8859-1')
        ])->delete();
    }
}
