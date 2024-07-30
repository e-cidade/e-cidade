<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensexcluidos
class cl_bensexcluidos { 
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
   var $t136_sequencial = 0; 
   var $t136_bens = 0; 
   var $t136_empnotaitem = 0; 
   var $t136_numcgm = 0;
   var $t136_instit = 0;
   var $t136_depart = 0;
   var $t136_codcla = 0;
   var $t136_bensmarca = 0;
   var $t136_bensmodelo = 0;
   var $t136_bensmedida = 0;
   var $t136_descr = 0;
   var $t136_dtaqu = 0;
   var $t136_dtaqu_dia = null;
   var $t136_dtaqu_mes = null;
   var $t136_dtaqu_ano = null;
   var $t136_obs = 0;
   var $t136_valaqu = 0;
   var $t136_ident = 0;
   
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t136_sequencial = int8 = Sequencial 
                 t136_bens = int8 = Bem 
                 t136_descr = varchar(100) = Descrição do bem
                 t136_dtaqu = date = Data da aquisição
                 t136_obs = text = Observações
                 t136_valaqu = float8 = Valor da aquisição
                 t136_ident = varchar(20) = Placa
                 t136_empnotaitem = int8 = Empnotaitem 
                 t136_numcgm = int8 = Fornecedor
                 t136_instit = int8 = Instituição
                 t136_depart = int8 = Departamento
                 t136_codcla = int8 = Classificação
                 t136_bensmarca = int8 = Cód Marca
                 t136_bensmodelo = int8 = Cód Modelo
                 t136_bensmedida = int8 = Cód Medida
                 ";
   //funcao construtor da classe 
   function cl_bensexcluidos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensexcluidos"); 
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
       $this->t136_sequencial = ($this->t136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_sequencial"]:$this->t136_sequencial);
       $this->t136_bens = ($this->t136_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_bens"]:$this->t136_bens);
       $this->t136_empnotaitem = ($this->t136_empnotaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_empnotaitem"]:$this->t136_empnotaitem);
       $this->t136_numcgm = ($this->t136_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_numcgm"]:$this->t136_numcgm);
       $this->t136_instit = ($this->t136_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_instit"]:$this->t136_instit);
       $this->t136_depart = ($this->t136_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_depart"]:$this->t136_depart);
       $this->t136_codcla = ($this->t136_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_codcla"]:$this->t136_codcla);
       $this->t136_bensmarca = ($this->t136_bensmarca == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_bensmarca"]:$this->t136_bensmarca);
       $this->t136_bensmedida = ($this->t136_bensmedida == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_bensmedida"]:$this->t136_bensmedida);
       $this->t136_bensmodelo = ($this->t136_bensmodelo == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_bensmodelo"]:$this->t136_bensmodelo);
       $this->t136_descr = ($this->t136_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_descr"]:$this->t136_descr);
       $this->t136_ident = ($this->t136_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_ident"]:$this->t136_ident);
       $this->t136_valaqu = ($this->t136_valaqu == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_valaqu"]:$this->t136_valaqu);
       $this->t136_obs = ($this->t136_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_obs"]:$this->t136_obs);
       if($this->t136_dtaqu == ""){
        $this->t136_dtaqu_dia = ($this->t136_dtaqu_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_dtaqu_dia"]:$this->t136_dtaqu_dia);
        $this->t136_dtaqu_mes = ($this->t136_dtaqu_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_dtaqu_mes"]:$this->t136_dtaqu_mes);
        $this->t136_dtaqu_ano = ($this->t136_dtaqu_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_dtaqu_ano"]:$this->t136_dtaqu_ano);
        if($this->t136_dtaqu_dia != ""){
          $this->t136_dtaqu = $this->t136_dtaqu_ano."-".$this->t136_dtaqu_mes."-".$this->t136_dtaqu_dia;
        }
      }
      }else{
       $this->t136_sequencial = ($this->t136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t136_sequencial"]:$this->t136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t136_sequencial){ 
      $this->atualizacampos();
     if($this->t136_bens == null ){ 
       $this->erro_sql = " Campo Bem nao Informado.";
       $this->erro_campo = "t136_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t136_numcgm == null ){
      $this->erro_sql = " Campo Fornecedor nao Informado.";
      $this->erro_campo = "t136_numcgm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_codcla == null ){
      $this->erro_sql = " Campo Classificação nao Informado.";
      $this->erro_campo = "t136_codcla";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_depart == null ){
      $this->erro_sql = " Campo Departamento nao Informado.";
      $this->erro_campo = "t136_depart";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_instit == null ){
      $this->erro_sql = " Campo Instituição nao Informado.";
      $this->erro_campo = "t136_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_bensmarca == null ){
      $this->erro_sql = " Campo Cód Marca nao Informado.";
      $this->erro_campo = "t136_bensmarca";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_bensmedida == null ){
      $this->erro_sql = " Campo Cód Medida nao Informado.";
      $this->erro_campo = "t136_bensmedida";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t136_bensmodelo == null ){
      $this->erro_sql = " Campo Cód Modelo nao Informado.";
      $this->erro_campo = "t136_bensmodelo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
     if($this->t136_empnotaitem == null ){ 
       $this->erro_sql = " Campo Empnotaitem nao Informado.";
       $this->erro_campo = "t136_empnotaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t136_sequencial == "" || $t136_sequencial == null ){
       $result = db_query("select nextval('bensexcluidos_t136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensexcluidos_t136_sequencial_seq do campo: t136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensexcluidos_t136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t136_sequencial)){
         $this->erro_sql = " Campo t136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t136_sequencial = $t136_sequencial; 
       }
     }
     if(($this->t136_sequencial == null) || ($this->t136_sequencial == "") ){ 
       $this->erro_sql = " Campo t136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensexcluidos(
                                       t136_sequencial 
                                      ,t136_bens 
                                      ,t136_empnotaitem 
                                      ,t136_numcgm
                                      ,t136_instit
                                      ,t136_depart
                                      ,t136_codcla
                                      ,t136_bensmarca
                                      ,t136_bensmodelo
                                      ,t136_bensmedida
                                      ,t136_descr
                                      ,t136_dtaqu
                                      ,t136_obs
                                      ,t136_valaqu
                                      ,t136_ident 
                       )
                values (
                                $this->t136_sequencial 
                               ,$this->t136_bens 
                               ,$this->t136_empnotaitem 
                               ,$this->t136_numcgm
                               ,$this->t136_instit
                               ,$this->t136_depart
                               ,$this->t136_codcla
                               ,$this->t136_bensmarca
                               ,$this->t136_bensmodelo
                               ,$this->t136_bensmedida
                               ,'$this->t136_descr'
                               ,".($this->t136_dtaqu == "null" || $this->t136_dtaqu == ""?"null":"'".$this->t136_dtaqu."'")."
                               ,'$this->t136_obs'
                               ,$this->t136_valaqu
                               ,'$this->t136_ident '
                      )"; 
     $result = db_query($sql);
   
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bensexcluidos ($this->t136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "bensexcluidos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "bensexcluidos ($this->t136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t136_sequencial));
    //  if(($resaco!=false)||($this->numrows!=0)){
    //    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
    //    $acount = pg_result($resac,0,0);
    //    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
    //    $resac = db_query("insert into db_acountkey values($acount,18887,'$this->t136_sequencial','I')");
    //    $resac = db_query("insert into db_acount values($acount,3349,18887,'','".AddSlashes(pg_result($resaco,0,'t136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    //    $resac = db_query("insert into db_acount values($acount,3349,18888,'','".AddSlashes(pg_result($resaco,0,'t136_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    //    $resac = db_query("insert into db_acount values($acount,3349,18889,'','".AddSlashes(pg_result($resaco,0,'t136_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    //  }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bensexcluidos set ";
     $virgula = "";
     if(trim($this->t136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t136_sequencial"])){ 
       $sql  .= $virgula." t136_sequencial = $this->t136_sequencial ";
       $virgula = ",";
       if(trim($this->t136_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t136_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t136_bens"])){ 
       $sql  .= $virgula." t136_bens = $this->t136_bens ";
       $virgula = ",";
       if(trim($this->t136_bens) == null ){ 
         $this->erro_sql = " Campo Bem nao Informado.";
         $this->erro_campo = "t136_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t136_empnotaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t136_empnotaitem"])){ 
       $sql  .= $virgula." t136_empnotaitem = $this->t136_empnotaitem ";
       $virgula = ",";
       if(trim($this->t136_empnotaitem) == null ){ 
         $this->erro_sql = " Campo Empnotaitem nao Informado.";
         $this->erro_campo = "t136_empnotaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t136_sequencial!=null){
       $sql .= " t136_sequencial = $this->t136_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t136_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18887,'$this->t136_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t136_sequencial"]) || $this->t136_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3349,18887,'".AddSlashes(pg_result($resaco,$conresaco,'t136_sequencial'))."','$this->t136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t136_bens"]) || $this->t136_bens != "")
           $resac = db_query("insert into db_acount values($acount,3349,18888,'".AddSlashes(pg_result($resaco,$conresaco,'t136_bens'))."','$this->t136_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t136_empnotaitem"]) || $this->t136_empnotaitem != "")
           $resac = db_query("insert into db_acount values($acount,3349,18889,'".AddSlashes(pg_result($resaco,$conresaco,'t136_empnotaitem'))."','$this->t136_empnotaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bensexcluidos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bensexcluidos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t136_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t136_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18887,'$t136_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3349,18887,'','".AddSlashes(pg_result($resaco,$iresaco,'t136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3349,18888,'','".AddSlashes(pg_result($resaco,$iresaco,'t136_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3349,18889,'','".AddSlashes(pg_result($resaco,$iresaco,'t136_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensexcluidos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t136_sequencial = $t136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bensexcluidos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bensexcluidos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:bensexcluidos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t136_sequencial=null,$campos="*",$ordem=null,$dbwhere="")
   { 
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
     $sql .= " from bensexcluidos ";
   
     $sql2 = "";
     if($dbwhere==""){
       if($t136_sequencial!=null ){
         $sql2 .= " where bensexcluidos.t136_sequencial = $t136_sequencial "; 
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
   function sql_query_file ( $t136_sequencial=null,$campos="*",$ordem=null,$dbwhere="")
   { 
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
     $sql .= " from bensexcluidos ";
     $sql2 = "";
     if($dbwhere==""){
       if($t136_sequencial!=null ){
         $sql2 .= " where bensexcluidos.t136_sequencial = $t136_sequencial "; 
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
  function sql_query_bens_excluidos ( $t136_sequencial=null,$campos="*",$ordem=null,$dbwhere="")
  {
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
  	$sql .= " from bensexcluidos ";
  	$sql .= "      inner join empnotaitem   on  empnotaitem.e72_sequencial = bensexcluidos.t136_empnotaitem";
  	$sql .= "      inner join empempitem 		on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
  	$sql .= "      inner join empnota  as a on  a.e69_codnota = empnotaitem.e72_codnota";
  	$sql .= "      inner join empnotaele    on  empnotaele.e70_codnota = a.e69_codnota";
  	$sql .= "      inner join orcelemento   on  orcelemento.o56_codele = empnotaele.e70_codele and orcelemento.o56_anousu =  a.e69_anousu";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($t136_sequencial!=null ){
  			$sql2 .= " where bensexcluidos.t136_sequencial = $t136_sequencial ";
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

     function sql_query_dados_bens_excluidos( $t136_bem=null,$campos="*",$ordem=null,$dbwhere="") 
     {
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
      $sql .= " from bensexcluidos ";
      $sql .= "      inner join cgm                on  cgm.z01_numcgm = bensexcluidos.t136_numcgm";
      $sql .= "      inner join db_depart          on  db_depart.coddepto = bensexcluidos.t136_depart";
      $sql .= "      inner join clabens            on  clabens.t64_codcla = bensexcluidos.t136_codcla";
      $sql .= "      inner join clabensconplano    on  clabensconplano.t86_clabens = clabens.t64_codcla";
      $sql .= "                                    and clabensconplano.t86_anousu  = ".db_getsession("DB_anousu");
      $sql .= "      inner join conplano           on  conplano.c60_codcon  = clabensconplano.t86_conplano";
      $sql .= "                                    and conplano.c60_anousu = ".db_getsession("DB_anousu");
      $sql .= "      left  join bensdiv     on bensdiv.t33_bem = bensexcluidos.t136_bens";
      $sql .= "      left  join departdiv   on  departdiv.t30_codigo = bensdiv.t33_divisao and departdiv.t30_depto = bensexcluidos.t136_depart";
      $sql .= "      inner join bensmarca   on  bensmarca.t65_sequencial = bensexcluidos.t136_bensmarca";
      $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bensexcluidos.t136_bensmodelo";
      $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bensexcluidos.t136_bensmedida";
      $sql .= "      left join bensbaix     on  bensbaix.t55_codbem = bensexcluidos.t136_bens";
      $sql .= "      left join benscedente     on t09_bem   = bensexcluidos.t136_bens";
      $sql .= "      left join bensdepreciacao on t44_bens  = bensexcluidos.t136_bens";
      $sql2 = "";
      if($dbwhere==""){
        if($t136_bem!=null ){
          $sql2 .= " where bensexcluidos.t136_bens = $t136_bem ";
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