<?

require_once 'libs/renderComponents/index.php';

//MODULO: compras
$clpcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc04_descrsubgrupo");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc03_codgrupo");
$clrotulo->label("pc04_codsubgrupo");
$clrotulo->label("pc01_validademinima");
$clrotulo->label("pc01_obrigatorio");
$vaiIframe = "";

/**
 * Funcao que retorna se o item esta em uma solicitação, autorização ou empenho para permitir ou não a alteração do item como seriviço sim ou não.
 * @param $iItem
 * @return bool
 */
function verPermissaoAlteraServico($iItem)
{
    $clsolicitem = new cl_solicitem;
    $clempautitem = new cl_empautitem;
    $clempempitem = new cl_empempitem;
    $rSql2 = db_query($clempautitem->sql_query(null, null, "e54_autori,e54_emiss,e54_anulad,descrdepto,e55_quant,e55_vlrun,e55_vltot", null, "e55_item=$iItem"));
    $rSql3 = db_query($clempempitem->sql_query(null, null, "e60_numemp,e60_codemp,e60_emiss,nome,e62_quant,e62_vlrun,e62_vltot", null, "e62_item=$iItem"));
    $rSql1 = db_query($clsolicitem->sql_query_pcmater(null, "pc10_numero,pc10_data,descrdepto,nome,pc11_seq, pc11_quant,pc11_vlrun,pc11_vlrun*pc11_quant as dl_Valor", "pc10_numero desc", "pc16_codmater=$iItem"));
    if (pg_num_rows($rSql1) > 0 or pg_num_rows($rSql2) > 0 or pg_num_rows($rSql3) > 0) {
        return true;
    } else {
        return false;
    }
}
$oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));
$l12_pncp = $oParamLicicita[0]->l12_pncp;
?>
<script>
    function js_troca() {
        document.form1.submit();
    }

    function js_executaIframe(val) {
        ele = document.form1.codeles.value;
        mat = document.form1.pc01_codmater.value;
        opc = <?= $db_opcao ?>;
        pcmater0011.location.href = 'com1_pcmater0011.php?db_opcao=' + opc + '&codigomater=' + mat + '&codsubgrupo=' + val + '&codele=' + ele;
    }
</script>
<style>
    #pc01_regimobiliario{background-color:#e6e4f1}#pc01_codgrupo,#pc01_codsubgrupo{width:60px}#pc01_codgrupodescr,#pc01_codsubgrupodescr{width:280px}
</style>

