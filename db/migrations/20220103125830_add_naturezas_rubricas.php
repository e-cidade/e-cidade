<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddNaturezasRubricas extends PostgresMigration
{
    protected $arrNatureza = array(
'1089' => 'Cesta b�sica ou refei��o, n�o vinculada ao PAT ',
'1022' => 'F�rias - Abono ou gratifica��o de f�rias n�o excedente a 20 dias',
'1216' => 'Adicional de localidade',
'1351' => 'Bolsa de estudo - M�dico residente',
'1404' => 'Aux�lio bab�',
'1405' => 'Assist�ncia m�dica',
'1207' => 'Comiss�es, porcentagens, produ��o',
'1208' => 'Gueltas ou gorjetas - Repassadas por fornecedores ou clientes',
'1209' => 'Gueltas ou gorjetas - Repassadas pelo empregador',
'1210' => 'Gratifica��o por acordo ou conven��o coletiva',
'1217' => 'Gratifica��o de curso/concurso',
'1225' => 'Quebra de caixa',
'1230' => 'Remunera��o do dirigente sindical',
'1300' => 'PLR - Participa��o em Lucros ou Resultados',
'1352' => 'Bolsa de estudo ou pesquisa',
'1401' => 'Abono',
'1406' => 'Aux�lio-creche',
'1006' => 'Intervalos intra e inter jornadas n�o concedidos',
'1007' => 'Luvas e premia��es',
'1009' => 'Sal�rio-fam�lia - Complemento',
'1010' => 'Sal�rio in natura - Pagos em bens ou servi�os',
'1011' => 'Sobreaviso e prontid�o',
'1021' => 'F�rias - Abono ou gratifica��o de f�rias superior a 20 dias',
'1214' => 'Adicional de penosidade',
'1005' => 'Direito de arena',
'1080' => 'Stock option',
'1412' => 'Abono perman�ncia',
'1601' => 'Ajuda de custo - Aeronauta',
'1603' => 'Ajuda de custo',
'1604' => 'Ajuda de custo - Acima de 50% da remunera��o mensal',
'1619' => 'Ajuda compensat�ria - Lei 14.020/2020',
'1800' => 'Alimenta��o concedida em pec�nia',
'1802' => 'Etapas (mar�timos)',
'1806' => 'Alimenta��o em ticket ou cart�o, vinculada ao PAT',
'1807' => 'Alimenta��o em ticket ou cart�o, n�o vinculada ao PAT',
'1809' => 'Cesta b�sica ou refei��o, n�o vinculada ao PAT',
'1899' => 'Outros aux�lios',
'2501' => 'Pr�mios',
'2502' => 'Liberalidades concedidas em mais de duas parcelas anuais',
'2510' => 'Direitos autorais e intelectuais',
'2801' => 'Quarentena remunerada',
'2901' => 'Empr�stimos',
'2902' => 'Vestu�rio e equipamentos',
'2999' => 'Arredondamentos',
'3505' => 'Retiradas (pr�-labore) de diretores empregados',
'3508' => 'Retiradas (pr�-labore) de propriet�rios ou s�cios',
'3509' => 'Honor�rios a conselheiros',
'1411' => 'Aux�lio-natalidade',
'1650' => 'Di�rias de viagem',
'1808' => 'Cesta b�sica ou refei��o, vinculada ao PAT',
'3510' => 'Gratifica��o (jeton)',
'3511' => 'Gratifica��o eleitoral',
'3520' => 'Remunera��o de cooperado',
'3525' => 'C�ngruas, prebendas e afins',
'4010' => 'Complementa��o salarial de aux�lio-doen�a',
'4011' => 'Complemento de sal�rio-m�nimo - RPPS',
'5501' => 'Adiantamento de sal�rio',
'5510' => 'Adiantamento de benef�cios previdenci�rios',
'6105' => 'Indeniza��o recebida a t�tulo de incentivo a demiss�o',
'6107' => 'Indeniza��o por quebra de estabilidade',
'6119' => 'Indeniza��o rescis�ria - Lei 14.020/2020',
'9243' => 'Cesta b�sica ou refei��o, vinculada ao PAT - Desconto',
'6103' => 'Indeniza��o do art. 14 da Lei 5.889/1973',
'7003' => 'Proventos - Reserva',
'7004' => 'Proventos - Reforma',
'7005' => 'Pens�o Militar',
'7006' => 'Aux�lio-reclus�o',
'7007' => 'Pens�es especiais',
'7008' => 'Complementa��o de aposentadoria/ pens�o',
'9202' => 'Contribui��o militar',
'9207' => 'Faltas',
'9208' => 'Atrasos',
'9211' => 'DSR sobre faltas',
'9212' => 'DSR sobre atrasos',
'9220' => 'Alimenta��o - Desconto',
'9224' => 'FAPI - Parte do empregado',
'9226' => 'Desconto de f�rias - Abono',
'9233' => 'Contribui��o sindical - Confederativa',
'9240' => 'Alimenta��o concedida em pec�nia - Desconto',
'9241' => 'Alimenta��o em ticket ou cart�o, vinculada ao PAT - Desconto',
'9242' => 'Alimenta��o em ticket ou cart�o, n�o vinculada ao PAT - Desconto',
'9244' => 'Cesta b�sica ou refei��o, n�o vinculada ao PAT - Desconto',
'9255' => 'Empr�stimos do empregador - Desconto',
'9260' => 'FIES - Desconto',
'9291' => 'Abate-teto',
'9292' => 'Ressarcimento ao er�rio',
'9293' => 'Honor�rios advocat�cios',
'9294' => 'Redutor EC 41/03',
'9909' => 'Base de c�lculo da contribui��o previdenci�ria - Aposentados e Pensionistas',
'9905' => 'Servi�o militar',
'9906' => 'Remunera��o no exterior',
'9907' => 'Total da contribui��o da previdenci�ria patronal - RPPS',
'9910' => 'Seguros',
'9911' => 'Assist�ncia M�dica',
'9950' => 'Horas extraordin�rias - Banco de horas',
'9951' => 'Horas compensadas - Banco de horas'
);

    public function up()
    {
        $sql = "";
        foreach ($this->arrNatureza as $codNatureza => $descNatureza) {
            if($this->checkNatureza($codNatureza)) {
                $sql .= "INSERT INTO rubricasesocial values ('{$codNatureza}','{$descNatureza}');";
            }
        }
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
        DELETE FROM rubricasesocial WHERE e990_sequencial = '1089';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1022';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1216';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1351';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1404';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1405';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1207';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1208';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1209';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1210';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1217';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1225';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1230';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1300';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1352';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1401';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1406';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1006';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1007';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1009';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1010';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1011';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1021';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1214';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1005';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1080';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1412';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1601';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1603';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1604';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1619';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1800';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1802';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1806';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1807';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1809';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1899';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2501';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2502';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2510';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2801';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2901';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2902';
DELETE FROM rubricasesocial WHERE e990_sequencial = '2999';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3505';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3508';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3509';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1411';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1650';
DELETE FROM rubricasesocial WHERE e990_sequencial = '1808';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3510';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3511';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3520';
DELETE FROM rubricasesocial WHERE e990_sequencial = '3525';
DELETE FROM rubricasesocial WHERE e990_sequencial = '4010';
DELETE FROM rubricasesocial WHERE e990_sequencial = '4011';
DELETE FROM rubricasesocial WHERE e990_sequencial = '5501';
DELETE FROM rubricasesocial WHERE e990_sequencial = '5510';
DELETE FROM rubricasesocial WHERE e990_sequencial = '6105';
DELETE FROM rubricasesocial WHERE e990_sequencial = '6107';
DELETE FROM rubricasesocial WHERE e990_sequencial = '6119';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9243';
DELETE FROM rubricasesocial WHERE e990_sequencial = '6103';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7003';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7004';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7005';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7006';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7007';
DELETE FROM rubricasesocial WHERE e990_sequencial = '7008';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9202';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9207';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9208';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9211';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9212';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9220';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9224';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9226';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9233';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9240';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9241';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9242';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9244';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9255';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9260';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9291';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9292';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9293';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9294';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9909';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9905';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9906';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9907';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9910';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9911';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9950';
DELETE FROM rubricasesocial WHERE e990_sequencial = '9951';
        ";
        $this->execute($sql);
    }

    private function checkNatureza($cod)
    {
        $result = $this->fetchRow("SELECT * FROM rubricasesocial WHERE e990_sequencial = '{$cod}'");
        if (empty($result)) {
            return true;
        }
        return false;
    }
}
