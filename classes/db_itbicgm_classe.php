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

//MODULO: itbi
//CLASSE DA ENTIDADE itbicgm
class cl_itbicgm { 
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
   var $it02_guia = 0; 
   var $it02_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it02_guia = int8 = N�mero da guia de ITBI 
                 it02_numcgm = int4 = Numcgm 
                 ";
   //funcao construtor da classe 
   function cl_itbicgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbicgm"); 
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
       $this->it02_guia = ($this->it02_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it02_guia"]:$this->it02_guia);
       $this->it02_numcgm = ($this->it02_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["it02_numcgm"]:$this->it02_numcgm);
     }else{
       $this->it02_guia = ($this->it02_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it02_guia"]:$this->it02_guia);
       $this->it02_numcgm = ($this->it02_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["it02_numcgm"]:$this->it02_numcgm);
     }
   }
   // funcao para inclusao
   function incluir ($it02_guia,$it02_numcgm){ 
      $this->atualizacampos();
       $this->it02_guia = $it02_guia; 
       $this->it02_numcgm = $it02_numcgm; 
     if(($this->it02_guia == null) || ($this->it02_guia == "") ){ 
       $this->erro_sql = " Campo it02_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->it02_numcgm == null) || ($this->it02_numcgm == "") ){ 
       $this->erro_sql = " Campo it02_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbicgm(
                                       it02_guia 
                                      ,it02_numcgm 
                       )
                values (
                                $this->it02_guia 
                               ,$this->it02_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "CGM da guia ($this->it02_guia."-".$this->it02_numcgm) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "CGM da guia j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "CGM da guia ($this->it02_guia."-".$this->it02_numcgm) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it02_guia."-".$this->it02_numcgm;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it02_guia,$this->it02_numcgm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5399,'$this->it02_guia','I')");
       $resac = db_query("insert into db_acountkey values($acount,5377,'$this->it02_numcgm','I')");
       $resac = db_query("insert into db_acount values($acount,793,5399,'','".AddSlashes(pg_result($resaco,0,'it02_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,793,5377,'','".AddSlashes(pg_result($resaco,0,'it02_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it02_guia=null,$it02_numcgm=null) { 
      $this->atualizacampos();
     $sql = " update itbicgm set ";
     $virgula = "";
     if(trim($this->it02_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it02_guia"])){ 
       $sql  .= $virgula." it02_guia = $this->it02_guia ";
       $virgula = ",";
       if(trim($this->it02_guia) == null ){ 
         $this->erro_sql = " Campo N�mero da guia de ITBI nao Informado.";
         $this->erro_campo = "it02_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it02_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it02_numcgm"])){ 
       $sql  .= $virgula." it02_numcgm = $this->it02_numcgm ";
       $virgula = ",";
       if(trim($this->it02_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "it02_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it02_guia!=null){
       $sql .= " it02_guia = $this->it02_guia";
     }
     if($it02_numcgm!=null){
       $sql .= " and  it02_numcgm = $this->it02_numcgm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it02_guia,$this->it02_numcgm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5399,'$this->it02_guia','A')");
         $resac = db_query("insert into db_acountkey values($acount,5377,'$this->it02_numcgm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it02_guia"]))
           $resac = db_query("insert into db_acount values($acount,793,5399,'".AddSlashes(pg_result($resaco,$conresaco,'it02_guia'))."','$this->it02_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it02_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,793,5377,'".AddSlashes(pg_result($resaco,$conresaco,'it02_numcgm'))."','$this->it02_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM da guia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it02_guia."-".$this->it02_numcgm;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM da guia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it02_guia."-".$this->it02_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it02_guia."-".$this->it02_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it02_guia=null,$it02_numcgm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it02_guia,$it02_numcgm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5399,'$it02_guia','E')");
         $resac = db_query("insert into db_acountkey values($acount,5377,'$it02_numcgm','E')");
         $resac = db_query("insert into db_acount values($acount,793,5399,'','".AddSlashes(pg_result($resaco,$iresaco,'it02_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,793,5377,'','".AddSlashes(pg_result($resaco,$iresaco,'it02_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbicgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it02_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it02_guia = $it02_guia ";
        }
        if($it02_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it02_numcgm = $it02_numcgm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM da guia nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it02_guia."-".$it02_numcgm;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM da guia nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it02_guia."-".$it02_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it02_guia."-".$it02_numcgm;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbicgm";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $it02_guia=null,$it02_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbicgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = itbicgm.it02_numcgm";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbicgm.it02_guia";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it02_guia!=null ){
         $sql2 .= " where itbicgm.it02_guia = $it02_guia "; 
       } 
       if($it02_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " itbicgm.it02_numcgm = $it02_numcgm "; 
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
   function sql_query_file ( $it02_guia=null,$it02_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbicgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($it02_guia!=null ){
         $sql2 .= " where itbicgm.it02_guia = $it02_guia "; 
       } 
       if($it02_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " itbicgm.it02_numcgm = $it02_numcgm "; 
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