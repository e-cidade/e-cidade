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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_cardapionutri
class cl_mer_cardapionutri { 
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
   var $me04_i_codigo = 0; 
   var $me04_i_cardapio = 0; 
   var $me04_i_nutricionista = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me04_i_codigo = int4 = C�digo 
                 me04_i_cardapio = int4 = Card�pio 
                 me04_i_nutricionista = int4 = Nutricionista 
                 ";
   //funcao construtor da classe 
   function cl_mer_cardapionutri() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_cardapionutri"); 
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
       $this->me04_i_codigo = ($this->me04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me04_i_codigo"]:$this->me04_i_codigo);
       $this->me04_i_cardapio = ($this->me04_i_cardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me04_i_cardapio"]:$this->me04_i_cardapio);
       $this->me04_i_nutricionista = ($this->me04_i_nutricionista == ""?@$GLOBALS["HTTP_POST_VARS"]["me04_i_nutricionista"]:$this->me04_i_nutricionista);
     }else{
       $this->me04_i_codigo = ($this->me04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me04_i_codigo"]:$this->me04_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me04_i_codigo){ 
      $this->atualizacampos();
     if($this->me04_i_cardapio == null ){ 
       $this->erro_sql = " Campo Card�pio nao Informado.";
       $this->erro_campo = "me04_i_cardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me04_i_nutricionista == null ){ 
       $this->erro_sql = " Campo Nutricionista nao Informado.";
       $this->erro_campo = "me04_i_nutricionista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me04_i_codigo == "" || $me04_i_codigo == null ){
       $result = db_query("select nextval('mercardapionutri_me04_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mercardapionutri_me04_codigo_seq do campo: me04_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me04_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mercardapionutri_me04_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me04_i_codigo)){
         $this->erro_sql = " Campo me04_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me04_i_codigo = $me04_i_codigo; 
       }
     }
     if(($this->me04_i_codigo == null) || ($this->me04_i_codigo == "") ){ 
       $this->erro_sql = " Campo me04_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_cardapionutri(
                                       me04_i_codigo 
                                      ,me04_i_cardapio 
                                      ,me04_i_nutricionista 
                       )
                values (
                                $this->me04_i_codigo 
                               ,$this->me04_i_cardapio 
                               ,$this->me04_i_nutricionista 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_cardapionutri ($this->me04_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_cardapionutri j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_cardapionutri ($this->me04_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me04_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me04_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12767,'$this->me04_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2236,12767,'','".AddSlashes(pg_result($resaco,0,'me04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2236,12769,'','".AddSlashes(pg_result($resaco,0,'me04_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2236,12768,'','".AddSlashes(pg_result($resaco,0,'me04_i_nutricionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me04_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_cardapionutri set ";
     $virgula = "";
     if(trim($this->me04_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me04_i_codigo"])){ 
       $sql  .= $virgula." me04_i_codigo = $this->me04_i_codigo ";
       $virgula = ",";
       if(trim($this->me04_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me04_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me04_i_cardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me04_i_cardapio"])){ 
       $sql  .= $virgula." me04_i_cardapio = $this->me04_i_cardapio ";
       $virgula = ",";
       if(trim($this->me04_i_cardapio) == null ){ 
         $this->erro_sql = " Campo Card�pio nao Informado.";
         $this->erro_campo = "me04_i_cardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me04_i_nutricionista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me04_i_nutricionista"])){ 
       $sql  .= $virgula." me04_i_nutricionista = $this->me04_i_nutricionista ";
       $virgula = ",";
       if(trim($this->me04_i_nutricionista) == null ){ 
         $this->erro_sql = " Campo Nutricionista nao Informado.";
         $this->erro_campo = "me04_i_nutricionista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me04_i_codigo!=null){
       $sql .= " me04_i_codigo = $this->me04_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me04_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12767,'$this->me04_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me04_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2236,12767,'".AddSlashes(pg_result($resaco,$conresaco,'me04_i_codigo'))."','$this->me04_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me04_i_cardapio"]))
           $resac = db_query("insert into db_acount values($acount,2236,12769,'".AddSlashes(pg_result($resaco,$conresaco,'me04_i_cardapio'))."','$this->me04_i_cardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me04_i_nutricionista"]))
           $resac = db_query("insert into db_acount values($acount,2236,12768,'".AddSlashes(pg_result($resaco,$conresaco,'me04_i_nutricionista'))."','$this->me04_i_nutricionista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapionutri nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me04_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapionutri nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me04_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me04_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me04_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me04_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12767,'$me04_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2236,12767,'','".AddSlashes(pg_result($resaco,$iresaco,'me04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2236,12769,'','".AddSlashes(pg_result($resaco,$iresaco,'me04_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2236,12768,'','".AddSlashes(pg_result($resaco,$iresaco,'me04_i_nutricionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_cardapionutri
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me04_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me04_i_codigo = $me04_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapionutri nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me04_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapionutri nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me04_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me04_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_cardapionutri";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapionutri ";
     $sql .= "      inner join mer_nutricionista  on  mer_nutricionista.me02_i_codigo = mer_cardapionutri.me04_i_nutricionista";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapionutri.me04_i_cardapio";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";	
     $sql2 = "";
     if($dbwhere==""){
       if($me04_i_codigo!=null ){
         $sql2 .= " where mer_cardapionutri.me04_i_codigo = $me04_i_codigo "; 
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
   function sql_query_file ( $me04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapionutri ";
     $sql2 = "";
     if($dbwhere==""){
       if($me04_i_codigo!=null ){
         $sql2 .= " where mer_cardapionutri.me04_i_codigo = $me04_i_codigo "; 
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