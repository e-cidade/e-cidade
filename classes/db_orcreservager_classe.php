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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcreservager
class cl_orcreservager { 
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
   var $o84_codres = 0; 
   var $o84_data_dia = null; 
   var $o84_data_mes = null; 
   var $o84_data_ano = null; 
   var $o84_data = null; 
   var $o84_id_usuario = 0; 
   var $o84_tipo = null; 
   var $o84_perc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o84_codres = int4 = o84_codres 
                 o84_data = date = Data 
                 o84_id_usuario = int8 = o84_id_usuario 
                 o84_tipo = varchar(6) = o84_tipo 
                 o84_perc = float8 = Percentual 
                 ";
   //funcao construtor da classe 
   function cl_orcreservager() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreservager"); 
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
       $this->o84_codres = ($this->o84_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_codres"]:$this->o84_codres);
       if($this->o84_data == ""){
         $this->o84_data_dia = ($this->o84_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_data_dia"]:$this->o84_data_dia);
         $this->o84_data_mes = ($this->o84_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_data_mes"]:$this->o84_data_mes);
         $this->o84_data_ano = ($this->o84_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_data_ano"]:$this->o84_data_ano);
         if($this->o84_data_dia != ""){
            $this->o84_data = $this->o84_data_ano."-".$this->o84_data_mes."-".$this->o84_data_dia;
         }
       }
       $this->o84_id_usuario = ($this->o84_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_id_usuario"]:$this->o84_id_usuario);
       $this->o84_tipo = ($this->o84_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_tipo"]:$this->o84_tipo);
       $this->o84_perc = ($this->o84_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_perc"]:$this->o84_perc);
     }else{
       $this->o84_codres = ($this->o84_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o84_codres"]:$this->o84_codres);
     }
   }
   // funcao para inclusao
   function incluir ($o84_codres){ 
      $this->atualizacampos();
     if($this->o84_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "o84_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o84_id_usuario == null ){ 
       $this->erro_sql = " Campo o84_id_usuario nao Informado.";
       $this->erro_campo = "o84_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o84_tipo == null ){ 
       $this->erro_sql = " Campo o84_tipo nao Informado.";
       $this->erro_campo = "o84_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o84_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "o84_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o84_codres = $o84_codres; 
     if(($this->o84_codres == null) || ($this->o84_codres == "") ){ 
       $this->erro_sql = " Campo o84_codres nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreservager(
                                       o84_codres 
                                      ,o84_data 
                                      ,o84_id_usuario 
                                      ,o84_tipo 
                                      ,o84_perc 
                       )
                values (
                                $this->o84_codres 
                               ,".($this->o84_data == "null" || $this->o84_data == ""?"null":"'".$this->o84_data."'")." 
                               ,$this->o84_id_usuario 
                               ,'$this->o84_tipo' 
                               ,$this->o84_perc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "o84 ($this->o84_codres) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "o84 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "o84 ($this->o84_codres) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o84_codres;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o84_codres));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5372,'$this->o84_codres','I')");
       $resac = db_query("insert into db_acount values($acount,785,5372,'','".AddSlashes(pg_result($resaco,0,'o84_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,785,5373,'','".AddSlashes(pg_result($resaco,0,'o84_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,785,5375,'','".AddSlashes(pg_result($resaco,0,'o84_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,785,5374,'','".AddSlashes(pg_result($resaco,0,'o84_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,785,8064,'','".AddSlashes(pg_result($resaco,0,'o84_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o84_codres=null) { 
      $this->atualizacampos();
     $sql = " update orcreservager set ";
     $virgula = "";
     if(trim($this->o84_codres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o84_codres"])){ 
       $sql  .= $virgula." o84_codres = $this->o84_codres ";
       $virgula = ",";
       if(trim($this->o84_codres) == null ){ 
         $this->erro_sql = " Campo o84_codres nao Informado.";
         $this->erro_campo = "o84_codres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o84_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o84_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o84_data_dia"] !="") ){ 
       $sql  .= $virgula." o84_data = '$this->o84_data' ";
       $virgula = ",";
       if(trim($this->o84_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "o84_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o84_data_dia"])){ 
         $sql  .= $virgula." o84_data = null ";
         $virgula = ",";
         if(trim($this->o84_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "o84_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o84_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o84_id_usuario"])){ 
       $sql  .= $virgula." o84_id_usuario = $this->o84_id_usuario ";
       $virgula = ",";
       if(trim($this->o84_id_usuario) == null ){ 
         $this->erro_sql = " Campo o84_id_usuario nao Informado.";
         $this->erro_campo = "o84_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o84_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o84_tipo"])){ 
       $sql  .= $virgula." o84_tipo = '$this->o84_tipo' ";
       $virgula = ",";
       if(trim($this->o84_tipo) == null ){ 
         $this->erro_sql = " Campo o84_tipo nao Informado.";
         $this->erro_campo = "o84_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o84_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o84_perc"])){ 
       $sql  .= $virgula." o84_perc = $this->o84_perc ";
       $virgula = ",";
       if(trim($this->o84_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "o84_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o84_codres!=null){
       $sql .= " o84_codres = $this->o84_codres";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o84_codres));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5372,'$this->o84_codres','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o84_codres"]))
           $resac = db_query("insert into db_acount values($acount,785,5372,'".AddSlashes(pg_result($resaco,$conresaco,'o84_codres'))."','$this->o84_codres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o84_data"]))
           $resac = db_query("insert into db_acount values($acount,785,5373,'".AddSlashes(pg_result($resaco,$conresaco,'o84_data'))."','$this->o84_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o84_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,785,5375,'".AddSlashes(pg_result($resaco,$conresaco,'o84_id_usuario'))."','$this->o84_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o84_tipo"]))
           $resac = db_query("insert into db_acount values($acount,785,5374,'".AddSlashes(pg_result($resaco,$conresaco,'o84_tipo'))."','$this->o84_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o84_perc"]))
           $resac = db_query("insert into db_acount values($acount,785,8064,'".AddSlashes(pg_result($resaco,$conresaco,'o84_perc'))."','$this->o84_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "o84 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o84_codres;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "o84 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o84_codres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o84_codres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o84_codres=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o84_codres));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5372,'$o84_codres','E')");
         $resac = db_query("insert into db_acount values($acount,785,5372,'','".AddSlashes(pg_result($resaco,$iresaco,'o84_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,785,5373,'','".AddSlashes(pg_result($resaco,$iresaco,'o84_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,785,5375,'','".AddSlashes(pg_result($resaco,$iresaco,'o84_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,785,5374,'','".AddSlashes(pg_result($resaco,$iresaco,'o84_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,785,8064,'','".AddSlashes(pg_result($resaco,$iresaco,'o84_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreservager
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o84_codres != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o84_codres = $o84_codres ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "o84 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o84_codres;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "o84 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o84_codres;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o84_codres;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreservager";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o84_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservager ";
     $sql .= "      inner join orcreserva  on  orcreserva.o80_codres = orcreservager.o84_codres";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = orcreserva.o80_anousu and  orcdotacao.o58_coddot = orcreserva.o80_coddot";
     $sql2 = "";
     if($dbwhere==""){
       if($o84_codres!=null ){
         $sql2 .= " where orcreservager.o84_codres = $o84_codres "; 
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
   function sql_query_file ( $o84_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservager ";
     $sql2 = "";
     if($dbwhere==""){
       if($o84_codres!=null ){
         $sql2 .= " where orcreservager.o84_codres = $o84_codres "; 
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