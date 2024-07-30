<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits;

trait ValoresPontoEvento
{
  use TipoPontoEvento;
  /**
   * Retorna
   *
   * @param integer $ponto
   * @param integer $matricula
   * @return resource
   */
  public function getValoresPorPonto(int $ponto, int $matricula)
  {
    if (empty($ponto)) {
      return db_query('');
    }
    $anousu = date("Y", db_getsession("DB_datausu"));
    $mesusu = date("m", db_getsession("DB_datausu"));

    $tipoPonto = $this->getTipoPonto($ponto);
    $sql = "  select '1' as ordem ,
                               {$tipoPonto->sigla}rubric as rubrica,
                               case
                                 when rh27_pd = 3 then 0
                                 else case
                                        when {$tipoPonto->sigla}pd = 1 then {$tipoPonto->sigla}valor
                                        else 0
                                      end
                               end as Provento,
                               case
                                 when rh27_pd = 3 then 0
                                 else case
                                        when {$tipoPonto->sigla}pd = 2 then {$tipoPonto->sigla}valor
                                        else 0
                                      end
                               end as Desconto,
                               {$tipoPonto->sigla}quant as quant,
                               rh27_descr,
                               {$tipoPonto->xtipo} as tipo ,
                               case
                                 when rh27_pd = 3 then 'Base'
                                 else case
                                        when {$tipoPonto->sigla}pd = 1 then 'Provento'
                                        else 'Desconto'
                                      end
                               end as provdesc,
                               case
                                when '{$tipoPonto->arquivo}' = 'gerfsal' then 1
                                when '{$tipoPonto->arquivo}' = 'gerfcom' then 3
                                when '{$tipoPonto->arquivo}' = 'gerfs13' then 4
                                when '{$tipoPonto->arquivo}' = 'gerfres' then 2
                                end as ideDmDev
                          from {$tipoPonto->arquivo}
                               inner join rhrubricas on rh27_rubric = {$tipoPonto->sigla}rubric
                                                    and rh27_instit = " . db_getsession("DB_instit") . "
                          " . bb_condicaosubpesproc($tipoPonto->sigla, $anousu . "/" . $mesusu) . "
                           and {$tipoPonto->sigla}regist = $matricula
                           and {$tipoPonto->sigla}pd != 3
                           and {$tipoPonto->sigla}rubric not in ('R985','R993','R981')
                           order by {$tipoPonto->sigla}pd,{$tipoPonto->sigla}rubric";
    return db_query($sql);
  }
}