<form name="form1" method="post" action="" onsubmit="return js_check()">
        <input type="hidden" name="codeles" value=<?= @$coluna ?>>

    <fieldset style="width:100%;">
            <legend>Materiais/Serviços</legend>
            <div class="row">
                <div class="col-sm-2 form-group">
                        <label><b>Código do Item: </b></label>
                        <?php
                            db_input(
                                'pc01_codmater',
                                10,
                                1,
                                true,
                                'text',
                                3,
                                "",
                                "",
                                "",
                                "",
                                null,
                                "form-control"
                            );
                            $pc01_id_usuario = db_getsession("DB_id_usuario");
                            db_input('pc01_id_usuario', 6, $Ipc01_id_usuario, true, 'hidden', 3, "");
                        ?>

                    <?
                        $arrlibaut_truefalse = array('t' => 'Sim', 'f' => 'Não');
                        db_select("pc01_libaut", $arrlibaut_truefalse, true, 1, "hidden");
                        $aValMin = array("f" => "Não", "t" => "Sim");
                        db_select("pc01_validademinima", $aValMin, true, 1, "hidden");
                        $aPObrigatorio = array("f" => "Não", "t" => "Sim");
                        db_select("pc01_obrigatorio", $aPObrigatorio, true, 1, "hidden");
                        $aLiberarResumo = array("t" => "Sim","f" => "Não");
                        db_select("pc01_liberaresumo", $aLiberarResumo, true, 1, "hidden");
                        $aVeic = array("f" => "Não", "t" => "Sim");
                        db_select("pc01_veiculo", $aVeic, true, 1, "hidden");
                    ?>


                </div>

                <div class="col-sm-6 form-group">
                </div>


                <div class="col-sm-2 form-group">
                        <label><b>Data: </b></label>
                        <?php
                            if ($db_opcao == 1) {
                                $pc01_data = date("d/m/Y", db_getsession("DB_datausu"));
                            } else{
                                $pc01_data = implode('/', array_reverse(explode('-', $pc01_data)));
                            }
                            db_input(
                                'pc01_data',
                                10,
                                $pc01_data,
                                true,
                                'text',
                                3,
                                "",
                                "",
                                "",
                                "",
                                null,
                                "form-control"
                            );
                        ?>
                </div>

                <div class="col-sm-2 form-group">
                <label><b>Inativo: </b></label>
                    <?db_select("pc01_ativo", array('f' => 'Não', 't' => 'Sim'), true, $db_opcao,"class='custom-select'");?>
                </div>

            </div>

            <div class="row" style="margin-top:20px;">

                <div class="col-sm-12 form-group">
                   <b> Descrição do Item: </b>
                    <? db_textarea('pc01_descrmater', 3, 140, $Ipc01_descrmater, true, 'text', $db_opcao, "onkeyup = 'return js_validaCaracteres(this.value, pc01_descrmater.id)';", '', '', '80') ?>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-12 form-group">
                   <b> Complemento da Descrição: </b>
                    <? db_textarea('pc01_complmater', 3, 140, $Ipc01_complmater, true, 'text', $db_opcao, "onkeyup = 'return js_validaCaracteres(this.value, pc01_complmater.id)';", '', '', '') ?>
                </div>

            </div>

            <div class="row" style="margin-top:20px;">

                <div class=" col-sm-3 form-group">
                    <label><b>Tipo: </b></label>
                    <?

                    if ($db_opcao == 1) {
                        $x = array("selecione" => "Selecione", "f" => "Material", "t" => "Serviço/Material Permanente");
                    } else {
                        $x = array("f" => "Material", "t" => "Serviço/Material Permanente");
                    }
                    if (isset($pc01_codmater)) {
                        if (verPermissaoAlteraServico($pc01_codmater)) {
                            db_select("pc01_servico", $x, true,3,"class='custom-select'");
                        } else {
                            db_select("pc01_servico", $x, true, $db_opcao,"class='custom-select'");
                        }
                    } else {
                        db_select("pc01_servico", $x, true, $db_opcao,"class='custom-select'");
                    }

                    ?>
                </div>

                <div class=" col-sm-4 form-group">
                <label><b>Unidade de Medida: </b></label>
                    <?
                        $aUnidades = array();

                            $rsMatunid = db_query("SELECT m61_codmatunid, m61_descr
                            FROM (
                                SELECT 0 AS m61_codmatunid, 'Selecione' AS m61_descr
                                UNION ALL
                                SELECT m61_codmatunid, m61_descr
                                FROM matunid
                            where m61_ativo='t') AS result
                            ORDER BY
                                CASE WHEN m61_descr = 'Selecione' THEN 0 ELSE 1 END,
                                m61_descr;");

                            for ($i = 0; $i < pg_numrows($rsMatunid); $i++) {
                                if(($db_opcao == 2 || $db_opcao == 3) && $pc01_unid != null){
                                    if($i==0)continue;
                                }
                                $matunid = db_utils::fieldsMemory($rsMatunid, $i);
                                $aUnidades[$matunid->m61_codmatunid] = $matunid->m61_descr;
                            }

                        if(($db_opcao == 2 || $db_opcao == 3) && $pc01_unid == null){
                            db_select("pc01_unid", $aUnidades, true, 3,"class='custom-select'");
                        }else{

                            if (isset($pc01_codmater)) {
                                if (verPermissaoAlteraServico($pc01_codmater)) {
                                    db_select("pc01_unid", $aUnidades, true, 3,"class='custom-select'");
                                } else {
                                    db_select("pc01_unid", $aUnidades, true, 1,"class='custom-select'");
                                }
                            } else {
                                db_select("pc01_unid", $aUnidades, true, 1,"class='custom-select'");
                            }

                        }

                    ?>
                </div>

                <div class=" col-sm-2 form-group">
                    <label><b>Item p/Obra/Serviço: </b></label>
                    <?db_select("pc01_obras", array('f' => 'Não', 't' => 'Sim'), true, $db_opcao,"class='custom-select'");?>
                </div>

                <div class=" col-sm-1 form-group">
                    <label><b>Tabela: </b></label>
                    <?db_select("pc01_tabela", array('f' => 'Não', 't' => 'Sim'), true, $db_opcao,"class='custom-select'");?>
                </div>

                <div class=" col-sm-1 form-group">
                    <b> Taxa: </b>
                    <?db_select("pc01_taxa", array('f' => 'Não', 't' => 'Sim'), true, $db_opcao,"class='custom-select'");?>
                </div>

                <div class=" col-sm-1 form-group">
                    <b> Fraciona: </b>
                    <?db_select("pc01_fraciona", array('f' => 'Não', 't' => 'Sim'), true, $db_opcao,"class='custom-select'");?>
                </div>


            </div>
            <div class="row" style="margin-top:20px;">
              <b style="margin-right: 10px;">Código do Grupo: </b>


                <?
                    if (!isset($pc01_codgrupo)) {
                    if (!isset($pc03_codgrupo)) {
                        if (isset($pc01_codsubgrupo) &&  ($db_opcao == 2 || $db_opcao == 3)) {
                        global $pc01_codgrupo;
                        $result = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query($pc01_codsubgrupo, "pc04_codgrupo as pc01_codgrupo", null, "pc04_codsubgrupo=$pc01_codsubgrupo and pc04_ativo is true and pc04_instit in (". db_getsession('DB_instit').",0)"));
                        if ($clpcsubgrupo->numrows > 0) {
                            db_fieldsmemory($result, 0);
                        }
                        }
                    }
                    }
                    $result = $clpcgrupo->sql_record($clpcgrupo->sql_query(null, "pc03_codgrupo,pc03_descrgrupo", "pc03_descrgrupo", "pc03_ativo is true and pc03_instit in (". db_getsession('DB_instit').",0)"));
                    @db_selectrecord("pc01_codgrupo", $result, true, $db_opcao, "", "", "", "0", "js_troca(this.value);");
                ?>



                <?
                     if (isset($pc01_codgrupo) || $db_opcao != 1) {
                        echo " <b style='margin-right: 10px; margin-left:10px;'>Código do SubGrupo: </b> ";
                    $sWhere = "pc04_codgrupo = " . @$pc01_codgrupo . " and pc04_ativo is true and (pc04_tipoutil=1 or pc04_tipoutil=3)";
                    if (!isset($pc01_codgrupo)) {
                        $sWhere = "pc04_ativo is true and (pc04_tipoutil=1 or pc04_tipoutil=3)";
                    }
                    $result = $clpcsubgrupo->sql_record(
                        $clpcsubgrupo->sql_query(
                            null,
                            "pc04_codsubgrupo as subgrupo,pc04_descrsubgrupo",
                            "pc04_descrsubgrupo",
                            $sWhere
                        )
                    );
                    if ($clpcsubgrupo->numrows > 0) {

                        db_fieldsmemory($result, 0);
                        $pc04_codsubgrupo = $subgrupo;
                        if (isset($impmater)) {
                            $result_coluna = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($impmater, null, "pc07_codele"));
                            $numrows_coluna = $clpcmaterele->numrows;
                            $separa = "";
                            $coluna = "";
                            for ($i = 0; $i < $numrows_coluna; $i++) {
                                db_fieldsmemory($result_coluna, $i);
                                $coluna .= $separa . $pc07_codele;
                                $separa  = "XX";
                            }
                            //db_msgbox($subgrupo);
                            $vaiIframe = "?db_opcao=$db_opcao&codigomater=" . $impmater . "&impmater=impmater&codsubgrupo=" . $subgrupo . "&codele=" . $coluna;
                        } else {
                            //db_msgbox($subgrupo);
                            //db_msgbox($pc01_codmater);
                            if (!isset($pc01_codmater)) {
                                $pc01_codmater = '';
                            }
                            $vaiIframe = "?db_opcao=$db_opcao&codigomater=" . $pc01_codmater . "&codsubgrupo=" . @$subgrupo . "&codele=" . @$coluna;
                        }
                    }
                    @db_selectrecord("pc01_codsubgrupo", $result, true, $db_opcao, "", "", "", "", "js_executaIframe(this.value)");

                }
                ?>

            </div>


            <?php if ($l12_pncp == 't') : ?>

            <div class="row" style="margin-top:20px;">

                <div class="col-sm-12 form-group">
                <b> Reg. Imobiliário: </b>
                <? db_textarea('pc01_regimobiliario', 3, 140, '', true, 'text', $db_opcao, "onkeyup = 'return js_validaCaracteres(this.value, pc01_justificativa.id)';", '', '', '255');?>
                </div>

            </div>

            <?php endif ?>

            <? if ($db_opcao == 22 || $db_opcao == 2) : ?>
                <div class="row" style="margin-top:20px;">
                    <div class="col-sm-12 form-group">
                    <b>Justificativa da Alteração:</b>
                        <? db_textarea('pc01_justificativa', 0, 140, '', true, 'text', $db_opcao, "onkeyup = 'return js_validaCaracteres(this.value, pc01_justificativa.id)';", '', '', '100'); ?>
                    </div>
                </div>
           <? endif; ?>


            <div  style="margin-top:20px;">
                <fieldset>
                    <legend>Lista de Desdobramentos:</legend>
                    <iframe style="width:100%;" height="200" name="pcmater0011" src="com1_pcmater0011.php<?= $vaiIframe ?>"></iframe>
                    <?
                     if ($db_opcao != 1) { ?>
                    <div class="row" style="background-color: #CCFF99;">
                        <strong>*** Elementos que não podem ser <?= $db_opcao == 2 ? " alterados " : " excluídos " ?> por estar na autorização de empenho.</strong>
                     </div>
                    <? } ?>
                </fieldset>
            </div>



    </fieldset>

    <div style="margin-top:15px;">


         <?php

         $nomeOpcao =  $db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir");

        $component->render('buttons/solid', [
          'type' => 'submit',
          'designButton' => 'success',
          'size' => 'sm',
          'onclick' => ($db_opcao == 2 || $db_opcao == 1 ? "return js_coloca();" : ""),
          'message' => $nomeOpcao,
          'value' => $nomeOpcao,
          'name' => "db_opcao",
          'id' => "db_opcao",
        ]);

        $component->render('buttons/solid', [
            'type' => 'button',
            'designButton' => 'primary',
            'size' => 'sm',
            'onclick' => "js_pesquisa();",
            'message' => "Pesquisar",
            'name' => "pesquisar",
            'id' => "pesquisar",
          ]);

          if($db_opcao == 1){
            $result_pcmater = $clpcmater->sql_record($clpcmater->sql_query_file());
            if ($clpcmater->numrows > 0) {
            $component->render('buttons/solid', [
                'type' => 'button',
                'designButton' => 'primary',
                'size' => 'sm',
                'onclick' => "js_janelaimporta();",
                'message' => "Importar material",
                'name' => "importar",
                'id' => "importar",
              ]);

            }

          }

        ?>

    <div>

