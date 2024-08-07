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
//CLASSE DA ENTIDADE lab_ausencia
class cl_lab_ausencia { 
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
   var $la36_i_codigo = 0; 
   var $la36_i_setorexame = 0; 
   var $la36_d_ini_dia = null; 
   var $la36_d_ini_mes = null; 
   var $la36_d_ini_ano = null; 
   var $la36_d_ini = null; 
   var $la36_d_fim_dia = null; 
   var $la36_d_fim_mes = null; 
   var $la36_d_fim_ano = null; 
   var $la36_d_fim = null; 
   var $la36_c_horaini = null; 
   var $la36_c_horafim = null; 
   var $la36_i_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la36_i_codigo = int4 = C�digo 
                 la36_i_setorexame = int4 = Setor Exame 
                 la36_d_ini = date = In�cio 
                 la36_d_fim = date = Fim 
                 la36_c_horaini = char(5) = Hora Inicial 
                 la36_c_horafim = char(5) = Hora final 
                 la36_i_tipo = int8 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_lab_ausencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_ausencia"); 
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
       $this->la36_i_codigo = ($this->la36_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_i_codigo"]:$this->la36_i_codigo);
       $this->la36_i_setorexame = ($this->la36_i_setorexame == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_i_setorexame"]:$this->la36_i_setorexame);
       if($this->la36_d_ini == ""){
         $this->la36_d_ini_dia = ($this->la36_d_ini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_ini_dia"]:$this->la36_d_ini_dia);
         $this->la36_d_ini_mes = ($this->la36_d_ini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_ini_mes"]:$this->la36_d_ini_mes);
         $this->la36_d_ini_ano = ($this->la36_d_ini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_ini_ano"]:$this->la36_d_ini_ano);
         if($this->la36_d_ini_dia != ""){
            $this->la36_d_ini = $this->la36_d_ini_ano."-".$this->la36_d_ini_mes."-".$this->la36_d_ini_dia;
         }
       }
       if($this->la36_d_fim == ""){
         $this->la36_d_fim_dia = ($this->la36_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_fim_dia"]:$this->la36_d_fim_dia);
         $this->la36_d_fim_mes = ($this->la36_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_fim_mes"]:$this->la36_d_fim_mes);
         $this->la36_d_fim_ano = ($this->la36_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_d_fim_ano"]:$this->la36_d_fim_ano);
         if($this->la36_d_fim_dia != ""){
            $this->la36_d_fim = $this->la36_d_fim_ano."-".$this->la36_d_fim_mes."-".$this->la36_d_fim_dia;
         }
       }
       $this->la36_c_horaini = ($this->la36_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_c_horaini"]:$this->la36_c_horaini);
       $this->la36_c_horafim = ($this->la36_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_c_horafim"]:$this->la36_c_horafim);
       $this->la36_i_tipo = ($this->la36_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_i_tipo"]:$this->la36_i_tipo);
     }else{
       $this->la36_i_codigo = ($this->la36_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la36_i_codigo"]:$this->la36_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la36_i_codigo){ 
      $this->atualizacampos();
     if($this->la36_i_setorexame == null ){ 
       $this->erro_sql = " Campo Setor Exame nao Informado.";
       $this->erro_campo = "la36_i_setorexame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la36_d_ini == null ){ 
       $this->erro_sql = " Campo In�cio nao Informado.";
       $this->erro_campo = "la36_d_ini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la36_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "la36_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la36_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "la36_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la36_i_codigo == "" || $la36_i_codigo == null ){
       $result = db_query("select nextval('lab_ausencia_la36_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_ausencia_la36_i_codigo_seq do campo: la36_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la36_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_ausencia_la36_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la36_i_codigo)){
         $this->erro_sql = " Campo la36_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la36_i_codigo = $la36_i_codigo; 
       }
     }
     if(($this->la36_i_codigo == null) || ($this->la36_i_codigo == "") ){ 
       $this->erro_sql = " Campo la36_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_ausencia(
                                       la36_i_codigo 
                                      ,la36_i_setorexame 
                                      ,la36_d_ini 
                                      ,la36_d_fim 
                                      ,la36_c_horaini 
                                      ,la36_c_horafim 
                                      ,la36_i_tipo 
                       )
                values (
                                $this->la36_i_codigo 
                               ,$this->la36_i_setorexame 
                               ,".($this->la36_d_ini == "null" || $this->la36_d_ini == ""?"null":"'".$this->la36_d_ini."'")." 
                               ,".($this->la36_d_fim == "null" || $this->la36_d_fim == ""?"null":"'".$this->la36_d_fim."'")." 
                               ,'$this->la36_c_horaini' 
                               ,'$this->la36_c_horafim' 
                               ,$this->la36_i_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_ausencia ($this->la36_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_ausencia j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_ausencia ($this->la36_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la36_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la36_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15805,'$this->la36_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2777,15805,'','".AddSlashes(pg_result($resaco,0,'la36_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,15806,'','".AddSlashes(pg_result($resaco,0,'la36_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,15807,'','".AddSlashes(pg_result($resaco,0,'la36_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,15808,'','".AddSlashes(pg_result($resaco,0,'la36_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,15809,'','".AddSlashes(pg_result($resaco,0,'la36_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,15810,'','".AddSlashes(pg_result($resaco,0,'la36_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2777,16032,'','".AddSlashes(pg_result($resaco,0,'la36_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la36_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_ausencia set ";
     $virgula = "";
     if(trim($this->la36_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_i_codigo"])){ 
       $sql  .= $virgula." la36_i_codigo = $this->la36_i_codigo ";
       $virgula = ",";
       if(trim($this->la36_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la36_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la36_i_setorexame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_i_setorexame"])){ 
       $sql  .= $virgula." la36_i_setorexame = $this->la36_i_setorexame ";
       $virgula = ",";
       if(trim($this->la36_i_setorexame) == null ){ 
         $this->erro_sql = " Campo Setor Exame nao Informado.";
         $this->erro_campo = "la36_i_setorexame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la36_d_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_d_ini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la36_d_ini_dia"] !="") ){ 
       $sql  .= $virgula." la36_d_ini = '$this->la36_d_ini' ";
       $virgula = ",";
       if(trim($this->la36_d_ini) == null ){ 
         $this->erro_sql = " Campo In�cio nao Informado.";
         $this->erro_campo = "la36_d_ini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la36_d_ini_dia"])){ 
         $sql  .= $virgula." la36_d_ini = null ";
         $virgula = ",";
         if(trim($this->la36_d_ini) == null ){ 
           $this->erro_sql = " Campo In�cio nao Informado.";
           $this->erro_campo = "la36_d_ini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la36_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la36_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la36_d_fim = '$this->la36_d_fim' ";
       $virgula = ",";
       if(trim($this->la36_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "la36_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la36_d_fim_dia"])){ 
         $sql  .= $virgula." la36_d_fim = null ";
         $virgula = ",";
         if(trim($this->la36_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "la36_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la36_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_c_horaini"])){ 
       $sql  .= $virgula." la36_c_horaini = '$this->la36_c_horaini' ";
       $virgula = ",";
     }
     if(trim($this->la36_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_c_horafim"])){ 
       $sql  .= $virgula." la36_c_horafim = '$this->la36_c_horafim' ";
       $virgula = ",";
     }
     if(trim($this->la36_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la36_i_tipo"])){ 
       $sql  .= $virgula." la36_i_tipo = $this->la36_i_tipo ";
       $virgula = ",";
       if(trim($this->la36_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "la36_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la36_i_codigo!=null){
       $sql .= " la36_i_codigo = $this->la36_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la36_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15805,'$this->la36_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_i_codigo"]) || $this->la36_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2777,15805,'".AddSlashes(pg_result($resaco,$conresaco,'la36_i_codigo'))."','$this->la36_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_i_setorexame"]) || $this->la36_i_setorexame != "")
           $resac = db_query("insert into db_acount values($acount,2777,15806,'".AddSlashes(pg_result($resaco,$conresaco,'la36_i_setorexame'))."','$this->la36_i_setorexame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_d_ini"]) || $this->la36_d_ini != "")
           $resac = db_query("insert into db_acount values($acount,2777,15807,'".AddSlashes(pg_result($resaco,$conresaco,'la36_d_ini'))."','$this->la36_d_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_d_fim"]) || $this->la36_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2777,15808,'".AddSlashes(pg_result($resaco,$conresaco,'la36_d_fim'))."','$this->la36_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_c_horaini"]) || $this->la36_c_horaini != "")
           $resac = db_query("insert into db_acount values($acount,2777,15809,'".AddSlashes(pg_result($resaco,$conresaco,'la36_c_horaini'))."','$this->la36_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_c_horafim"]) || $this->la36_c_horafim != "")
           $resac = db_query("insert into db_acount values($acount,2777,15810,'".AddSlashes(pg_result($resaco,$conresaco,'la36_c_horafim'))."','$this->la36_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la36_i_tipo"]) || $this->la36_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2777,16032,'".AddSlashes(pg_result($resaco,$conresaco,'la36_i_tipo'))."','$this->la36_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_ausencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la36_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_ausencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la36_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la36_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la36_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la36_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15805,'$la36_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2777,15805,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,15806,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,15807,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,15808,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,15809,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,15810,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2777,16032,'','".AddSlashes(pg_result($resaco,$iresaco,'la36_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_ausencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la36_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la36_i_codigo = $la36_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_ausencia nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la36_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_ausencia nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la36_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la36_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_ausencia";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la36_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_ausencia ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = lab_ausencia.la36_i_tipo";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_ausencia.la36_i_setorexame";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql2 = "";
     if($dbwhere==""){
       if($la36_i_codigo!=null ){
         $sql2 .= " where lab_ausencia.la36_i_codigo = $la36_i_codigo "; 
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
   function sql_query_file ( $la36_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_ausencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($la36_i_codigo!=null ){
         $sql2 .= " where lab_ausencia.la36_i_codigo = $la36_i_codigo "; 
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
   function sql_query_laboratorio ( $la36_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_ausencia ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_ausencia.la36_i_setorexame";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql2 = "";
     if($dbwhere==""){
       if($la36_i_codigo!=null ){
         $sql2 .= " where lab_ausencia.la36_i_codigo = $la36_i_codigo ";
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

function laboratorioausencia($la09_i_codigo, $sHoraIni, $sHoraFim, $dData) {

  $sMotivo = '';
  $sSep    = '';

  $sSql    = $this->sql_query('', 'la36_c_horaini, la36_c_horafim, s139_c_descr', '',
                              " la09_i_codigo = $la09_i_codigo and ($dData >= la36_d_ini and $dData <= la36_d_fim)"
                             );
  $rs      = $this->sql_record($sSql);

  for ($iCont = 0; $iCont < $this->numrows; $iCont++) {
      
    $oAusencias = db_utils::fieldsMemory($rs, $iCont);
    if ($oAusencias->la36_c_horaini != ''){

      $iMinHoraIni = $this->HoraToMin($sHoraIni);
      $iMinHoraFim = $this->HoraToMin($sHoraFim);
      $iMinAusIni  = $this->HoraToMin($oAusencias->la36_c_horaini);
      $iMinAusFim  = $this->HoraToMin($oAusencias->la36_c_horafim);
      if ($this->PeriodoPertense($iMinHoraIni, $iMinHoraFim, $iMinAusIni, $iMinAusFim)) {

        $sMotivo .= $sSep.$oAusencias->la36_c_horaini.' - '.$oAusencias->la36_c_horafim.' - ';
        $sMotivo .= $oAusencias->s139_c_descr;
        $sSep     = ', ';

      }

    } else { // Aus�ncia o dia inteiro

      $sMotivo = '00:00 - 23:59 - '.$oAusencias->s139_c_descr;
      break;

    }

  }

  return $sMotivo; 

}

 function HoraToMin($hora){
    $hora=str_replace("'","",$hora);
    $aVet=explode(":",$hora);
    $minutos=( ((int)$aVet[0]) *60)+ ((int)$aVet[1]);
    return $minutos;
 }
 function PeriodoPertense($ini_1,$fim_1,$ini_2,$fim_2){
    if( ( ($ini_1>=$ini_2)&&($ini_1<=$fim_2) )
             ||
        ( ($fim_1>=$ini_2)&&($fim_1<=$fim_2) )
      ){
       return true;  
    }else{
       return false;
    }
 } 
}
?>