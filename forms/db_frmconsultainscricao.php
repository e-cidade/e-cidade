<?php
use App\Models\ISSQN\RedesimSettings;

$parametrosRedesim = RedesimSettings::query()->first();

?>

<script>
    function mostraJanelaPesquisa() {
        const F = document.form1;
        if (F.numInscricao.value.length > 0) {
            frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=' + F.numInscricao.value;
            frameDadosInscricao.mostraMsg();
            frameDadosInscricao.show();
            frameDadosInscricao.focus();
        } else if (F.cgccpf.value.length > 0) {
            frameDadosInscricao.jan.location.href = 'iss3_consinscr002.php?pesquisaCgcCpf=' + F.cgccpf.value;
            frameDadosInscricao.mostraMsg();
            frameDadosInscricao.show();
            frameDadosInscricao.focus();
        } else if (F.inscricaoredesim && F.inscricaoredesim.value.length > 0) {
            frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?inscricaoredesim=' + F.inscricaoredesim.value;
            frameDadosInscricao.mostraMsg();
            frameDadosInscricao.show();
            frameDadosInscricao.focus();
        } else if (F.referenciaanterior.value.length > 0) {
            frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?referenciaanterior=' + F.referenciaanterior.value;
            frameDadosInscricao.mostraMsg();
            frameDadosInscricao.show();
            frameDadosInscricao.focus();
        } else if (F.razaoSocial.value.length > 0) {
            frameListaRazaoSocial.jan.location.href = 'func_nome.php?funcao_js=parent.mostraTodasInscricoes_PesquisaPorNome|0&lCadTec=1&nomeDigitadoParaPesquisa=' + F.razaoSocial.value;
            frameListaRazaoSocial.mostraMsg();
            frameListaRazaoSocial.show();
            frameListaRazaoSocial.focus();
        } else if (F.escritorio.value.length > 0) {
            frameEscritorio.jan.location.href = 'func_escritorio.php?funcao_js=parent.mostraTodasInscricoes_PesquisaEscritorio|0&lCadTec=1&nomeDigitadoParaPesquisa=' + F.escritorio.value;
            frameEscritorio.mostraMsg();
            frameEscritorio.show();
            frameEscritorio.focus();
        } else if (F.codRua.value.length > 0) {
            frameListaRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasInscricoes_PesquisaRuas|0&codrua=' + F.codRua.value;
            frameListaRuas.mostraMsg();
            frameListaRuas.show();
            frameListaRuas.focus();
        } else if (F.nomeRua.value.length > 0) {
            frameListaRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasInscricoes_PesquisaRuas|0&nomeRua=' + F.nomeRua.value;
            frameListaRuas.mostraMsg();
            frameListaRuas.show();
            frameListaRuas.focus();
        } else if (F.codBairro.value.length > 0) {
            frameListaBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasInscricoes_PesquisaBairro|0&codbairro=' + F.codBairro.value;
            frameListaBairros.mostraMsg();
            frameListaBairros.show();
            frameListaBairros.focus();
        } else if (F.nomeBairro.value.length > 0) {
            frameListaBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasInscricoes_PesquisaBairro|0&nomeBairro=' + F.nomeBairro.value;
            frameListaBairros.mostraMsg();
            frameListaBairros.show();
            frameListaBairros.focus();
        } else if (F.atividade.value.length > 0) {
            frameListaAtividades.jan.location.href = 'func_atividades.php?funcao_js=parent.mostraTodasInscricoes_PesquisaAtividades|0&nomeDigitadoParaPesquisa=' + F.atividade.value;
            frameListaAtividades.mostraMsg();
            frameListaAtividades.show();
            frameListaAtividades.focus();
        } else if (F.socios.value.length > 0) {
            frameListaSocios.jan.location.href = 'func_socios.php?funcao_js=parent.mostraTodasInscricoes_PesquisaSocios|0&nomeDigitadoParaPesquisa=' + F.socios.value;
            frameListaSocios.mostraMsg();
            frameListaSocios.show();
            frameListaSocios.focus();
        } else if (F.fantasia.value.length > 0) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'frameListaFantasia', 'func_nomefantasia.php?funcao_js=parent.mostraTodasInscricoes_PesquisaFantasia|0&nomeDigitadoParaPesquisa=' + F.fantasia.value, 'Pesquisa', true, 23);
        } else if (F.matriculaImovel.value.length > 0) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'frameListaMatriculaImovel', 'iss3_consinscr002.php?pesquisaMatriculaImovel=' + F.matriculaImovel.value, 'Lista Matricula', true, 23);

        } else if ((F.setor.value.length > 0) || (F.quadra.value.length > 0) || (F.lote.value.length > 0)) {

            js_OpenJanelaIframe('CurrentWindow.corpo', 'frameListaSetQuaLot', 'func_iptubase.php?'
                + 'funcao_js=parent.mostraTodasInscricoes_PesquisaSetQuaLot'
                + '&j34_setor=' + F.setor.value
                + '&j34_quadra=' + F.quadra.value
                + '&j34_lote=' + F.lote.value
                + '&PesquisaSetQuaLot=1', 'Pesquisa', true, 23);
        }
        F.reset();
    }

    function mostraDadosInscricao(numeroissqn) {
        frameDadosInscricao.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=' + numeroissqn;
        frameDadosInscricao.mostraMsg();
        frameDadosInscricao.show();
        frameDadosInscricao.focus();
    }

    function mostraTodasInscricoes_PesquisaPorNome(numerocgm) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaPorNome=' + numerocgm;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaFantasia(numerocgm) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaPorNomeFantasia=' + numerocgm;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaEscritorio(numerocgm) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaEscritorio=' + numerocgm;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaRuas(rua) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaRua=' + rua;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaAtividades(codAtividade) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaAtividade=' + codAtividade;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaSocios(cgmsocio) {
        frameListaInscricoes.jan.location.href = 'iss3_consinscr002.php?pesquisaSocios=' + cgmsocio;
        frameListaInscricoes.mostraMsg();
        frameListaInscricoes.show();
        frameListaInscricoes.focus();
    }

    function mostraTodasInscricoes_PesquisaBairro(bairro) {
        frameListaBairros.jan.location.href = 'iss3_consinscr002.php?pesquisaBairro=' + bairro;
        frameListaBairros.mostraMsg();
        frameListaBairros.show();
        frameListaBairros.focus();
    }

    function mostraTodasInscricoes_PesquisaSetQuaLot(numeroMatricula) {
        frameListaSetQuaLot.jan.location.href = 'iss3_consinscr002.php?pesquisaMatriculaImovel=' + numeroMatricula;
        frameListaSetQuaLot.mostraMsg();
        frameListaSetQuaLot.show();
        frameListaSetQuaLot.focus();
    }

