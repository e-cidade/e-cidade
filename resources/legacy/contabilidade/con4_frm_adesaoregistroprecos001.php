<?php
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("dbforms/db_classesgenericas.php");
  include("classes/db_pcparam_classe.php");

  db_postmemory($HTTP_GET_VARS);
  db_postmemory($HTTP_POST_VARS);
?>
<form action="" method="post" name="form1" id="frmAdesaoRegistroPrecos">
  <input type="hidden" name="exec" value="<?= !empty($si06_sequencial)? 'atualizaAdesaoRegistroPrecos' : 'inserirAdesaoRegistroPrecos' ?>">
  <fieldset>
    <legend>Órgão Gerenciador</legend>
    <div class="row">
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_sequencial">Cód. Sequencial:</label>
        <?php
            db_input(
                'si06_sequencial',
                10,
                $si06_sequencial,
                true,
                'text',
                3,
                '',
                '',
                '',
                '',
                '',
                'form-control'
            );
        ?>
      </div>
      <div class="col-12 col-sm-10 form-group mb-2">
        <label for="si06_departamento">
          <?php db_ancora("Cód. Departamento:", "pesquisaDepartamento(true);", 1) ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-3 mb-2 pl-0">
            <?php
                db_input(
                    'si06_departamento',
                    4,
                    '',
                    true,
                    'text',
                    1,
                    " onchange='pesquisaDepartamento(false);' ",
                    '',
                    '',
                    '',
                    '',
                    'form-control',
                    [
                        'validate-required'                 => "true",
                        'validate-minlength'                => "1",
                        'validate-maxlength'                => '10',
                        'validate-no-special-chars'         => 'true',
                        'validate-integer'                  => 'true',
                        'validate-required-message'         => "O campo departamento não foi informado",
                        'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                        'validate-maxlength-message'        => 'O campo departamento deve ter no máximo 10 caracteres',
                        'validate-no-special-chars-message' => 'O campo departamento não deve conter aspas simples, ponto e vírgula ou porcentagem',
                        'validate-integer-message'          => 'O campo departamento deve conter apenas numeros'
                    ]
                );
            ?>
          </div>
          <div class="col-12 col-sm-9 mb-2 pr-0">
              <?php
                  db_input(
                      'descricaodepartamento',
                      40,
                      '',
                      true,
                      'text',
                      3,
                      '',
                      '',
                      '',
                      '',
                      '',
                      'form-control',
                      [
                          'validate-required'          => "true",
                          'validate-minlength'         => "1",
                          'validate-required-message'  => "O campo departamento não foi informado",
                          'validate-minlength-message' => "O campo departamento deve ter pelo menos 1 caracteres",
                      ]
                  );
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-6 form-group mb-2">
        <label for="si06_orgaogerenciador">
          <?php db_ancora("Orgão Gerenciador:", "pesquisaOrgaoGerenciador(true);", 1) ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-3 mb-2 pl-0">
            <?php
              db_input(
                'si06_orgaogerenciador',
                4,
                '',
                true,
                'text',
                1,
                " onchange='pesquisaOrgaoGerenciador(false);' ",
                '',
                '',
                '',
                '',
                'form-control',
                [
                    'validate-required'                 => "true",
                    'validate-minlength'                => "1",
                    'validate-maxlength'                => '10',
                    'validate-no-special-chars'         => 'true',
                    'validate-integer'                  => 'true',
                    'validate-required-message'         => "O campo Orgão Gerenciador não foi informado",
                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                    'validate-maxlength-message'        => 'O campo Orgão Gerenciador deve ter no máximo 10 caracteres',
                    'validate-no-special-chars-message' => 'O campo Orgão Gerenciador não deve conter aspas simples, ponto e vírgula ou porcentagem',
                    'validate-integer-message'          => 'O campo Orgão Gerenciador deve conter apenas numeros'
                ]
              );
            ?>
          </div>
          <div class="col-12 col-sm-9 mb-2 pr-0">
            <?php
              db_input(
                'z01_nomeorg',
                40,
                '',
                true,
                'text',
                3,
                '',
                '',
                '',
                '',
                '',
                'form-control',
                [
                    'validate-required'          => "true",
                    'validate-minlength'         => "1",
                    'validate-required-message'  => "O campo Orgão Gerenciador não foi informado",
                    'validate-minlength-message' => "O campo Orgão Gerenciador deve ter pelo menos 1 caracteres",
                ]
              );
            ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 form-group mb-2">
        <label for="si06_cgm">
          <?php db_ancora('Resp. pela Aprovação:', "pesquisaCgm(true, 'si06_cgm', 'z01_nomeresp');", 1) ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-3 mb-2 pl-0">
            <?php
              db_input(
                'si06_cgm',
                10,
                '',
                true,
                'text',
                1,
                " onchange=\"pesquisaCgm(false, 'si06_cgm', 'z01_nomeresp');\" ",
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-maxlength'                => '10',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required'                 => "true",
                  'validate-minlength'                => "1",
                  'validate-required-message'         => "O campo Resp. pela Aprovação não foi informado",
                  'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                  'validate-maxlength-message'        => 'O Resp. pela Aprovação deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O Resp. pela Aprovação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Resp. pela Aprovação deve conter apenas numeros',
                ]
              );
            ?>
          </div>
          <div class="col-12 col-sm-9 mb-2 pr-0">
            <?php
              db_input(
                'z01_nomeresp',
                45,
                '',
                true,
                'text',
                3,
                '',
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-required'          => "true",
                  'validate-minlength'         => "1",
                  'validate-required-message'  => "O campo Resp. pela Aprovação não foi informado",
                  'validate-minlength-message' => "O nome do Resp. pela Aprovação deve ter pelo menos 1 caracteres",
                ]
              );
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-4 form-group mb-2">
        <label for="si06_leidalicitacao">Lei da Licitação</label>
        <select
          name="si06_leidalicitacao"
          id="si06_leidalicitacao"
          class="custom-select"
          data-validate-required="true"
          data-validate-required-message="O campo Lei de Licitações não foi informado"
        >
          <option value="">Selecione</option>
          <option value="1">1 - Lei 14.133/2021</option>
          <option value="2">2 - Lei 8.666/1993 e outras</option>
        </select>
      </div>
      <div id="containRegimeContracao" class="col-12 col-sm-4 form-group mb-2" style="display: none;">
        <label for="si06_regimecontratacao">Regime de Contratação:</label>
        <select
          name="si06_regimecontratacao"
          id="si06_regimecontratacao"
          class="custom-select"
          data-validate-required="false"
          data-validate-required-message="O campo Regime de Contratação não foi informado"
        >
          <option value="">Selecione</option>
          <option value="1">1 - Contratação por licitação</option>
          <option value="2">2 - Contratação direta por dispensa</option>
          <option value="3">3 - Contratação direta por inexibilidade</option>
        </select>
      </div>
    </div>
    <div class="row">
    <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_numeroprc">Processo Licitatório:</label>
        <?php
          db_input(
              'si06_numeroprc',
              4,
              '',
              true,
              'text',
              1,
              "",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-required'                 => 'true',
                  'validate-maxlength'                => '20',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required-message'         => 'O campo Processo Licitatório não foi informado',
                  'validate-maxlength-message'        => 'O campo Processo Licitatório deve ter no máximo 20 caracteres',
                  'validate-no-special-chars-message' => 'O campo Processo Licitatório não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Processo Licitatório deve conter apenas numeros'
              ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_anoproc">Exercício:</label>
        <?php
          db_input(
              'si06_anoproc',
              4,
              '',
              true,
              'text',
              1,
              "onkeyup='validacaoAno(event, this.value);'",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-required'                 => 'true',
                  'validate-maxlength'                => '4',
                  'validate-minlength'                => '4',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required-message'         => 'O campo Exercício não foi informado',
                  'validate-maxlength-message'        => 'O campo Exercício deve ter no máximo 4 caracteres',
                  'validate-minlength-message'        => 'O campo Exercício deve ter no minimo 4 caracteres',
                  'validate-no-special-chars-message' => 'O campo Exercício não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Exercício deve conter apenas numeros'
              ]
          );
        ?>
      </div>
      <div id="containEdital" class="col-12 col-sm-2 form-group mb-2" style="display: none;">
        <label for="si06_edital">Edital:</label>
        <?php
          db_input(
            'si06_edital',
            10,
            '',
            true,
            'text',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required'                 => 'false',
              // 'validate-maxlength'                => '10',
              'validate-no-special-chars'         => 'false',
              'validate-integer'                  => 'false',
              'validate-required-message'         => 'O campo Edital não foi informado',
              'validate-maxlength-message'        => 'O campo Edital deve ter no máximo 10 caracteres',
              'validate-no-special-chars-message' => 'O campo Edital não deve conter aspas simples, ponto e vírgula ou porcentagem',
              'validate-integer-message'          => 'O campo Edital deve conter apenas numeros'
            ]
          );
        ?>
      </div>
      <div id="containModalidade" class="col-12 col-sm-4 form-group mb-2" style="display: none;">
        <label for="si06_modalidade">Modalidade:</label>
        <select
          name="si06_modalidade"
          id="si06_modalidade"
          class="custom-select"
          data-validate-required="false"
          data-validate-required-message="O campo Modalidade não foi informado"
        >
          <option value="">Selecione</option>
          <option value="3">Pregão Eletrônico</option>
          <option value="2">Pregão Presencial</option>
          <option value="1">Concorrência</option>
        </select>
      </div>      
      <div id="containNumeroModalidade" class="col-12 col-sm-2 form-group mb-2" style="display: none;">
        <label for="si06_numlicitacao">Número Modalidade:</label>
        <?php
          db_input(
            'si06_numlicitacao',
            10,
            '',
            true,
            'text',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required'                 => 'false',
              // 'validate-maxlength'                => '10',
              'validate-no-special-chars'         => 'false',
              'validate-integer'                  => 'false',
              'validate-required-message'         => 'O campo Número Modalidade não foi informado',
              'validate-maxlength-message'        => 'O campo Número Modalidade deve ter no máximo 10 caracteres',
              'validate-no-special-chars-message' => 'O campo Número Modalidade não deve conter aspas simples, ponto e vírgula ou porcentagem',
              'validate-integer-message'          => 'O campo Número Modalidade deve conter apenas numeros'
            ]
          );
        ?>
      </div>
    </div>
    <div class="row">
      <div id="containOrgaoAta" class="col-12 col-sm-4 form-group mb-2" style="display: <?= db_getsession('DB_anousu') >= 2025 ? 'none' : 'block' ?>;">
        <label for="si06_orgarparticipante">Orgão da Ata de Registro de Preço:</label>
        <select
          name="si06_orgarparticipante"
          id="si06_orgarparticipante"
          class="custom-select"
          data-validate-required="false"
          data-validate-required-message="O campo Orgão da Ata de Registro de Preço não foi informado"
        >
          <option value="">Selecione</option>
          <option value="1">Orgão Participante</option>
          <option value="2">Não Participante</option>
        </select>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_dataata">Data da Ata:</label>
        <?php
          db_input(
              'si06_dataata',
              4,
              '',
              true,
              'date',
              1,
              "",
              '',
              '',
              '',
              '',
              'form-control',
              [
                'validate-required' => 'true',
                'validate-date' => 'true',
                'validate-date-message' => 'O campo Data da Ata não foi informado'
              ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_datavalidade">Data da Validade:</label>
        <?php
          db_input(
            'si06_datavalidade',
            4,
            '',
            true,
            'date',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required' => 'true',
              'validate-date' => 'true',
              'validate-date-message' => 'O campo Data da Validade não foi informado'
            ]
          );
        ?>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Órgão de Adesão</legend>
    <div class="row">
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_numeroadm">Número da Adesão:</label>
        <?php
          db_input(
              'si06_numeroadm',
              10,
              '',
              true,
              'text',
              1,
              "",
              '',
              '',
              '',
              16,
              'form-control',
              [
                  'validate-required'                 => 'true',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-maxlength'                => '16',
                  'validate-required-message'         => 'O campo Número da Adesão não foi informado',
                  'validate-no-special-chars-message' => 'O campo Número da Adesão não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Número da Adesão deve conter apenas numeros',
                  'validate-maxlength-message'        => 'O campo Número da Adesão deve ter no máximo 16 caracteres'
              ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_anomodadm">Exercício:</label>
        <?php
          db_input(
              'si06_anomodadm',
              4,
              '',
              true,
              'text',
              1,
              "onkeyup='validacaoAno(event, this.value);'",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-required'                 => 'true',
                  'validate-maxlength'                => '4',
                  'validate-minlength'                => '4',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-minlength-message'        => 'O campo Exercício deve ter no minimo 4 caracteres',
                  'validate-required-message'         => 'O campo Exercício não foi informado',
                  'validate-maxlength-message'        => 'O campo Exercício deve ter no máximo 4 caracteres',
                  'validate-no-special-chars-message' => 'O campo Exercício não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Exercício deve conter apenas numeros'
              ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_nummodadm">Nº da Modalidade:</label>
        <?php
          db_input(
              'si06_nummodadm',
              10,
              '',
              true,
              'text',
              1,
              "",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-required'                 => 'true',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required-message'         => 'O campo Nº da Modalidade não foi informado',
                  'validate-no-special-chars-message' => 'O campo Nº da Modalidade não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Nº da Modalidade deve conter apenas numeros'
              ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_dataabertura">Data da Abertura:</label>
        <?php
          db_input(
            'si06_dataabertura',
            4,
            '',
            true,
            'date',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required' => 'true',
              'validate-date' => 'true',
              'validate-date-message' => 'O campo Data da Abertura não foi informado'
            ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_dataadesao">Data de Adesão:</label>
        <?php
          db_input(
            'si06_dataadesao',
            4,
            '',
            true,
            'date',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required' => 'true',
              'validate-date' => 'true',
              'validate-date-message' => 'O campo Data da Adesão não foi informado'
            ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_publicacaoaviso">Data Pub. Aviso:</label>
        <?php
          db_input(
            'si06_publicacaoaviso',
            4,
            '',
            true,
            'date',
            1,
            "",
            '',
            '',
            '',
            '',
            'form-control opcional',
            [
              'validate-required' => 'false',
              'validate-date' => 'true',
              'validate-date-message' => 'O campo Data de Publicação não foi informado'
            ]
          );
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-3 form-group mb-2">
        <label for="si06_criterioadjudicacao">Critério de Adjudicação</label>
        <select
          name="si06_criterioadjudicacao"
          id="si06_criterioadjudicacao"
          class="custom-select"
          data-validate-required="true"
          data-validate-required-message="O campo Critério de Adjudicação não foi informado"
        >
          <option value="">Selecione</option>
        </select>
      </div>
      <div id="containDescricaoCriterio" class="col-12 col-sm-9 form-group mb-2">
        <label for="si06_descrcriterioutilizado">Descrição Critério Utilizado:</label>
        <?php
          db_input(
            'si06_descrcriterioutilizado',
            4,
            '',
            true,
            'text',
            1,
            "",
            '',
            '',
            150,
            '',
            'form-control',
            [
              'validate-required'                 => 'true',
              'validate-maxlength'                => '150',
              'validate-no-special-chars'         => 'true',
              'validate-required-message'         => "O Descrição Critério Utilizado é obrigatório",
              'validate-maxlength-message'        => 'O Descrição Critério Utilizado ter no máximo 150 caaracteres',
              'validate-no-special-chars-message' => 'O campo Descrição Critério Utilizado não deve conter aspas simples, ponto e vírgula ou porcentagem'
            ]
          );
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-3 form-group mb-2">
        <label for="si06_processoporlote">Processo por Lote</label>
        <select
          name="si06_processoporlote"
          id="si06_processoporlote"
          class="custom-select"
          data-validate-required="true"
          data-validate-required-message="O campo Processo por Lote não foi informado"
        >
          <option value="2">Não</option>
          <option value="1">Sim</option>
        </select>
      </div>
      <div class="col-12 col-sm-3 form-group mb-2">
        <label for="si06_processocompra">
          <?php db_ancora('Processo de Compra', "processoCompra(true);", 1); ?>
        </label>
        <?php
          db_input(
              'si06_processocompra',
              4,
              '',
              true,
              'text',
              1,
              " onchange='processoCompra(false);' ",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-required'                 => "true",
                  'validate-minlength'                => "1",
                  'validate-maxlength'                => '10',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required-message'         => "O campo Processo de Compra não foi informado",
                  'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                  'validate-maxlength-message'        => 'O campo Processo de Compra deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O campo Processo de Compra não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Processo de Compra deve conter apenas numeros'
              ]
          );
        ?>
      </div>
      <div class="col-12 form-group mb-2">
        <label for="si06_objetoadesao">Objeto:</label>
        <?php
          db_textarea(
            'si06_objetoadesao',
            0,
            1,
            '',
            true,
            'text',
            '1',
            'onkeydown="disabledQuebraLinha(event)"',
            '',
            '',
            500,
            'form-control-plaintext form-control-lg',
            [
                'validate-required' => 'true',
                'validate-minlength' => '10',
                'validate-no-special-chars' => 'true',
                'validate-required-message' => 'O campo Objeto não foi informado',
                'validate-minlength-message' => 'A Objeto deve ter pelo menos 10 caracteres',
                'validate-no-special-chars-message' => 'A Objeto não deve conter aspas simples, ponto e vírgula ou porcentagem'
            ]
          );
        ?>
      </div>
    </div>
  </fieldset>
  <div class="row">
    <div class="col-12 text-center mt-4 mb-2">
      <?php if(!empty($si06_sequencial)): ?>
        <button type="submit" class="btn btn-success" id="btnAtualizar">Alterar</button>
      <?php else: ?>
        <button type="submit" class="btn btn-success" id="btnSalvar">Incluir</button>
      <?php endif; ?>
      <button class="btn btn-danger" type="button" id="btnCancelar">Cancelar</button>
    </div>
  </div>
</form>
<script>
  (function (){
    const btnSalvar = document.getElementById('btnSalvar');
    const btnAtualizar = document.getElementById('btnAtualizar');
    const btnCancelar = document.getElementById('btnCancelar');

    const selectCriterioAdjudicacao = document.getElementById('si06_criterioadjudicacao');
    const selectLeiLicitacao = document.getElementById('si06_leidalicitacao');
    const selectRegimeContratacao = document.getElementById('si06_regimecontratacao');
    const inputDescricaoCriterio = document.getElementById('si06_descrcriterioutilizado');

    const validator = initializeValidation('#frmAdesaoRegistroPrecos');
    const eventChange = new Event('change');
    const eventInput = new Event('input');
    const inputsValidateInteger = [
      'si06_departamento',
      'si06_orgaogerenciador',
      'si06_cgm',
      'si06_edital',
      'si06_numeroprc',
      'si06_numlicitacao',
      'si06_anoproc',
      'si06_numeroadm',
      'si06_anomodadm',
      'si06_nummodadm',
      'si06_processocompra'
    ];

    inputsValidateInteger.forEach(function(value, index) {
      document.getElementById(value).addEventListener('input', function(e){
        validateChangeInteger(value)
      });
    });

    function validateChangeInteger(id, integer = true) {
      const inputElement = document.getElementById(id);

      if (!inputElement) return false;

      const validator = initializeValidationInput(inputElement);

      const isValid = validator.validate();
      if (!isValid) {
        if(integer){
          inputElement.value = inputElement.value.replace(/\D/g, '').substr(0, 10);
        }
        return false;
      }

      return true;
    }

    function validateChange(id, value = true) {
      const inputElement = document.getElementById(id);

      if (!inputElement) return false;

      const validator = initializeValidationInput(inputElement);

      const isValid = validator.validate();
      if (!isValid) {
        return false;
      }

      return true;
    }

    function pesquisaDepartamento(mostra){
      if (mostra == true) {
        var sUrl = 'func_db_depart.php?funcao_js=parent.carregaDepartamento|coddepto|descrdepto';
        if(typeof db_iframe_db_depart != 'undefined'){
          db_iframe_db_depart.jan.location.href = sUrl;
          db_iframe_db_depart.setAltura("100%");
          db_iframe_db_depart.setLargura("100%");
          db_iframe_db_depart.setPosX("0");
          db_iframe_db_depart.setPosY("0");
          db_iframe_db_depart.setCorFundoTitulo('#316648');
          db_iframe_db_depart.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_db_depart',
          sUrl,
          'Pesquisar Departamento',
          false,
          '0'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_db_depart.show();
        }
      } else if (document.form1.si06_departamento.value != '' && validateChangeInteger('si06_departamento')) {
        js_OpenJanelaIframe(
          '',
          'db_iframe_db_depart',
          'func_db_depart.php?pesquisa_chave=' + document.form1.si06_departamento.value + '&funcao_js=parent.carregaDepartamento',
          'Pesquisar Departamento',
          false,
          '0'
        );
      } else {
        document.getElementById('descricaodepartamento').value = '';
        document.getElementById('si06_departamento').value = '';
      }
    }

    function carregaDepartamento(cod, desc, error = false){
      if(typeof desc == 'boolean' && !desc){
        document.getElementById('descricaodepartamento').value = cod;
        validateChangeInteger('si06_departamento')
        db_iframe_db_depart.hide();
        return false;
      }

      if(typeof desc == 'boolean' && desc){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum departamento foi encontrado.',
        });
        document.getElementById('si06_departamento').value = '';
        document.getElementById('descricaodepartamento').value = '';
        return false;
      }

      document.getElementById('si06_departamento').value = cod;
      document.getElementById('descricaodepartamento').value = desc;
      validateChangeInteger('si06_departamento');
      db_iframe_db_depart.hide();
    }

    function pesquisaOrgaoGerenciador(mostra) { 
      if(mostra){
        var sUrl = 'func_cgm.php?funcao_js=parent.carregaCgmOrgaoAncora|z01_numcgm|z01_nome';
        if(typeof db_iframe_cgm != 'undefined'){
          db_iframe_cgm.jan.location.href = sUrl;
          db_iframe_cgm.setAltura("100%");
          db_iframe_cgm.setLargura("100%");
          db_iframe_cgm.setPosX("0");
          db_iframe_cgm.setPosY("0");
          db_iframe_cgm.setCorFundoTitulo('#316648');
          db_iframe_cgm.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_cgm',
          sUrl,
          'Pesquisar Orgão Gerenciador',
          false,
          '0'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_cgm.show();
        }
      } else if(document.getElementById('si06_orgaogerenciador').value != '') {
        js_OpenJanelaIframe(
          '', 
          'db_iframe_cgm', 
          'func_cgm.php?pesquisa_chave=' + document.form1.si06_orgaogerenciador.value + '&funcao_js=parent.carregaCgmOrgao', 
          'Pesquisa', 
          false
        );
      } else {
        document.getElementById('si06_orgaogerenciador').value = '';
        document.getElementById('z01_nomeorg').value = '';
      }
    }

    function carregaCgmOrgaoAncora(chave1, chave2){
      document.getElementById('si06_orgaogerenciador').value = chave1;
      document.getElementById('z01_nomeorg').value = chave2;
      db_iframe_cgm.hide();
    }

    function carregaCgmOrgao(erro, chave){
      if(erro == true){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum Orgão CGM foi encontrado',
        });
        document.getElementById('si06_orgaogerenciador').focus();
        document.getElementById('si06_orgaogerenciador').value = '';
        return false;
      }
      
      document.getElementById('z01_nomeorg').value = chave
    }

    function pesquisaCgm(mostra, num, nome){
      numCampo = num;
      nomeCampo = nome;
      if(mostra == true){
        let sUrl = 'func_nome.php?funcao_js=parent.carregaCgmAncora|z01_numcgm|z01_nome&filtro=1';
        if(typeof db_iframe_licitacoes != 'undefined'){
          db_iframe_licitacoes.jan.location.href = sUrl;
          db_iframe_licitacoes.setAltura("100%");
          db_iframe_licitacoes.setLargura("100%");
          db_iframe_licitacoes.setPosX("0");
          db_iframe_licitacoes.setPosY("0");
          db_iframe_licitacoes.setCorFundoTitulo('#316648');
          db_iframe_licitacoes.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_licitacoes',
          sUrl,
          'Pesquisa',
          false,
          '0',
          '1'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_licitacoes.show();
        }
      } else if(validateChangeInteger(numCampo)){
        numcgm = document.getElementById(numCampo).value;
        if (numcgm != '') {
          js_OpenJanelaIframe(
            '',
            'db_iframe_licitacoes',
            'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.carregaCgm&filtro=1',
            'Pesquisa',
            false
          );
        } else {
          document.getElementById(numCampo).value = "";
        }
      } else {
        document.getElementById(numCampo).value = "";
        document.getElementById(nomeCampo).value = "";
      }
    }

    function carregaCgmAncora(cod, desc){
      document.getElementById(numCampo).value = cod;
      document.getElementById(nomeCampo).value = desc;
      validateChangeInteger(numCampo)
      db_iframe_licitacoes.hide();
    }

    function carregaCgm(erro, chave){
      document.getElementById(nomeCampo).value = chave;
      if (erro == true) {
        document.getElementById(numCampo).value = "";
        document.getElementById(nomeCampo).value = "";
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Responsável não encontrado!',
        });
      }
    }

    function validacaoAno(event, ano){
      event.preventDefault();
      if(event.target.value.length > 4){
        event.target.value = event.target.value.substr(0, 4);
      }
    } 

    function processoCompra(mostra){
      if(mostra){
        let sUrl = 'func_pcproc.php?lFiltroPrecoRef=1&adesaoregpreco=1&itemobras=true&criterioadjudicacao=' + document.form1.si06_criterioadjudicacao.value + '&filtrovinculo=true&funcao_js=parent.mostraPcProcAncora|pc80_codproc|pc80_resumo';
        if(typeof db_iframe_pcproc != 'undefined'){
          db_iframe_pcproc.jan.location.href = sUrl;
          db_iframe_pcproc.setAltura("100%");
          db_iframe_pcproc.setLargura("100%");
          db_iframe_pcproc.setPosX("0");
          db_iframe_pcproc.setPosY("0");
          db_iframe_pcproc.setCorFundoTitulo('#316648');
          db_iframe_pcproc.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_pcproc',
          sUrl,
          'Pesquisa',
          false,
          '0',
          '1'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_pcproc.show();
        }
      } else if(document.form1.si06_processocompra.value != '' && validateChangeInteger('si06_processocompra')) {
        js_OpenJanelaIframe(
          '', 
          'db_iframe_pcproc', 
          'func_pcproc.php?lFiltroPrecoRef=1&adesaoregpreco=1&itemobras=true&criterioadjudicacao=' + document.form1.si06_criterioadjudicacao.value + '&filtrovinculo=true&pesquisa_chave=' + document.form1.si06_processocompra.value + '&funcao_js=parent.mostraPcProc', 
          'Pesquisa', 
          false
        );
      } else {
        document.getElementById('si06_processocompra').value = '';
        document.getElementById('si06_objetoadesao').value = '';
      }
    }

    function mostraPcProcAncora(chave1, chave2){
      document.getElementById('si06_processocompra').value = chave1;
      document.getElementById('si06_objetoadesao').value = chave2;
      db_iframe_pcproc.hide();
    }

    function mostraPcProc(chave, chave2, erro){
      if(chave2 == true || erro){
        document.getElementById('si06_processocompra').value = '';
        document.getElementById('si06_objetoadesao').value = '';
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Processo de Compra não encontrado!',
        });
        return false;
      }
      document.getElementById('si06_objetoadesao').value = chave2;
    }

    async function getCriterioAdjudicacao(l20_criterioadjudicacao = 3, triggerChange = false) {
        let oParam = {};
        oParam.exec = 'listagemCriterioAdjudicacao';
        await new Ajax.Request(
          'lic_dispensasinexigibilidades.RPC.php',
          {
            method: 'post',
            asynchronous: true,
            parameters: 'json=' + JSON.stringify(oParam),
            onComplete: function(oAjax){
              let oRetorno = JSON.parse(oAjax.responseText);
              Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                const option = document.createElement('option');
                option.value = iSeq;
                option.text = oValue;

                if(l20_criterioadjudicacao == iSeq){
                    option.selected = true;
                }

                selectCriterioAdjudicacao.appendChild(option);
              })

              if(triggerChange){
                selectCriterioAdjudicacao.dispatchEvent(eventChange);
              }
            }
          }
        )
    }

    function validateToggleRegimeContratacao(si06_leidalicitacao, enabledEdit = true){
      const select = document.getElementById('containRegimeContracao').querySelectorAll('select');
      if(['1', '2'].includes(si06_leidalicitacao)){
        document.getElementById('containRegimeContracao').style.display = 'block';
        select.forEach(select => {
          select.setAttribute('data-validate-required', true);
          if(si06_leidalicitacao == '2'){
            select.selectedIndex = 1;
            select.classList.add('disabled');
          } else {
            select.classList.remove('disabled');
          }
        });

        return false;
      }

      document.getElementById('containRegimeContracao').style.display = 'none';
      select.forEach(select => {
        select.setAttribute('data-validate-required', false);
        select.selectedIndex = 0;
        select.classList.remove('disabled');
      });
    }

    function validateToggleModalidade(si06_leidalicitacao = null, si06_regimecontratacao = null, enabledEdit = true){
      const select = document.getElementById('containModalidade').querySelectorAll('select');
      if(['2'].includes(si06_leidalicitacao) || ['1'].includes(si06_regimecontratacao)){
        document.getElementById('containModalidade').style.display = 'block';
        select.forEach(select => {
          select.setAttribute('data-validate-required', true);
        });

        return false;
      }

      document.getElementById('containModalidade').style.display = 'none';
      select.forEach(select => {
        select.setAttribute('data-validate-required', false);
        select.selectedIndex = 0;
        select.classList.remove('disabled');
      });
    }
    
    function validateToggleNumeroModalidade(si06_leidalicitacao = null, si06_regimecontratacao = null, enabledEdit = true){
      const inputs = document.getElementById('containNumeroModalidade').querySelectorAll('input');
      if(['2'].includes(si06_leidalicitacao) || ['1'].includes(si06_regimecontratacao)){
        document.getElementById('containNumeroModalidade').style.display = 'block';
        inputs.forEach(input => {
          if(input.id == 'si06_numlicitacao'){
            input.setAttribute('data-validate-maxlength', 10)
          }

          input.setAttribute('data-validate-required', true);
          input.setAttribute('data-validate-no-special-chars', true);
          input.setAttribute('data-validate-integer', true);
        });

        return false;
      }

      document.getElementById('containNumeroModalidade').style.display = 'none';
      inputs.forEach(input => {
        if(input.id == 'si06_numlicitacao'){
          input.removeAttribute('data-validate-maxlength')
        }

        input.setAttribute('data-validate-required', false);
        input.setAttribute('data-validate-no-special-chars', false);
        input.setAttribute('data-validate-integer', false);

        input.value = '';
      });
    }

    function validateToggleEdital(si06_leidalicitacao = null, si06_regimecontratacao = null, enabledEdit = true){
      const inputs = document.getElementById('containEdital').querySelectorAll('input');
      if(['2'].includes(si06_leidalicitacao) || ['1'].includes(si06_regimecontratacao)){
        document.getElementById('containEdital').style.display = 'block';
        inputs.forEach(input => {
          if(input.id == 'si06_edital'){
            input.setAttribute('data-validate-maxlength', 10)
          }

          input.setAttribute('data-validate-required', true);
          input.setAttribute('data-validate-no-special-chars', true);
          input.setAttribute('data-validate-integer', true);
        });

        return false;
      }

      document.getElementById('containEdital').style.display = 'none';
      inputs.forEach(input => {
        if(input.id == 'si06_edital'){
          input.removeAttribute('data-validate-maxlength')
        }

        input.setAttribute('data-validate-required', false);
        input.setAttribute('data-validate-no-special-chars', false);
        input.setAttribute('data-validate-integer', false);

        input.value = '';
      });
    }

    function validateToggleDescricaoCriterio(si06_criterioadjudicacao, enabledEdit = true){
      const inputs = document.getElementById('containDescricaoCriterio').querySelectorAll('input');
      if(['3'].includes(si06_criterioadjudicacao)){
        document.getElementById('containDescricaoCriterio').style.display = 'block';
        inputs.forEach(input => {
          if(input.id == 'si06_descrcriterioutilizado'){
            input.setAttribute('data-validate-maxlength', 150)
          }

          input.setAttribute('data-validate-required', true);
          input.setAttribute('data-validate-no-special-chars', true);
        });

        return false;
      }

      document.getElementById('containDescricaoCriterio').style.display = 'none';
      inputs.forEach(input => {
        if(input.id == 'si06_descrcriterioutilizado'){
          input.removeAttribute('data-validate-maxlength')
        }

        input.setAttribute('data-validate-required', false);
        input.setAttribute('data-validate-no-special-chars', false);
        input.value = '';
      });
    }

    function validateData(){
      const dataAta = document.getElementById('si06_dataata').value;
      const dataAbertura = document.getElementById('si06_dataabertura').value;

      if(dataAta > dataAbertura){
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'A data inserida em \'Data da Ata\' não podem ser anteriores à data inserida em \'Data da Abertura\'.',
        });
        return false;
      }

      return true;
    }

    if(selectLeiLicitacao != null){
      selectLeiLicitacao.addEventListener('change', function(e){
        e.preventDefault();
        const value = selectLeiLicitacao.value;

        validateToggleRegimeContratacao(value);
        validateToggleEdital(value);
        validateToggleModalidade(value);
        validateToggleNumeroModalidade(value);

      });
    }

    if(selectRegimeContratacao != null){
      selectRegimeContratacao.addEventListener('change', function(e){
        e.preventDefault();
        validateToggleEdital(selectLeiLicitacao.value, selectRegimeContratacao.value);
        validateToggleModalidade(selectLeiLicitacao.value, selectRegimeContratacao.value);
        validateToggleNumeroModalidade(selectLeiLicitacao.value, selectRegimeContratacao.value);
      });
    }

    if(selectCriterioAdjudicacao != null){
      selectCriterioAdjudicacao.addEventListener('change', function(e){
        e.preventDefault();
        
        validateToggleDescricaoCriterio(selectCriterioAdjudicacao.value);
      });
    }

    if(inputDescricaoCriterio){
      inputDescricaoCriterio.addEventListener('input', function(e){
        if(event.target.value.length > 150){
          validateChange('si06_descrcriterioutilizado');
          event.target.value = event.target.value.substring(0, 150);
          return false;
        }
        validateChange('si06_descrcriterioutilizado');
      });
    }

    if(btnSalvar != null){
      btnSalvar.addEventListener('click', function(e){
        e.preventDefault();
        const formData = serializarFormulario(document.getElementById('frmAdesaoRegistroPrecos'));
        const isValid = validator.validate();
        if(!isValid){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Preencha todos os campos corretamente.',
          });
          return false;
        }

        if(!validateData()){
          return false;
        }

        Swal.fire({
          title: 'Aguarde...',
          text: 'Estamos processando sua solicitação.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        new Ajax.Request(
          url,
          {
            method: 'post',
            parameters: 'json=' + formData,
            onComplete: (oAjax) => {
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.fire({
                  icon: 'success',
                  title: 'Sucesso!',
                  text: 'Adesão salva com sucesso!',
                });

                let currentUrl = new URL(window.location.href);
                parent.loading();
                currentUrl.searchParams.set('si06_sequencial', oRetorno.data.adesao.si06_sequencial);
                window.location.href = currentUrl.toString();
                return false;
              }

              if(oRetorno.status == 400){
                Swal.fire({
                  icon: 'warning',
                  title: 'Atenção!',
                  html: oRetorno.message,
                });
                return false;
              }

              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: oRetorno.message,
              });
            }
          }
        );

      });
    }

    if(btnAtualizar != null){
      btnAtualizar.addEventListener('click', function(e){
        e.preventDefault();
        const formData = serializarFormulario(document.getElementById('frmAdesaoRegistroPrecos'));
        const isValid = validator.validate();
        if(!isValid){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Preencha todos os campos corretamente.',
          });
          return false;
        }

        if(!validateData()){
          return false;
        }

        Swal.fire({
          title: 'Aguarde...',
          text: 'Estamos processando sua solicitação.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        new Ajax.Request(
          url,
          {
            method: 'post',
            parameters: 'json=' + formData,
            onComplete: (oAjax) => {
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.fire({
                  icon: 'success',
                  title: 'Sucesso!',
                  text: 'Adesão atualizada com sucesso!',
                });

                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('si06_sequencial', oRetorno.data.adesao.si06_sequencial);
                window.location.href = currentUrl.toString();
                return false;
              }

              if(oRetorno.status == 400){
                Swal.fire({
                  icon: 'warning',
                  title: 'Atenção!',
                  html: oRetorno.message,
                });
                return false;
              }

              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: oRetorno.message,
              });
            }
          }
        )
      });
    }

    if(btnCancelar != null){
      btnCancelar.addEventListener('click', function(e){
        e.preventDefault();
        parent.close(false);
        return false;
      });
    }

    function init(){
      getCriterioAdjudicacao();
    }

    function loadEdit(){
      let oParam = {};
      oParam.exec = 'getAdesaoByCodigo';
      oParam.si06_sequencial = si06_sequencial;
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: async function(oAjax){
            let oRetorno = JSON.parse(oAjax.responseText);
            let adesao = oRetorno.data.adesao;

            document.getElementById('si06_sequencial').value = adesao.si06_sequencial;
            document.getElementById('si06_departamento').value = adesao.si06_departamento;
            document.getElementById('descricaodepartamento').value = adesao.descrdepto;
            document.getElementById('si06_orgaogerenciador').value = adesao.si06_orgaogerenciador;
            document.getElementById('z01_nomeorg').value = adesao.dl_orgao_gerenciador;
            document.getElementById('si06_cgm').value = adesao.si06_cgm;
            document.getElementById('z01_nomeresp').value = adesao.dl_resp_aprovacao;
            document.getElementById('si06_leidalicitacao').value = adesao.si06_leidalicitacao;
            document.getElementById('si06_edital').value = adesao.si06_edital;
            document.getElementById('si06_numeroprc').value = adesao.si06_numeroprc;
            document.getElementById('si06_regimecontratacao').value = adesao.si06_regimecontratacao;
            document.getElementById('si06_modalidade').value = adesao.si06_modalidade;
            document.getElementById('si06_numlicitacao').value = adesao.si06_numlicitacao;
            document.getElementById('si06_orgarparticipante').value = adesao.si06_orgarparticipante;
            document.getElementById('si06_anoproc').value = adesao.si06_anoproc;
            document.getElementById('si06_dataata').value = adesao.si06_dataata;
            document.getElementById('si06_datavalidade').value = adesao.si06_datavalidade;
            document.getElementById('si06_numeroadm').value = adesao.si06_numeroadm;
            document.getElementById('si06_anomodadm').value = adesao.si06_anomodadm;
            document.getElementById('si06_nummodadm').value = adesao.si06_nummodadm;
            document.getElementById('si06_dataabertura').value = adesao.si06_dataabertura;
            document.getElementById('si06_dataadesao').value = adesao.si06_dataadesao;
            document.getElementById('si06_publicacaoaviso').value = adesao.si06_publicacaoaviso;

            getCriterioAdjudicacao(adesao.si06_criterioadjudicacao);

            validateToggleRegimeContratacao(adesao.si06_leidalicitacao.toString(), false);
            validateToggleEdital(adesao.si06_leidalicitacao.toString(), (adesao.si06_regimecontratacao ? adesao.si06_regimecontratacao.toString() : null), false);
            validateToggleModalidade(adesao.si06_leidalicitacao.toString(), (adesao.si06_regimecontratacao ? adesao.si06_regimecontratacao.toString() : null), false);
            validateToggleNumeroModalidade(adesao.si06_leidalicitacao.toString(), (adesao.si06_regimecontratacao ? adesao.si06_regimecontratacao.toString() : null), false);
            validateToggleDescricaoCriterio((adesao.si06_criterioadjudicacao ? adesao.si06_criterioadjudicacao.toString() : null), false);

            document.getElementById('si06_descrcriterioutilizado').value = adesao.si06_descrcriterioutilizado;
            document.getElementById('si06_processoporlote').value = adesao.si06_processoporlote;
            document.getElementById('si06_processocompra').value = adesao.si06_processocompra;
            document.getElementById('si06_objetoadesao').value = adesao.si06_objetoadesao;
            
            document.getElementById('si06_objetoadesao').dispatchEvent(eventInput)

            validateToggleDocumentos(adesao.si06_anomodadm, adesao.si06_dataadesao);

            const anoAtual = 2025;
            if(adesao.si06_anocadastro < 2025){
              document.getElementById('containOrgaoAta').style.display = 'block';
            }
          }
        }
      )
    }

    if(si06_sequencial != null && si06_sequencial != ''){
      loadEdit();
    } else {
      init();
    }

    window.pesquisaDepartamento = pesquisaDepartamento;
    window.carregaDepartamento = carregaDepartamento;

    window.pesquisaOrgaoGerenciador = pesquisaOrgaoGerenciador;
    window.carregaCgmOrgaoAncora = carregaCgmOrgaoAncora;
    window.carregaCgmOrgao = carregaCgmOrgao;

    window.pesquisaCgm = pesquisaCgm;
    window.carregaCgmAncora = carregaCgmAncora;
    window.carregaCgm = carregaCgm;

    window.processoCompra = processoCompra;
    window.mostraPcProcAncora = mostraPcProcAncora;
    window.mostraPcProc = mostraPcProc;

    window.validacaoAno = validacaoAno;

  })();
</script>