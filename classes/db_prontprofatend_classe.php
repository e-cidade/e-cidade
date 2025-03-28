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
//MODULO: ambulatorial
//CLASSE DA ENTIDADE prontprofatend
class cl_prontprofatend { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $s104_i_codigo = 0; 
   var $s104_i_prontuario = 0; 
   var $s104_i_profissional = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s104_i_codigo = int4 = C�digo 
                 s104_i_prontuario = int4 = Prontu�rio 
                 s104_i_profissional = int4 = Profissional 
                 ";
   //funcao construtor da classe 
   function cl_prontprofatend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontprofatend"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->s104_i_codigo = ($this->s104_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s104_i_codigo"]:$this->s104_i_codigo);
       $this->s104_i_prontuario = ($this->s104_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["s104_i_prontuario"]:$this->s104_i_prontuario);
       $this->s104_i_profissional = ($this->s104_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["s104_i_profissional"]:$this->s104_i_profissional);
     }else{
       $this->s104_i_codigo = ($this->s104_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s104_i_codigo"]:$this->s104_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s104_i_codigo){ 
      $this->atualizacampos();
     if($this->s104_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontu�rio n�o informado.";
       $this->erro_campo = "s104_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s104_i_profissional == null ){ 
       $this->erro_sql = " Campo Profissional n�o informado.";
       $this->erro_campo = "s104_i_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s104_i_codigo == "" || $s104_i_codigo == null ){
       $result = db_query("select nextval('prontprofatend_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontprofatend_codigo_seq do campo: s104_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s104_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontprofatend_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s104_i_codigo)){
         $this->erro_sql = " Campo s104_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s104_i_codigo = $s104_i_codigo; 
       }
     }
     if(($this->s104_i_codigo == null) || ($this->s104_i_codigo == "") ){ 
       $this->erro_sql = " Campo s104_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontprofatend(
                                       s104_i_codigo 
                                      ,s104_i_prontuario 
                                      ,s104_i_profissional 
                       )
                values (
                                $this->s104_i_codigo 
                               ,$this->s104_i_prontuario 
                               ,$this->s104_i_profissional 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuario Profissional Atendimento ($this->s104_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuario Profissional Atendimento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuario Profissional Atendimento ($this->s104_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s104_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s104_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13516,'$this->s104_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2367,13516,'','".AddSlashes(pg_result($resaco,0,'s104_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2367,13517,'','".AddSlashes(pg_result($resaco,0,'s104_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2367,13518,'','".AddSlashes(pg_result($resaco,0,'s104_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s104_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontprofatend set ";
     $virgula = "";
     if(trim($this->s104_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s104_i_codigo"])){ 
       $sql  .= $virgula." s104_i_codigo = $this->s104_i_codigo ";
       $virgula = ",";
       if(trim($this->s104_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "s104_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s104_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s104_i_prontuario"])){ 
       $sql  .= $virgula." s104_i_prontuario = $this->s104_i_prontuario ";
       $virgula = ",";
       if(trim($this->s104_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontu�rio n�o informado.";
         $this->erro_campo = "s104_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s104_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s104_i_profissional"])){ 
       $sql  .= $virgula." s104_i_profissional = $this->s104_i_profissional ";
       $virgula = ",";
       if(trim($this->s104_i_profissional) == null ){ 
         $this->erro_sql = " Campo Profissional n�o informado.";
         $this->erro_campo = "s104_i_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s104_i_codigo!=null){
       $sql .= " s104_i_codigo = $this->s104_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s104_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13516,'$this->s104_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s104_i_codigo"]) || $this->s104_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2367,13516,'".AddSlashes(pg_result($resaco,$conresaco,'s104_i_codigo'))."','$this->s104_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s104_i_prontuario"]) || $this->s104_i_prontuario != "")
             $resac = db_query("insert into db_acount values($acount,2367,13517,'".AddSlashes(pg_result($resaco,$conresaco,'s104_i_prontuario'))."','$this->s104_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s104_i_profissional"]) || $this->s104_i_profissional != "")
             $resac = db_query("insert into db_acount values($acount,2367,13518,'".AddSlashes(pg_result($resaco,$conresaco,'s104_i_profissional'))."','$this->s104_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuario Profissional Atendimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s104_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuario Profissional Atendimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s104_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s104_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s104_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($s104_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13516,'$s104_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2367,13516,'','".AddSlashes(pg_result($resaco,$iresaco,'s104_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2367,13517,'','".AddSlashes(pg_result($resaco,$iresaco,'s104_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2367,13518,'','".AddSlashes(pg_result($resaco,$iresaco,'s104_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from prontprofatend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s104_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s104_i_codigo = $s104_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuario Profissional Atendimento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s104_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuario Profissional Atendimento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s104_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s104_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:prontprofatend";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s104_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from prontprofatend ";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = prontprofatend.s104_i_profissional";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontprofatend.s104_i_prontuario";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s104_i_codigo!=null ){
         $sql2 .= " where prontprofatend.s104_i_codigo = $s104_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $s104_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from prontprofatend ";
     $sql2 = "";
     if($dbwhere==""){
       if($s104_i_codigo!=null ){
         $sql2 .= " where prontprofatend.s104_i_codigo = $s104_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  function sql_query_vinculo_profissional ( $s104_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split( "#", $campos );
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from prontprofatend ";
    $sql .= "      inner join especmedico    on especmedico.sd27_i_codigo    = prontprofatend.s104_i_profissional";
    $sql .= "      inner join prontuarios    on prontuarios.sd24_i_codigo    = prontprofatend.s104_i_prontuario";
    $sql .= "      inner join rhcbo          on rhcbo.rh70_sequencial        = especmedico.sd27_i_rhcbo";
    $sql .= "      inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
    $sql .= "      inner join db_usuarios    on db_usuarios.id_usuario       = prontuarios.sd24_i_login";
    $sql .= "      left  join sau_siasih     on sau_siasih.sd92_i_codigo     = prontuarios.sd24_i_siasih";
    $sql .= "      inner join unidades       on unidades.sd02_i_codigo       = prontuarios.sd24_i_unidade";
    $sql .= "      left  join cgs            on cgs.z01_i_numcgs             = prontuarios.sd24_i_numcgs";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $s104_i_codigo != null ) {
        $sql2 .= " where prontprofatend.s104_i_codigo = {$s104_i_codigo} ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split( "#", $ordem );
      $virgula     = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }

    return $sql;
  }
}
?>
