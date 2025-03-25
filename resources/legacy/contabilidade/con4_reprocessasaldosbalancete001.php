<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

use Illuminate\Support\Facades\DB;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_scripts_classe.php");

// Array de meses para o select do formulário
$aMesReprocessamento = [
    0 => 'Selecione o mês',
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
];


/**
 * Função para realizar o reprocessamento dos saldos.
 *
 * @param int $mesSelecionado Mês selecionado.
 * @param string $mensagem A mensagem a ser atualizada com base no resultado do processamento.
 * @return bool Retorna verdadeiro ou falso ao final do processamento.
 */
function reprocessarSaldosMes(int $mesSelecionado, string &$mensagem): bool
{
    $cl_scripts = new cl_scripts;
    try {
        $anoSessao = db_getsession("DB_anousu");
        $aDadosLancamento = DB::table('contabilidade.conlancamval')
            ->select('conlancamval.c69_data')
            ->whereRaw("date_part('month', c69_data) = ?", [$mesSelecionado])
            ->whereRaw("date_part('year', c69_data) = ?", $anoSessao)
            ->orderBy('c69_data')
            ->limit(1)
            ->get()->toArray();

        $sessionUserId = db_getsession('DB_id_usuario');
        $descrLog = "Reprocessamento saldos balancete - Mês: " . $GLOBALS['aMesReprocessamento'][$mesSelecionado] . "/$anoSessao - Usuario: $sessionUserId";
        $resultadoProcessamento = $cl_scripts->ajustaSaldoContasLancamento(null, $aDadosLancamento[0]->c69_data, $descrLog);
        if ($resultadoProcessamento) {
            $mensagem = "Processamento realizado com sucesso para o mês de " . $GLOBALS['aMesReprocessamento'][$mesSelecionado] . "/$anoSessao.";
            return true;
        }else{
            $mensagem = "Erro no reprocessamento. Verifique.";
            return false;
        }

    } catch (Exception $e) {
        $mensagem = "Erro no processamento: " . $e->getMessage();
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mesSelecionado = $_POST['mes'] ?? null;
    $exibirMensagem = true;

    if (!$mesSelecionado) {
        $mensagem = "Por favor, selecione um mês.";
    } else {
        $mensagem = $mensagem ?? "";
        $sucesso = reprocessarSaldosMes($mesSelecionado, $mensagem);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <title>Contass Contabilidade e Consultoria Ltda - Página Inicial</title>
    <meta http-equiv="expires" content="0">
    <link href="estilos.css" rel="stylesheet" type="text/css" />
    <?php db_app::load('scripts.js, strings.js, prototype.js'); ?>
    <?php if ($exibirMensagem): ?>
        <script>
            alert("<?= addslashes($mensagem) ?>");
        </script>
    <?php endif; ?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
    <fieldset class="container" style="width:350px;">
        <legend id="lgdReprocessarLancamento">Reprocessar Saldos do Balancete</legend>
        <form method="POST" action="con4_reprocessasaldosbalancete001.php">
            <table class="form-container">
                <tr>
                    <td><strong id="lMesReprocessamento">Mês de Referência:</strong></td>
                    <td>
                        <label for="iMesReprocessamento">
                            <select name="mes" id="iMesReprocessamento" style="width: 150px">
                                <?php foreach ($aMesReprocessamento as $key => $mes): ?>
                                    <option value="<?= $key ?>" <?= (isset($mesSelecionado) && $mesSelecionado == $key) ? 'selected' : ''; ?>>
                                        <?= $mes ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </td>
                </tr>
            </table>
            <br />
            <input type="submit" id="proximo" value="Reprocessar" />
        </form>
    </fieldset>
</center>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
