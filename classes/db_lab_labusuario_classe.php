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

//MODULO: Laborat�rio
//CLASSE DA ENTIDADE lab_labusuario
class cl_lab_labusuario { 
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
   var $la05_i_codigo = 0; 
   var $la05_i_laboratorio = 0; 
   var $la05_i_usuario = 0; 
   var $la05_d_inicio_dia = null; 
   var $la05_d_inicio_mes = null; 
   var $la05_d_inicio_ano = null; 
   var $la05_d_inicio = null; 
   var $la05_d_fim_dia = null; 
   var $la05_d_fim_mes = null; 
   var $la05_d_fim_ano = null; 
   var $la05_d_fim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la05_i_codigo = int4 = C�digo 
                 la05_i_laboratorio = int4 = Laborat�rio 
                 la05_i_usuario = int4 = Usu�rio 
                 la05_d_inicio = date = In�cio 
                 la05_d_fim = date = Fim 
                 ";
   //funcao construtor da classe 
   function cl_lab_labusuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_labusuario"); 
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
       $this->la05_i_codigo = ($this->la05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_i_codigo"]:$this->la05_i_codigo);
       $this->la05_i_laboratorio = ($this->la05_i_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_i_laboratorio"]:$this->la05_i_laboratorio);
       $this->la05_i_usuario = ($this->la05_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_i_usuario"]:$this->la05_i_usuario);
       if($this->la05_d_inicio == ""){
         $this->la05_d_inicio_dia = ($this->la05_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_dia"]:$this->la05_d_inicio_dia);
         $this->la05_d_inicio_mes = ($this->la05_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_mes"]:$this->la05_d_inicio_mes);
         $this->la05_d_inicio_ano = ($this->la05_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_ano"]:$this->la05_d_inicio_ano);
         if($this->la05_d_inicio_dia != ""){
            $this->la05_d_inicio = $this->la05_d_inicio_ano."-".$this->la05_d_inicio_mes."-".$this->la05_d_inicio_dia;
         }
       }
       if($this->la05_d_fim == ""){
         $this->la05_d_fim_dia = ($this->la05_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_fim_dia"]:$this->la05_d_fim_dia);
         $this->la05_d_fim_mes = ($this->la05_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_fim_mes"]:$this->la05_d_fim_mes);
         $this->la05_d_fim_ano = ($this->la05_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_d_fim_ano"]:$this->la05_d_fim_ano);
         if($this->la05_d_fim_dia != ""){
            $this->la05_d_fim = $this->la05_d_fim_ano."-".$this->la05_d_fim_mes."-".$this->la05_d_fim_dia;
         }
       }
     }else{
       $this->la05_i_codigo = ($this->la05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la05_i_codigo"]:$this->la05_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la05_i_codigo){ 
      $this->atualizacampos();
     if($this->la05_i_laboratorio == null ){ 
       $this->erro_sql = " Campo Laborat�rio nao Informado.";
       $this->erro_campo = "la05_i_laboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la05_i_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "la05_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la05_d_inicio == null ){ 
       $this->erro_sql = " Campo In�cio nao Informado.";
       $this->erro_campo = "la05_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la05_d_fim == null ){ 
       $this->la05_d_fim = "null";
     }
     if($la05_i_codigo == "" || $la05_i_codigo == null ){
       $result = db_query("select nextval('lab_labusuario_la05_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_labusuario_la05_i_codigo_seq do campo: la05_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la05_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_labusuario_la05_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la05_i_codigo)){
         $this->erro_sql = " Campo la05_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la05_i_codigo = $la05_i_codigo; 
       }
     }
     if(($this->la05_i_codigo == null) || ($this->la05_i_codigo == "") ){ 
       $this->erro_sql = " Campo la05_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_labusuario(
                                       la05_i_codigo 
                                      ,la05_i_laboratorio 
                                      ,la05_i_usuario 
                                      ,la05_d_inicio 
                                      ,la05_d_fim 
                       )
                values (
                                $this->la05_i_codigo 
                               ,$this->la05_i_laboratorio 
                               ,$this->la05_i_usuario 
                               ,".($this->la05_d_inicio == "null" || $this->la05_d_inicio == ""?"null":"'".$this->la05_d_inicio."'")." 
                               ,".($this->la05_d_fim == "null" || $this->la05_d_fim == ""?"null":"'".$this->la05_d_fim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_labusuario ($this->la05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_labusuario j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_labusuario ($this->la05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la05_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la05_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15718,'$this->la05_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2756,15718,'','".AddSlashes(pg_result($resaco,0,'la05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2756,15719,'','".AddSlashes(pg_result($resaco,0,'la05_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2756,15720,'','".AddSlashes(pg_result($resaco,0,'la05_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2756,15721,'','".AddSlashes(pg_result($resaco,0,'la05_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2756,15722,'','".AddSlashes(pg_result($resaco,0,'la05_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la05_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_labusuario set ";
     $virgula = "";
     if(trim($this->la05_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la05_i_codigo"])){ 
       $sql  .= $virgula." la05_i_codigo = $this->la05_i_codigo ";
       $virgula = ",";
       if(trim($this->la05_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la05_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la05_i_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la05_i_laboratorio"])){ 
       $sql  .= $virgula." la05_i_laboratorio = $this->la05_i_laboratorio ";
       $virgula = ",";
       if(trim($this->la05_i_laboratorio) == null ){ 
         $this->erro_sql = " Campo Laborat�rio nao Informado.";
         $this->erro_campo = "la05_i_laboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la05_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la05_i_usuario"])){ 
       $sql  .= $virgula." la05_i_usuario = $this->la05_i_usuario ";
       $virgula = ",";
       if(trim($this->la05_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "la05_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la05_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." la05_d_inicio = '$this->la05_d_inicio' ";
       $virgula = ",";
       if(trim($this->la05_d_inicio) == null ){ 
         $this->erro_sql = " Campo In�cio nao Informado.";
         $this->erro_campo = "la05_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la05_d_inicio_dia"])){ 
         $sql  .= $virgula." la05_d_inicio = null ";
         $virgula = ",";
         if(trim($this->la05_d_inicio) == null ){ 
           $this->erro_sql = " Campo In�cio nao Informado.";
           $this->erro_campo = "la05_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la05_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la05_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la05_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la05_d_fim = '$this->la05_d_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la05_d_fim_dia"])){ 
         $sql  .= $virgula." la05_d_fim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($la05_i_codigo!=null){
       $sql .= " la05_i_codigo = $this->la05_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la05_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15718,'$this->la05_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la05_i_codigo"]) || $this->la05_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2756,15718,'".AddSlashes(pg_result($resaco,$conresaco,'la05_i_codigo'))."','$this->la05_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la05_i_laboratorio"]) || $this->la05_i_laboratorio != "")
           $resac = db_query("insert into db_acount values($acount,2756,15719,'".AddSlashes(pg_result($resaco,$conresaco,'la05_i_laboratorio'))."','$this->la05_i_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la05_i_usuario"]) || $this->la05_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2756,15720,'".AddSlashes(pg_result($resaco,$conresaco,'la05_i_usuario'))."','$this->la05_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la05_d_inicio"]) || $this->la05_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,2756,15721,'".AddSlashes(pg_result($resaco,$conresaco,'la05_d_inicio'))."','$this->la05_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la05_d_fim"]) || $this->la05_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2756,15722,'".AddSlashes(pg_result($resaco,$conresaco,'la05_d_fim'))."','$this->la05_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_labusuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_labusuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la05_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la05_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15718,'$la05_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2756,15718,'','".AddSlashes(pg_result($resaco,$iresaco,'la05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2756,15719,'','".AddSlashes(pg_result($resaco,$iresaco,'la05_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2756,15720,'','".AddSlashes(pg_result($resaco,$iresaco,'la05_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2756,15721,'','".AddSlashes(pg_result($resaco,$iresaco,'la05_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2756,15722,'','".AddSlashes(pg_result($resaco,$iresaco,'la05_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_labusuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la05_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la05_i_codigo = $la05_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_labusuario nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_labusuario nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la05_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_labusuario";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_labusuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_labusuario.la05_i_usuario";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labusuario.la05_i_laboratorio";
     $sql2 = "";
     if($dbwhere==""){
       if($la05_i_codigo!=null ){
         $sql2 .= " where lab_labusuario.la05_i_codigo = $la05_i_codigo "; 
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
   function sql_query_file ( $la05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_labusuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($la05_i_codigo!=null ){
         $sql2 .= " where lab_labusuario.la05_i_codigo = $la05_i_codigo "; 
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