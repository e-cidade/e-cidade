<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

include("fpdf151/pdf.php");
include("libs/db_sql.php");


function verificaTipoDocumento(string $numeroDoc): string
{
    if (strlen($numeroDoc) === 11) {
        return "CPF " . mask($numeroDoc, '###.###.###-##');
    }
    return "CNPJ " . mask($numeroDoc, '##.###.###/####-##');
}

function verificaTipoDocumentoCPF(string $numeroDoc): string
{
    if (strlen($numeroDoc) === 11) {
        return true;
    }
    return false;
}

function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k])) $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i])) $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

/*
 * query que busca os dados para retorno do relatório
 */

use ECidade\Patrimonial\Protocolo\Serpro;
use App\Services\Configuracoes\ApiRFPService;
use App\Repositories\Configuracoes\DbConfigRepository;
use App\Repositories\Patrimonial\Protocolo\CgmSituacaoCadastralRepository;

try {
    $oApiRFP = new ApiRFPService();
    $oDadosInstituicao = (new DbConfigRepository())->getByCodigo(db_getsession("DB_instit"));

    if (verificaTipoDocumentoCPF($documento)) {
        $serpro = new Serpro($oApiRFP->getURL() . "validar/cpf");
        $serpro->setDados(array('cpf' => $documento, 'cliente' => $oApiRFP->getCliente(), 'tipo' => $oDadosInstituicao->db21_apirfb));
        $cgm = $serpro->request();
        $documento = mask($documento, '###.###.###-##');
    } else {
        $serpro = new Serpro($oApiRFP->getURL() . "validar/cnpj");
        $serpro->setDados(array('cnpj' => $documento, 'cliente' => $oApiRFP->getCliente(), 'tipo' => $oDadosInstituicao->db21_apirfb));
        $cgm = $serpro->request();
        $documento = mask($documento, '##.###.###/####-##');
    }
} catch (Exception $e) {
}

/*
 * construção do relatório
 */
$head1 = "Consulta dados RFB";
$head3 = "Documento: " . $documento;

$pdf = new PDF('Landscape', 'mm', 'A4');
$pdf->Open();
$pdf->AliasNbPages();
$alt = 4;
$total = 0;
$pdf->setfillcolor(235);
$pdf->addpage("L");
$pdf->setfont('arial', 'b', 8);

