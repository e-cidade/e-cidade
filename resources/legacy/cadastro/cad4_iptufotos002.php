<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_bemfoto_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);
$clbem = new cl_bens();
$clbem->rotulo->label();
$clbemfoto = new cl_bemfoto();
$db_opcaoal = 1;
$clbemfoto->rotulo->label();

if (isset($oPost->cpf) && trim($oPost->cpf) != "") {
    $lPessoaFisica = true;
} else {
    $lPessoaFisica = false;
}

db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbViewCadEndereco.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
                   datagrid.widget.js");
db_app::load("estilos.css,grid.style.css");
?>

<style>
    .pageFotoscontainer {
        display: flex;
        width: 100%;
        justify-content: center;
    }

    .content {
        display: flex;
        width: 100%;
        flex-direction: column;
    }
</style>

<div class="body-default pageFotoscontainer">
    <form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
        <div class="content">
            <table>
                <tr>
                    <td valign="top">
                        <fieldset>
                            <legend>
                                <strong>Adicionar Foto:</strong>
                            </legend>
                            <table>
                                <tr>
                                    <td valign="top">
                                        <p><b>Arquivo da Foto:</b></p>
                                    </td>
                                    <td valign='top'>
                                        <?php
                                        db_input("uploadfile", 30, 0, true, "file", $db_opcaoal);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Foto ativa:</b></p>
                                    </td>
                                    <td>
                                        <?php
                                        db_select("t54_fotoativa", array("t" => "Sim", "f" => "Não"), true, $db_opcaoal);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>Foto Principal:</b></p>
                                    </td>
                                    <td>
                                        <?php
                                        db_select("t54_principal", array("t" => "Sim", "f" => "Não"), true, 1);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td rowspan="2">
                        <img src="imagens/moveis.png" width="96" height="101" id='preview'>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <input type='button' id='btnSalvar' Value='Salvar'>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend>
                                <strong>Fotos Cadastradas</strong>
                            </legend>
                            <div id='ctnDbGridFotos'>
                            </div>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <strong>Apenas serão aceitas imagens do tipo "JPG", "JPEG" e "PNG" com tamanho máximo de <span style='color:red'>2MB</span>.</strong>
        </div>
    </form>
