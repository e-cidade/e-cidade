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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meiprocessa
class cl_meiprocessa { 
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
   var $q113_sequencial = 0; 
   var $q113_data_dia = null; 
   var $q113_data_mes = null; 
   var $q113_data_ano = null; 
   var $q113_data = null; 
   var $q113_hora = null; 
   var $q113_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q113_sequencial = int4 = Sequencial 
                 q113_data = date = Data Processamento 
                 q113_hora = char(5) = Hora Processamento 
                 q113_id_usuario = int4 = Usu�rio 
                 ";
   //funcao construtor da classe 
   function cl_meiprocessa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiprocessa"); 
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
       $this->q113_sequencial = ($this->q113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]:$this->q113_sequencial);
       if($this->q113_data == ""){
         $this->q113_data_dia = ($this->q113_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_dia"]:$this->q113_data_dia);
         $this->q113_data_mes = ($this->q113_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_mes"]:$this->q113_data_mes);
         $this->q113_data_ano = ($this->q113_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_ano"]:$this->q113_data_ano);
         if($this->q113_data_dia != ""){
            $this->q113_data = $this->q113_data_ano."-".$this->q113_data_mes."-".$this->q113_data_dia;
         }
       }
       $this->q113_hora = ($this->q113_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_hora"]:$this->q113_hora);
       $this->q113_id_usuario = ($this->q113_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_id_usuario"]:$this->q113_id_usuario);
     }else{
       $this->q113_sequencial = ($this->q113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]:$this->q113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q113_sequencial){ 
      $this->atualizacampos();
     if($this->q113_data == null ){ 
       $this->erro_sql = " Campo Data Processamento nao Informado.";
       $this->erro_campo = "q113_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q113_hora == null ){ 
       $this->erro_sql = " Campo Hora Processamento nao Informado.";
       $this->erro_campo = "q113_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q113_id_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "q113_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q113_sequencial == "" || $q113_sequencial == null ){
       $result = db_query("select nextval('meiprocessa_q113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiprocessa_q113_sequencial_seq do campo: q113_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiprocessa_q113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q113_sequencial)){
         $this->erro_sql = " Campo q113_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q113_sequencial = $q113_sequencial; 
       }
     }
     if(($this->q113_sequencial == null) || ($this->q113_sequencial == "") ){ 
       $this->erro_sql = " Campo q113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiprocessa(
                                       q113_sequencial 
                                      ,q113_data 
                                      ,q113_hora 
                                      ,q113_id_usuario 
                       )
                values (
                                $this->q113_sequencial 
                               ,".($this->q113_data == "null" || $this->q113_data == ""?"null":"'".$this->q113_data."'")." 
                               ,'$this->q113_hora' 
                               ,$this->q113_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processamento do MEI ($this->q113_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processamento do MEI j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processamento do MEI ($this->q113_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16640,'$this->q113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2925,16640,'','".AddSlashes(pg_result($resaco,0,'q113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2925,16641,'','".AddSlashes(pg_result($resaco,0,'q113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2925,16642,'','".AddSlashes(pg_result($resaco,0,'q113_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2925,16643,'','".AddSlashes(pg_result($resaco,0,'q113_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiprocessa set ";
     $virgula = "";
     if(trim($this->q113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_sequencial"])){ 
       $sql  .= $virgula." q113_sequencial = $this->q113_sequencial ";
       $virgula = ",";
       if(trim($this->q113_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q113_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"] !="") ){ 
       $sql  .= $virgula." q113_data = '$this->q113_data' ";
       $virgula = ",";
       if(trim($this->q113_data) == null ){ 
         $this->erro_sql = " Campo Data Processamento nao Informado.";
         $this->erro_campo = "q113_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"])){ 
         $sql  .= $virgula." q113_data = null ";
         $virgula = ",";
         if(trim($this->q113_data) == null ){ 
           $this->erro_sql = " Campo Data Processamento nao Informado.";
           $this->erro_campo = "q113_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q113_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_hora"])){ 
       $sql  .= $virgula." q113_hora = '$this->q113_hora' ";
       $virgula = ",";
       if(trim($this->q113_hora) == null ){ 
         $this->erro_sql = " Campo Hora Processamento nao Informado.";
         $this->erro_campo = "q113_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q113_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_id_usuario"])){ 
       $sql  .= $virgula." q113_id_usuario = $this->q113_id_usuario ";
       $virgula = ",";
       if(trim($this->q113_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "q113_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q113_sequencial!=null){
       $sql .= " q113_sequencial = $this->q113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16640,'$this->q113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]) || $this->q113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2925,16640,'".AddSlashes(pg_result($resaco,$conresaco,'q113_sequencial'))."','$this->q113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_data"]) || $this->q113_data != "")
           $resac = db_query("insert into db_acount values($acount,2925,16641,'".AddSlashes(pg_result($resaco,$conresaco,'q113_data'))."','$this->q113_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_hora"]) || $this->q113_hora != "")
           $resac = db_query("insert into db_acount values($acount,2925,16642,'".AddSlashes(pg_result($resaco,$conresaco,'q113_hora'))."','$this->q113_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_id_usuario"]) || $this->q113_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2925,16643,'".AddSlashes(pg_result($resaco,$conresaco,'q113_id_usuario'))."','$this->q113_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processamento do MEI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processamento do MEI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16640,'$q113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2925,16640,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2925,16641,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2925,16642,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2925,16643,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiprocessa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q113_sequencial = $q113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processamento do MEI nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q113_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processamento do MEI nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiprocessa";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from meiprocessa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q113_sequencial!=null ){
         $sql2 .= " where meiprocessa.q113_sequencial = $q113_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $q113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from meiprocessa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q113_sequencial!=null ){
         $sql2 .= " where meiprocessa.q113_sequencial = $q113_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>