</form>

<script>
var elementIds = [
    'pc01_unid_select_descr',
    'pc01_obras_select_descr',
    'pc01_tabela_select_descr',
    'pc01_taxa_select_descr',
    'pc01_fraciona_select_descr',
    'pc04_descrsubgrupo',
    'pc01_ativo_select_descr',
    'pc01_servico_select_descr',
    'pc01_codgrupo',
    'pc01_codsubgrupo',
    'pc01_codgrupodescr',
    'pc01_codsubgrupodescr'
];

elementIds.forEach(function(id) {
    var element = document.getElementById(id);
    if (element) {
        element.classList.add('form-control');
    }
});



</script>

<script>
    function js_check() {
        if (document.form1.pc01_obras.value == 0) {
            alert('Campo Material Utilizado em Obras/serviços? não informado.');
            document.form1.pc01_obras.focus();
            return false;
        }
        return true
    }

    /**
     * limitar caracteres de textarea
     */
    function textCounter() {
        var field = document.form1.pc01_complmater;
        var maxlimit = 250;
        if (field.value.length > maxlimit) {
            field.value = field.value.substring(0, maxlimit);
        }
    }


    function js_coloca(codele) {

        if (document.getElementById('pc01_servico').value == 'selecione') {
            alert("Usuário: Selecione o tipo do item.");
            return false;
        }

        obj = pcmater0011.document.form1;
        var coluna = '';
        var sep = '';

        for (i = 0; i < obj.length; i++) {
            nome = obj[i].name.substr(0, 10);
            if (nome == "o56_codele" && obj[i].checked == true) {
                coluna += sep + obj[i].value;
                sep = "XX";
            }
        }

        if((document.getElementById('db_opcao').value == "Alterar" || document.getElementById('db_opcao').value == "Incluir") && coluna == ""){
            alert('Usuário: Selecione pelo menos um desdobramento.');
            return false;
        }
        document.form1.codeles.value = coluna;
        return true;
        //return coluna ;
    }

    function js_pesquisapc01_codsubgrupo(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcsubgrupo', 'func_pcsubgrupo.php?funcao_js=parent.js_mostrapcsubgrupo1|pc04_codsubgrupo|pc04_descrsubgrupo', 'Pesquisa', true);
        } else {
            if (document.form1.pc01_codsubgrupo.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcsubgrupo', 'func_pcsubgrupo.php?pesquisa_chave=' + document.form1.pc01_codsubgrupo.value + '&funcao_js=parent.js_mostrapcsubgrupo', 'Pesquisa', false);
            } else {
                document.form1.pc04_descrsubgrupo.value = '';
            }
        }
    }

    function js_mostrapcsubgrupo(chave, erro) {
        document.form1.pc04_descrsubgrupo.value = chave;
        if (erro == true) {
            document.form1.pc01_codsubgrupo.focus();
            document.form1.pc01_codsubgrupo.value = '';
        }
    }

    function js_mostrapcsubgrupo1(chave1, chave2) {
        document.form1.pc01_codsubgrupo.value = chave1;
        document.form1.pc04_descrsubgrupo.value = chave2;
        db_iframe_pcsubgrupo.hide();
    }

    function js_pesquisa() {
        let filtra_atuais = false;
        let opcao = <?= $db_opcao; ?>;

        if (opcao != 1) {
            filtra_atuais = true;
        }
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcmater', 'func_pcmater.php?funcao_js=parent.js_preenchepesquisa|pc01_codmater&vertudo=true&filtra_atuais=' + filtra_atuais, 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {

        document.form1.pc01_codmater.value = chave;
        db_iframe_pcmater.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }

    function js_janelaimporta() {
        qry = "&enviadescr=true";
        if (document.form1.pc01_descrmater.value != "") {
            qry += "&chave_pc01_descrmater=" + document.form1.pc01_descrmater.value;
        }
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcmater', 'func_pcmater.php?funcao_js=parent.js_enviacodmater|pc01_codmater|pc01_descrmater&vertudo=true' + qry, 'Pesquisa', true);
    }

    function js_enviacodmater(chave, descr) {
        db_iframe_pcmater.hide();
        if (chave != "") {
            obj = document.createElement('input');
            obj.setAttribute('name', 'impmater');
            obj.setAttribute('type', 'hidden');
            obj.setAttribute('value', chave);
            document.form1.appendChild(obj);
            document.form1.submit();
        }
    }

    js_executaIframe(document.form1.pc01_codsubgrupo.value);

    <?
    if (isset($vaiIframe) && trim($vaiIframe) != "") {
        //echo "pcmater0011.location.href = 'com1_pcmater0011.php".$vaiIframe."';";
        echo "pcmater0011.document.form1.codsubgrupo.value=document.form1.pc01_codsubgrupo.value;
          pcmater0011.document.form1.submit();";
    }
    ?>

    function js_validaCaracteres(texto, campo) {
        let temporario = '';
        temporario = texto.replace(/\n/g, ' ');

        /*Caracteres não permitidos na descrição e complemento material*/
        let charBuscados = [";", "'", "\"", "\\", "*", ":"];
        let novoTexto = temporario;
        let erro = '';

        charBuscados.map(caractere => {
            if (texto.includes(caractere)) {
                erro = true;
            }
        })


        if (window.event) {
            /* Lança o erro quando a tecla Enter é pressionada. */
            if (window.event.keyCode == 13) {
                erro = true;
                novoTexto = texto.replace(/(\r\n|\n|\r)/g, '');
            }
        }

        /* Remove os caracteres contidos no array charBuscados */
        novoTexto = novoTexto.match(/[^;\*\\\:\"\']/gm);

        for (let cont = 0; cont < novoTexto.length; cont++) {

            /* Remove aspas duplas e simples pelo código, pelo fato de virem de fontes diferentes*/

            if (novoTexto[cont].charCodeAt(0) == 8221 || novoTexto[cont].charCodeAt(0) == 8220 || novoTexto[cont].charCodeAt(0) == 8216) {
                novoTexto[cont] = '';
                erro = true;
            }
        }

        novoTexto = novoTexto.join('');

        switch (campo) {
            case 'pc01_descrmater':
                document.form1.pc01_descrmater.value = novoTexto;
                break;

            case 'pc01_complmater':
                document.form1.pc01_complmater.value = novoTexto;
                break;
        }
    }
</script>