</div>
<script>
    const iMatric = <?=$oGet->j01_matric?>;
    const sUrlRpc = "web/iptufoto";
    const tela_inativa = '2';

    oGridFotos = new DBGrid('gridFotos');
    oGridFotos.nameInstance = "oGridFotos";
    oGridFotos.setHeight(200);
    oGridFotos.setCellAlign(["right", "center", "center", "center", "center", "center"]);
    oGridFotos.setHeader(["Codigo", "Data", "Pric", "Ativa", "Ver", "Ação"]);
    oGridFotos.show($('ctnDbGridFotos'));

    if (tela_inativa === '33') {
        js_desabilitaCampos();
    }

    function startLoading() {
        js_divCarregando('Aguarde... Carregando Imagem', 'msgbox');
    }

    function endLoading() {
        js_removeObj('msgbox');
    }

    async function js_previewImagem(id, oDiv) {
        startLoading();

        try {
            const response = await fetch(`${sUrlRpc}/show/${id}`, {
                method: 'get',
            });

            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            window.open(result.url, '_blank');

            $('uploadfile').value = '';
        } catch (error) {
            endLoading();
            alert(error);
        } finally {
            endLoading();
        }
    }

    function js_closePreview() {

        $('previewfotogrid').src = '';
        $('ctnDisplayFoto').style.setProperty('display','none');
    }

    async function js_salvarFoto() {

        if ($F('uploadfile') === '') {

            alert('Escolha uma Foto!');
            return false;
        }
        const postBody = {
            iMatric,
            principal: $F('t54_principal') == "t" ? true : false,
            ativa: $F('t54_fotoativa') == "t" ? true : false,
            arquivo: $F('uploadfile')
        };

        const formData = parseObjectToFormData(postBody);

        const file = document.querySelector('#uploadfile').files[0];
        formData.append('image', file);

        js_divCarregando('Aguarde... Salvando Imagem', 'msgbox');

        try {
            const response = await fetch(`${sUrlRpc}/upload`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            alert(result.success);
            $('uploadfile').value = '';
            js_getFotos();
        } catch (error) {
            alert(error);
        } finally {
            endLoading();
        }
    }

    function js_retornoSalvarFoto(oAjax) {

        js_removeObj("msgbox");
        const oRetorno = eval('(' + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            $('uploadfile').value = '';
            js_getFotos();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_getFotos() {
        const oAjax = new Ajax.Request(
            `${sUrlRpc}/list/${iMatric}`,
            {
                method: 'get',
                onComplete: js_retornoGetFotos
            });
    }

    function js_retornoGetFotos(oAjax) {
        const oRetorno = eval('(' + oAjax.responseText + ')');
        oGridFotos.clearAll(true);
        oRetorno.itens.each(function (oFoto, id) {

            const aLinha = [];
            aLinha[0] = oFoto.id;
            aLinha[1] = js_formatar(oFoto.created_at, 'd');
            aLinha[2] = eval("principal" + oFoto.id + " = new DBComboBox('principal" + oFoto.id + "','principal" + oFoto.id + "')");
            aLinha[2].addItem('1', 'Sim')
            aLinha[2].addItem('2', 'Não');
            aLinha[2].setValue(oFoto.j54_principal ? '1' : '2');
            aLinha[3] = eval("ativa" + oFoto.id + " = new DBComboBox('ativa" + oFoto.id + "','ativa" + oFoto.id + "')");
            aLinha[3].addItem('1', 'Sim')
            aLinha[3].addItem('2', 'Não');
            aLinha[3].setValue(oFoto.j54_fotoativa ? '1' : '2');
            aLinha[4] = '<input type="button" value="..." onclick="js_previewImagem(' + oFoto.id + ', this)" >';
            aLinha[5] = '<input type="button" value="A" onclick="js_alterarFoto(' + id + ')">';
            aLinha[5] += '<input type="button" value="E" onclick="js_excluirFoto(' + oFoto.id + ')">';
            oGridFotos.addRow(aLinha);
        });
        oGridFotos.renderRows();
    }

    $('btnSalvar').observe("click", js_salvarFoto);
    js_getFotos();

    async function js_excluirFoto(id) {

        if (!confirm('Confirma a Exclusão da Imagem?')) {
            return false;
        }

        js_divCarregando('Aguarde... excluindo imagem', 'msgbox');
        try {
            const response = await fetch(`${sUrlRpc}/delete/${id}/${iMatric}`, {
                method: 'DELETE',
            });

            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            alert(result.success);
            $('uploadfile').value = '';
            js_getFotos();
        } catch (error) {
            alert(error);
        } finally {
            endLoading();
        }
    }

    async function js_alterarFoto(iRow) {

        const oRow = oGridFotos.aRows[iRow];
        const iFoto = oRow.aCells[0].getValue();
        const lPrincipal = oRow.aCells[2].getValue() === '1';
        const lAtiva = oRow.aCells[3].getValue() === '1';

        const oParam = {
            id: iFoto,
            principal: lPrincipal,
            ativa: lAtiva
        }
        const formData = parseObjectToFormData(oParam);
        js_divCarregando('Aguarde... Alterando imagem', 'msgbox');

        try {
            const response = await fetch(`${sUrlRpc}/update`, {
                method: 'POST',
                body: formData
            });

            js_removeObj("msgbox");

            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            alert(result.success);
            $('uploadfile').value = '';
            js_getFotos();
        } catch (error) {
            alert(error);
        } finally {
            endLoading();
        }
    }

    function js_desabilitaCampos() {
        document.getElementById('ctnDbGridFotos').style['pointer-events'] = 'none';
        document.getElementById('t54_fotoativa').disabled = true;
        document.getElementById('t54_principal').disabled = true;
        document.getElementById('btnSalvar').disabled = true;
        document.getElementById('uploadfile').disabled = true;
    }
</script>
