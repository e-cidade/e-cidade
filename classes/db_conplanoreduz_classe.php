<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoreduz
class cl_conplanoreduz {
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
   var $c61_codcon = 0;
   var $c61_anousu = 0;
   var $c61_reduz = 0;
   var $c61_instit = 0;
   var $c61_codigo = 0;
   var $c61_contrapartida = 0;
   var $c61_codtce = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c61_codcon = int4 = C�digo da Conta
                 c61_anousu = int4 = Exerc�cio
                 c61_reduz = int4 = Reduzido
                 c61_instit = int4 = Institui��o
                 c61_codigo = int4 = Codigo do Recurso
                 c61_contrapartida = int4 = Contra Partida
                 c61_codtce = int8 = Codigo TCE
                 ";
   //funcao construtor da classe
   function cl_conplanoreduz() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoreduz");
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
       $this->c61_codcon = ($this->c61_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_codcon"]:$this->c61_codcon);
       $this->c61_anousu = ($this->c61_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_anousu"]:$this->c61_anousu);
       $this->c61_reduz = ($this->c61_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_reduz"]:$this->c61_reduz);
       $this->c61_instit = ($this->c61_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_instit"]:$this->c61_instit);
       $this->c61_codigo = ($this->c61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_codigo"]:$this->c61_codigo);
       $this->c61_contrapartida = ($this->c61_contrapartida == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"]:$this->c61_contrapartida);
       $this->c61_codtce = ($this->c61_codtce == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_codtce"]:$this->c61_codtce);
     }else{
       $this->c61_anousu = ($this->c61_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_anousu"]:$this->c61_anousu);
       $this->c61_reduz = ($this->c61_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c61_reduz"]:$this->c61_reduz);
     }
   }
   // funcao para inclusao
   function incluir ($c61_reduz,$c61_anousu){
      $this->atualizacampos();
     if($this->c61_codcon == null ){
       $this->erro_sql = " Campo C�digo da Conta nao Informado.";
       $this->erro_campo = "c61_codcon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c61_instit == null ){
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "c61_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c61_codigo == null ){
       $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
       $this->erro_campo = "c61_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c61_contrapartida == null ){
       $this->c61_contrapartida = "0";
     }
     if($c61_reduz == "" || $c61_reduz == null ){
       $result = db_query("select nextval('conplanoreduz_c61_reduz_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanoreduz_c61_reduz_seq do campo: c61_reduz";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c61_reduz = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conplanoreduz_c61_reduz_seq");
       if(($result != false) && (pg_result($result,0,0) < $c61_reduz)){
         $this->erro_sql = " Campo c61_reduz maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c61_reduz = $c61_reduz;
       }
     }
     if(($this->c61_reduz == null) || ($this->c61_reduz == "") ){
       $this->erro_sql = " Campo c61_reduz nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c61_anousu == null) || ($this->c61_anousu == "") ){
       $this->erro_sql = " Campo c61_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoreduz(
                                       c61_codcon
                                      ,c61_anousu
                                      ,c61_reduz
                                      ,c61_instit
                                      ,c61_codigo
                                      ,c61_contrapartida
                                      ,c61_codtce
                       )
                values (
                                $this->c61_codcon
                               ,$this->c61_anousu
                               ,$this->c61_reduz
                               ,$this->c61_instit
                               ,$this->c61_codigo
                               ,$this->c61_contrapartida
                               ,".($this->c61_codtce == null ? 'null' : $this->c61_codtce)."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "C�digo Reduzido do Plano ($this->c61_reduz."-".$this->c61_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "C�digo Reduzido do Plano j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "C�digo Reduzido do Plano ($this->c61_reduz."-".$this->c61_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c61_reduz."-".$this->c61_anousu;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c61_reduz,$this->c61_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5247,'$this->c61_reduz','I')");
       $resac = db_query("insert into db_acountkey values($acount,8060,'$this->c61_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,773,5246,'','".AddSlashes(pg_result($resaco,0,'c61_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,773,8060,'','".AddSlashes(pg_result($resaco,0,'c61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,773,5247,'','".AddSlashes(pg_result($resaco,0,'c61_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,773,5248,'','".AddSlashes(pg_result($resaco,0,'c61_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,773,5249,'','".AddSlashes(pg_result($resaco,0,'c61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,773,7372,'','".AddSlashes(pg_result($resaco,0,'c61_contrapartida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c61_reduz=null,$c61_anousu=null) {
      $this->atualizacampos();
     $sql = " update conplanoreduz set ";
     $virgula = "";
     if(trim($this->c61_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_codcon"])){
       $sql  .= $virgula." c61_codcon = $this->c61_codcon ";
       $virgula = ",";
       if(trim($this->c61_codcon) == null ){
         $this->erro_sql = " Campo C�digo da Conta nao Informado.";
         $this->erro_campo = "c61_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c61_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_anousu"])){
       $sql  .= $virgula." c61_anousu = $this->c61_anousu ";
       $virgula = ",";
       if(trim($this->c61_anousu) == null ){
         $this->erro_sql = " Campo Exerc�cio nao Informado.";
         $this->erro_campo = "c61_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c61_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_reduz"])){
       $sql  .= $virgula." c61_reduz = $this->c61_reduz ";
       $virgula = ",";
       if(trim($this->c61_reduz) == null ){
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c61_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c61_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_instit"])){
       $sql  .= $virgula." c61_instit = $this->c61_instit ";
       $virgula = ",";
       if(trim($this->c61_instit) == null ){
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "c61_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c61_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_codigo"])){
       $sql  .= $virgula." c61_codigo = $this->c61_codigo ";
       $virgula = ",";
       if(trim($this->c61_codigo) == null ){
         $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
         $this->erro_campo = "c61_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c61_contrapartida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"])){
        if(trim($this->c61_contrapartida)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"])){
           $this->c61_contrapartida = "0" ;
        }
       $sql  .= $virgula." c61_contrapartida = $this->c61_contrapartida ";
       $virgula = ",";
     }
     if(trim($this->c61_codtce)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c61_codtce"])){
        if(trim($this->c61_codtce)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c61_codtce"])){
           $this->c61_codtce = "null" ;
        }
       $sql  .= $virgula." c61_codtce = $this->c61_codtce ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c61_reduz!=null){
       $sql .= " c61_reduz = $this->c61_reduz";
     }
     if($c61_anousu!=null){
       $sql .= " and  c61_anousu = $this->c61_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c61_reduz,$this->c61_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5247,'$this->c61_reduz','A')");
         $resac = db_query("insert into db_acountkey values($acount,8060,'$this->c61_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_codcon"]))
           $resac = db_query("insert into db_acount values($acount,773,5246,'".AddSlashes(pg_result($resaco,$conresaco,'c61_codcon'))."','$this->c61_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_anousu"]))
           $resac = db_query("insert into db_acount values($acount,773,8060,'".AddSlashes(pg_result($resaco,$conresaco,'c61_anousu'))."','$this->c61_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_reduz"]))
           $resac = db_query("insert into db_acount values($acount,773,5247,'".AddSlashes(pg_result($resaco,$conresaco,'c61_reduz'))."','$this->c61_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_instit"]))
           $resac = db_query("insert into db_acount values($acount,773,5248,'".AddSlashes(pg_result($resaco,$conresaco,'c61_instit'))."','$this->c61_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_codigo"]))
           $resac = db_query("insert into db_acount values($acount,773,5249,'".AddSlashes(pg_result($resaco,$conresaco,'c61_codigo'))."','$this->c61_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"]))
           $resac = db_query("insert into db_acount values($acount,773,7372,'".AddSlashes(pg_result($resaco,$conresaco,'c61_contrapartida'))."','$this->c61_contrapartida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "C�digo Reduzido do Plano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c61_reduz."-".$this->c61_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "C�digo Reduzido do Plano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c61_reduz."-".$this->c61_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c61_reduz."-".$this->c61_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c61_reduz=null,$c61_anousu=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c61_reduz,$c61_anousu));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5247,'$c61_reduz','E')");
         $resac = db_query("insert into db_acountkey values($acount,8060,'$c61_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,773,5246,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,773,8060,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,773,5247,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,773,5248,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,773,5249,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,773,7372,'','".AddSlashes(pg_result($resaco,$iresaco,'c61_contrapartida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoreduz
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c61_reduz != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c61_reduz = $c61_reduz ";
        }
        if($c61_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c61_anousu = $c61_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "C�digo Reduzido do Plano nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c61_reduz."-".$c61_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "C�digo Reduzido do Plano nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c61_reduz."-".$c61_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c61_reduz."-".$c61_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoreduz";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c61_reduz=null,$c61_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoreduz ";
     $sql .= "      inner join db_config  on  db_config.codigo = conplanoreduz.c61_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoreduz.c61_codigo";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoreduz.c61_codcon and  conplano.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join conclass  as a on   a.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  as b on   b.c52_codsis = conplano.c60_codsis";
     $sql2 = "";
     if($dbwhere==""){
       if($c61_anousu!=null ){
         $sql2 .= " where conplanoreduz.c61_anousu = $c61_anousu ";
       }
       if($c61_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " conplanoreduz.c61_reduz = $c61_reduz ";
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
   function sql_query_file ( $c61_reduz=null,$c61_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoreduz ";
     $sql2 = "";
     if($dbwhere==""){
       if($c61_anousu!=null ){
         $sql2 .= " where conplanoreduz.c61_anousu = $c61_anousu ";
       }
       if($c61_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " conplanoreduz.c61_reduz = $c61_reduz ";
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

  function sql_query_contabancaria( $c61_codcon=null,$c61_anousu=null, $campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoreduz ";
     $sql .= " inner join conplano              on conplano.c60_codcon = conplanoreduz.c61_codcon"; 
     $sql .= "                                 and conplano.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= " inner join conplanoconta         on conplanoconta.c63_anousu = conplanoreduz.c61_anousu";
     $sql .= "                                 and conplanoconta.c63_codcon = conplanoreduz.c61_codcon";
     $sql .= " inner join conplanocontabancaria on conplanocontabancaria.c56_codcon = conplanoreduz.c61_codcon";
     $sql .= "                                 and conplanocontabancaria.c56_anousu = conplanoreduz.c61_anousu";
     $sql .= " inner join contabancaria         on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria";
     $sql2 = "";
     if($dbwhere==""){
       if($c61_codcon!=null ){
         $sql2 .= " where conplanoreduz.c61_codcon = $c61_codcon ";
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

   function sql_query_razao( $c61_codcon=null,$c61_anousu=null, $campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoreduz ";
     $sql .="      inner join conlancamval on c69_debito = conplanoreduz.c61_reduz or c69_credito = conplanoreduz.c61_reduz";
     $sql .="      inner join conplano on c60_codcon = conplanoreduz.c61_codcon";
     $sql2 = "";
     if($dbwhere==""){
       if($c61_codcon!=null ){
         $sql2 .= " where conplanoreduz.c61_codcon = $c61_codcon ";
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

  /**
   *
   * Busca as contas correntes de um reduzido
   * @return string
   */
  function sql_query_reduz_contacorrente ( $c61_reduz=null,$c61_anousu=null,$campos="*",$ordem=null,$dbwhere="") {

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
  	$sql .= " from conplanoreduz ";
  	$sql .= "      inner join db_config  on  db_config.codigo = conplanoreduz.c61_instit";
  	$sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoreduz.c61_codigo";
  	$sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoreduz.c61_codcon ";
  	$sql .= "       									 and  conplano.c60_anousu = conplanoreduz.c61_anousu ";
  	$sql .= "       left join conplanocontacorrente  on  conplano.c60_codcon = conplanocontacorrente.c18_codcon ";
  	$sql .= "       									              and  conplano.c60_anousu = conplanocontacorrente.c18_anousu ";
  	$sql .= "       left join contacorrente  on contacorrente.c17_sequencial = conplanocontacorrente.c18_contacorrente ";
  	$sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
  	$sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
  	$sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
  	$sql .= "      inner join conclass  as a on   a.c51_codcla = conplano.c60_codcla";
  	$sql .= "      inner join consistema  as b on   b.c52_codsis = conplano.c60_codsis";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c61_anousu!=null ){
  			$sql2 .= " where conplanoreduz.c61_anousu = $c61_anousu ";
  		}
  		if($c61_reduz!=null ){
  		if($sql2!=""){
              $sql2 .= " and ";
           }else{
  			$sql2 .= " where ";
  			}
  				$sql2 .= " conplanoreduz.c61_reduz = $c61_reduz ";
         }
       }else if($dbwhere != ""){
  				$sql2 = " where $dbwhere";
  		}
    $sql .= $sql2;
    if ($ordem != null ) {

	    $sql .= " order by ";
	    $campos_sql = explode("#",$ordem);
	    $virgula = "";

	    for ($i=0;$i<sizeof($campos_sql);$i++) {
	      $sql .= $virgula.$campos_sql[$i];
	      $virgula = ",";
	    }
    }
    return $sql;
  }

  function sql_query_analitica ( $c61_reduz=null,$c61_anousu=null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from conplanoreduz ";
    $sql .= "      inner join conplano  on conplano.c60_codcon = conplanoreduz.c61_codcon";
    $sql .= "                          and conplano.c60_anousu = conplanoreduz.c61_anousu";
    $sql .= "      left  join conplanocontacorrente  on conplanocontacorrente.c18_codcon = conplano.c60_codcon ";
    $sql .= "                                       and conplanocontacorrente.c18_anousu = conplano.c60_anousu AND c18_sequencial IS NULL";
    $sql2 = "";
    if($dbwhere==""){
    		if($c61_anousu!=null ){
    		  $sql2 .= " where conplanoreduz.c61_anousu = $c61_anousu ";
    		}
    		if($c61_reduz!=null ){
    		  if($sql2!=""){
    		    $sql2 .= " and ";
    		  }else{
    		    $sql2 .= " where ";
    		  }
    				$sql2 .= " conplanoreduz.c61_reduz = $c61_reduz ";
    		}
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = explode("#",$ordem);
      $virgula = "";

      for ($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_reduzAberturaExercicio($iAnousuOrigem, $iAnousuDestino, $iInstit)
  {
    $sSqlReduz = " SELECT conplanoreduz.*,
                           tb1.c60_codcon AS c60_codcon2022,
                           tb1.c60_estrut AS c60_estrut2022,
                           tb2.c60_codcon AS c60_codcon2023,
                           tb2.c60_estrut AS c60_estrut2023
                    FROM conplanoreduz ";

    $sSqlReduz .= " LEFT JOIN conplano tb1 ON (tb1.c60_codcon, tb1.c60_anousu) = (c61_codcon, c61_anousu) ";
    $sSqlReduz .= " LEFT JOIN conplano tb2 ON (tb2.c60_estrut, tb2.c60_anousu) = (tb1.c60_estrut, {$iAnousuDestino}) ";
    $sSqlReduz .= " LEFT JOIN conplanoreduz reduzexercicio ON (reduzexercicio.c61_reduz, reduzexercicio.c61_instit) = (conplanoreduz.c61_reduz, conplanoreduz.c61_instit) ";
    $sSqlReduz .= " AND reduzexercicio.c61_anousu = {$iAnousuDestino} ";
    $sSqlReduz .= " WHERE conplanoreduz.c61_anousu = {$iAnousuOrigem} ";
    $sSqlReduz .= "   AND conplanoreduz.c61_instit = {$iInstit} ";
    $sSqlReduz .= "   AND reduzexercicio.c61_anousu IS NULL ";

    return $sSqlReduz;
  }

  public function sql_razaocontas($iAnousu, $txt_where)
  {
    $sSqlReduz = "SELECT conplanoreduz.c61_codcon,
                         conplanoreduz.c61_reduz,
          		           conplano.c60_estrut,
                         conplano.c60_descr,
                         conplano.c60_codcon
                    FROM conplanoreduz ";

    $sSqlReduz .= " inner join conplano     on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=conplanoreduz.c61_anousu ";
    $sSqlReduz .= " where conplanoreduz.c61_anousu = " . $iAnousu . " and " . $txt_where;
    $sSqlReduz .= " order by conplano.c60_estrut";

    return $sSqlReduz;

  }

  public function sql_razaocontasregistros($iAnousu, $txt_where)
  {
    $sSqlReduz = "select distinct conplanoreduz.c61_codcon,
                        conplanoreduz.c61_reduz,
                            conplano.c60_estrut,
                        conplano.c60_descr as conta_descr,
                            c69_codlan,
                        c69_sequen,
                        c69_data,
                        c69_codhist,
                        c53_coddoc,
                        c53_descr,
                        c69_debito,
                                debplano.c60_descr as debito_descr,
                        c69_credito,
                                credplano.c60_descr as credito_descr,
                        c69_valor,
                        case when c71_coddoc = 980
                            then c28_tipo
                            else (case when c69_debito = conplanoreduz.c61_reduz
                            then 'D'
                            else 'C'
                        end) end as tipo,
                                    c50_codhist,
                        c50_descr,
                        c74_codrec,
                        c79_codsup,
                        c75_numemp,
                        e60_codemp,
                        e60_resumo,
                        e60_anousu,
                        c73_coddot,
                        c76_numcgm,
                        c78_chave,
                        c72_complem ,
                        z01_numcgm,
                        z01_nome,
                        ( select k81_codpla
                            from conlancamcorrente
                                    inner join corplacaixa on k82_data = c86_data and k82_id = c86_id and k82_autent = c86_autent
                                    inner join placaixarec on k81_seqpla = k82_seqpla
                            where c86_conlancam = c69_codlan ) as planilha,

                        ( select distinct c84_slip
                            from conlancamcorrente
                                    inner join corlanc on c86_id     = k12_id
                                                    and c86_data   = k12_data
                                                    and c86_autent = k12_autent
                                    inner join conlancamslip on c84_slip = k12_codigo
                            where c86_conlancam = c69_codlan) as slip

                    from conplanoreduz";
    $sSqlReduz .= " inner join conlancamval on  c69_anousu=conplanoreduz.c61_anousu and ( c69_debito=conplanoreduz.c61_reduz or c69_credito = conplanoreduz.c61_reduz)";
    $sSqlReduz .= " inner join conplano     on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=conplanoreduz.c61_anousu";
    $sSqlReduz .= " inner join conplanoreduz debval on debval.c61_anousu = conlancamval.c69_anousu and debval.c61_reduz  = conlancamval.c69_debito";
    $sSqlReduz .= " inner join conplano  debplano  on debplano.c60_anousu = debval.c61_anousu and debplano.c60_codcon = debval.c61_codcon";
    $sSqlReduz .= " inner join conplanoreduz credval on credval.c61_anousu = conlancamval.c69_anousu and credval.c61_reduz  = conlancamval.c69_credito";
    $sSqlReduz .= " inner join conplano  credplano  on credplano.c60_anousu = credval.c61_anousu and credplano.c60_codcon = credval.c61_codcon";
    $sSqlReduz .= " left join conhist          on c50_codhist = c69_codhist";
    $sSqlReduz .= " left join conlancamdoc on c71_codlan  = c69_codlan";
    $sSqlReduz .= " left join conhistdoc   on c53_coddoc  = conlancamdoc.c71_coddoc";
    $sSqlReduz .= " left join conlancamrec on c74_codlan = c69_codlan and c74_anousu = c69_anousu";
    $sSqlReduz .= " left join conlancamsup on c79_codlan = c69_codlan";
    $sSqlReduz .= " left join conlancamemp on c75_codlan = c69_codlan";
    $sSqlReduz .= " left join empempenho   on  e60_numemp = conlancamemp.c75_numemp";
    $sSqlReduz .= " left join conlancamdot on c73_codlan = c69_codlan and c73_anousu = c69_anousu";
    $sSqlReduz .= " left join conlancamcgm on c76_codlan = c69_codlan";
    $sSqlReduz .= " left join cgm on z01_numcgm = c76_numcgm";
    $sSqlReduz .= " left join conlancamdig on c78_codlan = c69_codlan";
    $sSqlReduz .= " left join conlancamcompl on c72_codlan = c69_codlan";
    $sSqlReduz .= " left join contacorrentedetalheconlancamval on c28_conlancamval = c69_sequen";
    $sSqlReduz .= " where conplanoreduz.c61_anousu = {$iAnousu} and {$txt_where}";
    $sSqlReduz .= " order by conplano.c60_estrut, c69_data,c69_codlan,c69_sequen";

    return $sSqlReduz;
  }
}

?>
