<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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


require_once ("libs/db_stdlibwebseller.php");
require_once ("fpdf151/FpdfMultiCellBorder.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libdocumento.php");
require_once ("libs/db_libparagrafo.php");
require_once ('model/educacao/ArredondamentoNota.model.php');

$oGet                   = db_utils::postMemory($_GET);
$aAlunosSelecionados    = explode(",", $oGet->sAlunos);
$lExibirReclassificacao = $oGet->sExibirReclassificacao == 't' ? true : false;

$oFpdf = new FpdfMultiCellBorder("P");
$oFpdf->Open();
$oFpdf->AliasNbPages();
$oFpdf->SetMargins(8, 10);

foreach ( $aAlunosSelecionados as $iCodigoAluno ) {
   
  $oRelatorioEscolar = new RelatorioCertificadoEscolarRetrato( 
                                                               $oFpdf,
                                                               AlunoRepository::getAlunoByCodigo($iCodigoAluno),
                                                               EscolaRepository::getEscolaByCodigo($oGet->iEscola),
                                                               $oGet->iTipoRelatorio,
                                                               $lExibirReclassificacao
                                                             );

  $oRelatorioEscolar->setTipoRegistro($oGet->iTipoRegistro);
  $oRelatorioEscolar->montarEstruturaDeDados();
  $oRelatorioEscolar->setDisposicao($oGet->sDisposicao);
  $oRelatorioEscolar->escreveCabecalho();
  $oRelatorioEscolar->criarTabelaComponentesCurriculares();
  $oRelatorioEscolar->criarTabelaResumoEtapas();
  $oRelatorioEscolar->montaQuadroObservacao();
  $oRelatorioEscolar->montarQuadroCertificado();
  $oRelatorioEscolar->escreverRodape($oGet->sDiretor,$oGet->sSecretario);
}
$oFpdf->Output();
?>