if ($cgm->data->pessoa_fisica_info) {
    /** @var \App\Models\Cgm $cgm */
    $pdf->setfont('arial', 'b', 8);
    $cabecalho = "Nome: {$cgm->data->pessoa_fisica_info->nome} - " . verificaTipoDocumento($cgm->data->pessoa_fisica_info->ni);
    $pdf->cell(279, $alt, $cabecalho, 0, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(279, $alt, "Dados", 1, 1, "C", 1);

    $pdf->cell(20, $alt, "Situação: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(110, $alt, $cgm->data->pessoa_fisica_info->situacao->codigo . ' - ' . $cgm->data->pessoa_fisica_info->situacao->situacao, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Nascimento:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_fisica_info->data_nascimento, 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(20, $alt, "Óbito:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(110, $alt, $cgm->data->pessoa_fisica_info->ano_obito, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Consulta:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_fisica_info->data_consulta . ' ' . $cgm->data->pessoa_fisica_info->hora_consulta, 1, 0, "L", 0);

    $pdf->cell(279, $alt, "", 0, 1, "C");
} else if ($cgm->data->pessoa_juridica_info) {
    /** @var \App\Models\Cgm $cgm */
    $pdf->setfont('arial', 'b', 8);
    $cabecalho = "Nome: {$cgm->data->pessoa_juridica_info->nome_empresarial} - " . verificaTipoDocumento($cgm->data->pessoa_juridica_info->ni);
    $pdf->cell(279, $alt, $cabecalho, 0, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(279, $alt, "Dados", 1, 1, "C", 1);

    $pdf->cell(30, $alt, "Nome Fantasia: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->nome_fantasia, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Abertura:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->data_abertura, 1, 1, "L", 0);


    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Telefone: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->telefone[0]->ddd . ' ' . $cgm->data->pessoa_juridica_info->telefone[0]->telefone, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Correio Eletronico:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->correio_eletronico, 1, 1, "L", 0);


    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Logradouro: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->logradouro . ', ' . $cgm->data->pessoa_juridica_info->endereco[0]->numero, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Complemento:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->complemento, 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Bairro: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->bairro, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);

    $pdf->cell(27, $alt, "Cidade: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->municipio, 1, 1, "L", 0);
    $pdf->setfont('arial', 'b', 8);

    $pdf->cell(30, $alt, "UF:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->uf, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "CEP:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->endereco[0]->cep, 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Capital Social: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, number_format($cgm->data->pessoa_juridica_info->capital_social, 2, ',', '.'), 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Porte:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $porte =  array(
        "0" => "Selecione",
        "1" => "Microempresa - ME",
        "3" => "Empresa de pequeno porte - EPP",
        "5" => "Demais empresas"
    );
    $pdf->cell(122, $alt, $porte[(int) $cgm->data->pessoa_juridica_info->porte], 1, 1, "L", 0);

    $aSituacaoCadastral = CgmSituacaoCadastralRepository::toArray();
    
    foreach ($cgm->data->pessoa_juridica_info->situacao_cadastral as $situacao) {
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(30, $alt, "Situação Cadastral:  ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
   
        if ($situacao->codigo == 1) {
            $situacao->codigo = 8;
        }
        if ($situacao->codigo == 2) {
            $situacao->codigo = 0;
        }
        if ($situacao->codigo == 3) {
            $situacao->codigo = 2;
        }
        if ($situacao->codigo == 8) {
            $situacao->codigo = 10;
        }
        if ($situacao->codigo == 4) {
            $situacao->codigo = 6;
        }
        if ($situacao->codigo == 5) {
            $situacao->codigo = 7;
        }

        $pdf->cell(100, $alt, $aSituacaoCadastral[$situacao->codigo] . ' - ' . utf8_decode($situacao->motivo), 1, 0, "L", 0);

        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(27, $alt, "Data da Situação:  ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(122, $alt, date('d/m/Y', strtotime($situacao->data)), 1, 1, "L", 0);
    }

    foreach ($cgm->data->pessoa_juridica_info->natureza_juridica as $natureza) {
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(30, $alt, "Natureza Jurídica: ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(249, $alt, $natureza->codigo . ' - ' . utf8_decode($natureza->descricao), 1, 1, "L", 0);
    }

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Atividade Principal:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(249, $alt, $cgm->data->pessoa_juridica_info->cnae_principal[0]->codigo . " - " . utf8_decode($cgm->data->pessoa_juridica_info->cnae_principal[0]->descricao), 1, 1, "L", 0);

    if ($cgm->data->pessoa_juridica_info->cnae_secundarios) {
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(279, $alt, "Atividades Secundários:", 1, 1, "C", 1);
    }

    foreach ($cgm->data->pessoa_juridica_info->cnae_secundarios as $cnae) {
        $pdf->setfont('arial', '', 6);
        $pdf->cell(30, $alt, $cnae->codigo, 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(249, $alt, utf8_decode($cnae->descricao), 1, 1, "L", 0);
    }

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(279, $alt, "Informações Adicionais", 1, 1, "C", 1);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Optante Simples: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, ($cgm->data->pessoa_juridica_info->informacoes_adicionais->optante_simples ? 'Sim' : 'Não'), 1, 0, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Inicio: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(48, $alt, $cgm->data->pessoa_juridica_info->informacoes_adicionais->lista_periodo_simples->data_inicio, 1, 0, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Fim:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(47, $alt, $cgm->data->pessoa_juridica_info->informacoes_adicionais->lista_periodo_simples->data_fim, 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Optante Mei:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, ($cgm->data->pessoa_juridica_info->informacoes_adicionais->optante_mei ? 'Sim' : 'Não'), 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, "Situação Especial: ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(100, $alt, $cgm->data->pessoa_juridica_info->situacao_especial, 1, 0, "L", 0);
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(27, $alt, "Data Situação:  ", 1, 0, "L", 0);
    $pdf->setfont('arial', '', 6);
    $pdf->cell(122, $alt, $cgm->data->pessoa_juridica_info->data_situacao_especial, 1, 1, "L", 0);

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(279, $alt, "Sócios", 1, 1, "C", 1);

    foreach ($cgm->data->pessoa_juridica_info->socios as $socio) {
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(30, $alt, "Nome:  ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(50, $alt, $socio->nome, 1, 0, "L", 0);
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(20, $alt, "CPF/CNPJ: ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(30, $alt, $socio->cpf, 1, 0, "L", 0);

        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(27, $alt, "Qualificacao: ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);

        $qualificacao = array(
            '05' => 'Administrador',
            '08' => 'Conselheiro de Administração',
            '10' => 'Diretor',
            '16' => 'Presidente',
            '17' => 'Procurador',
            '18' => 'Secretário',
            '20' => 'Sociedade Consorciada',
            '21' => 'Sociedade Filiada',
            '22' => 'Sócio',
            '23' => 'Sócio Capitalista',
            '24' => 'Sócio Comanditado',
            '25' => 'Sócio Comanditário',
            '26' => 'Sócio de Indústria',
            '28' => 'Sócio-Gerente',
            '29' => 'Sócio Incapaz ou Relat.Incapaz (exceto menor)',
            '30' => 'Sócio Menor (Assistido/Representado)',
            '31' => 'Sócio Ostensivo',
            '33' => 'Tesoureiro',
            '37' => 'Sócio Pessoa Jurídica Domiciliado no Exterior',
            '38' => 'Sócio Pessoa Física Residente ou Domiciliado no Exterior',
            '47' => 'Sócio Pessoa Física Residente no Brasil',
            '48' => 'Sócio Pessoa Jurídica Domiciliado no Brasil',
            '49' => 'Sócio-Administrador',
            '52' => 'Sócio com Capital',
            '53' => 'Sócio sem Capital',
            '54' => 'Fundador',
            '55' => 'Sócio Comanditado Residente no Exterior',
            '56' => 'Sócio Comanditário Pessoa Física Residente no Exterior',
            '57' => 'Sócio Comanditário Pessoa Jurídica Domiciliado no Exterior',
            '58' => 'Sócio Comanditário Incapaz',
            '59' => 'Produtor Rural',
            '63' => 'Cotas em Tesouraria',
            '65' => 'Titular Pessoa Física Residente ou Domiciliado no Brasil',
            '66' => 'Titular Pessoa Física Residente ou Domiciliado no Exterior',
            '67' => 'Titular Pessoa Física Incapaz ou Relativamente Incapaz (exceto menor)',
            '68' => 'Titular Pessoa Física Menor (Assistido/Representado)',
            '70' => 'Administrador Residente ou Domiciliado no Exterior',
            '71' => 'Conselheiro de Administração Residente ou Domiciliado no Exterior',
            '72' => 'Diretor Residente ou Domiciliado no Exterior',
            '73' => 'Presidente Residente ou Domiciliado no Exterior',
            '74' => 'Sócio-Administrador Residente ou Domiciliado no Exterior',
            '75' => 'Fundador Residente ou Domiciliado no Exterior',
            '76' => 'Protetor',
            '77' => 'Vice-Presidente',
            '78' => 'Titular Pessoa Jurídica Domiciliada no Brasil',
            '79' => 'Titular Pessoa Jurídica Domiciliada no Exterior'
        );

        $pdf->cell(48, $alt, $qualificacao[$socio->qualificacao], 1, 0, "L", 0);

        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(27, $alt, "Data Inclusão:  ", 1, 0, "L", 0);
        $pdf->setfont('arial', '', 6);
        $pdf->cell(47, $alt, $socio->data_inclusao ? date('d/m/Y', strtotime($socio->data_inclusao)) : '', 1, 1, "L", 0);
    }
}

$pdf->Output();