</script>

<style>
    .table {
        display: flex;
        justify-content: center;
        text-align: left;
    }
    .action {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
</style>

<fieldset style="margin-top: 20px;">
    <legend><b>Consulta Cadastro Municipal</b></legend>
    <div class="table">
        <form name="form1" method="post" action="">
            <table>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Inscri��o :</strong></td>
                    <td nowrap>
                        <input name="numInscricao" type="text" id="matricula3"
                               onBlur="js_ValidaCamposText(this,1);" size="10" maxlength="8">
                    </td>
                </tr>
                <?php if ($parametrosRedesim->q180_active) { ?>
                    <tr>
                        <td nowrap>
                            <strong>Inscri��o REDESIM:</strong></td>
                        <td nowrap>
                            <input name="inscricaoredesim" type="text" id="inscricaoredesim" size="10"
                                   maxlength="10">
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td nowrap>
                        <strong>Referencia anterior:</strong></td>
                    <td nowrap>
                        <input name="referenciaanterior" type="text" id="referenciaanterior" size="10"
                               maxlength="10">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>CPF/CNPJ:</strong></td>
                    <td nowrap>
                        <input name="cgccpf" type="text" id="cgccpf" size="14"
                               onkeyup='js_ValidaCampos(this,1,"CPF/CNPJ","","",event);'
                               oninput="js_ValidaCampos(this,0,'CNPJ/CPF','t','t',event);"
                               onkeydown="return js_controla_tecla_enter(this,event);" maxlength="14">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Raz�o social</strong>:
                    </td>
                    <td nowrap>
                        <input name="razaoSocial" type="text" id="razaoSocial" size="50" maxlength="40">
                    </td>
                </tr>
                <!-- coloquei novo campo no filtro para filtrar por nome fantasia "Robson"-->
                <tr>
                    <td nowrap>
                        <strong>Nome fantasia</strong>:
                    </td>
                    <td nowrap>
                        <input name="fantasia" type="text" id="fantasia" size="50" maxlength="40">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Escrit�rio de contabilidade:</strong></td>
                    <td nowrap>
                        <input name="escritorio" type="text" id="escritorio" size="50" maxlength="40">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>C�digo da rua :</strong>
                    </td>
                    <td nowrap>
                        <input name="codRua" type="text" id="codRua" onBlur="js_ValidaCamposText(this,1)"
                               size="8" maxlength="7">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Nome da rua :</strong>
                    </td>
                    <td nowrap>
                        <input name="nomeRua" onkeydown="return js_controla_tecla_enter(this,event);"
                               oninput="js_ValidaCampos(this,0,'Logradouro','t','t',event);" type="text"
                               style="text-transform:uppercase;" onblur="js_ValidaMaiusculo(this,'t',event);"
                               id="nomeRua" size="50" maxlength="40">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>C�digo do bairro :</strong>
                    </td>
                    <td nowrap>
                        <input name="codBairro" type="text" id="codBairro" onBlur="js_ValidaCamposText(this,1)"
                               size="5" maxlength="4">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Nome do bairro:</strong>
                    </td>
                    <td nowrap>
                        <input name="nomeBairro" type="text" id="nomeBairro" size="50" maxlength="40">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Matr�cula do im�vel:</strong>
                    </td>
                    <td nowrap>
                        <input name="matriculaImovel" type="text" id="matriculaImovel"
                               onBlur="js_ValidaCamposText(this,1);" size="10" maxlength="8">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Setor/Quadra/Lote:</strong>
                    </td>
                    <td nowrap>
                        <input name="setor" type="text" id="setor" size="5" maxlength="4"> /
                        <input name="quadra" type="text" id="quadra" size="5" maxlength="4"> /
                        <input name="lote" type="text" id="lote" size="5" maxlength="4">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>Atividade:</strong>
                    </td>
                    <td nowrap>
                        <input name="atividade" type="text" id="atividade">
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        <strong>S�cios:</strong>
                    </td>
                    <td nowrap>
                        <input name="socios" type="text" id="socios" size="50" maxlength="40">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</fieldset>

<div class="action">
    <input name="pesquisar" type="button" onClick="mostraJanelaPesquisa()" id="pesquisar" value="Pesquisar">
</div>
