/**
 * Redireciona o usu�rio para uma p�gina espec�fica com base em par�metros fornecidos.
 * 
 * Esta fun��o valida a �rea fornecida e, se for v�lida, faz uma requisi��o AJAX para obter um item de menu.
 * Ap�s receber a resposta, a fun��o cria uma nova janela no desktop com os par�metros fornecidos, 
 * ou exibe uma mensagem de erro se a opera��o falhar.
 * 
 * @param {string} title - O t�tulo da nova janela do desktop.
 * @param {string} action - A url correspondente ao redirecionamento.
 * @param {number} areaId - O ID da �rea para a qual o redirecionamento deve ser realizado. Deve ser um valor presente na lista de �reas v�lidas.
 *                        - a areaId deve ser definida conforme o valor at26_sequencial da tabela atendcadarea correspondente a at25_descr(exemplos: DB:PATRIMONIAL,DB:TRIBUT�RIO) desejada.
 * @param {number} moduloId - O ID do m�dulo do menu a ser redirecionado. O modulo Id deve ser definido conforme o valor do campo 'id_item' da tabela db_modulos correspondente ao campo nome_modulo(exemplos: Licita��es,Contratos) desejado.
 * @param {string} redirectionFileName - O nome do arquivo para o qual o redirecionamento deve ocorrer.
 * 
 * @throws {Error} Se o `areaId` n�o estiver na lista de �reas v�lidas, um alerta de erro � exibido.
 * @throws {Error} Se a resposta da requisi��o AJAX indicar falha, um alerta de erro � exibido com a mensagem recebida.
 * 
 * @example
 * js_redirectPage(
 *   'DB:PATRIMONIAL > Licita��es > Cadastros > Fornecedor > Inclus�o',
 *   'com1_pcforne001.php?pc60_numcgm=1753',
 *   4,
 *   381,
 *   'com1_pcforne001.php'
 * );
 */
function js_redirectPage(title,action,areaId,moduloId,redirectionFileName){

    let aIdsAreaValidos = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,2000000];

    if(!aIdsAreaValidos.includes(areaId)){
      return alert('Erro: �rea informada para realizar o redirecionamento n�o foi encontrada');
    }

    let oParam = {};
  
    oParam.namefile = redirectionFileName;
    oParam.exec = 'getIdItemMenu';
  
    let sUrlRpc = 'redirectPage.RPC.php';
  
      let oAjax = new Ajax.Request(sUrlRpc, {
              method: 'post',
              parameters: 'json=' + Object.toJSON(oParam),
              onComplete: function(oAjax) {
  
                  var oRetorno = eval('(' + oAjax.responseText + ')');
                  let idItemMenu = oRetorno.idItemMenu;
  
                  if (oRetorno.status == 1) {
  
                    let oRedirectParams = {
                      action: action,
                      iInstitId: top.jQuery('#instituicoes span.active').data('id'),
                      iAreaId: areaId,
                      iModuloId: moduloId,
                      redirect: true,
                      redirectionFileName: redirectionFileName,
                      DB_itemmenu_acessado: idItemMenu
                    }
  
                    Desktop.Window.create(title, oRedirectParams);
                    return;
  
                  }

                  return alert(oRetorno.erro);
  
              }
      });
}
