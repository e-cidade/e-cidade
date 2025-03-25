<?php

global $conn;
if(empty($conn)) {
    $DB_SERVIDOR = db_getsession("DB_servidor");
    $DB_BASE     = db_getsession("DB_base");
    $DB_PORTA    = db_getsession("DB_porta");
    $DB_USUARIO  = db_getsession("DB_user");
    $DB_SENHA    = db_getsession("DB_senha");

    if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

        echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
        session_destroy();
        exit;
    }
}
$result = db_query("select descricao from db_itensmenu where funcao = '".basename($_SERVER['PHP_SELF'])."'");
if(pg_numrows($result) > 0)
  $str = pg_result($result,0,0);
else
  $str = basename($_SERVER['PHP_SELF']);

$result = db_query("select uol_id from db_usuariosonline
  where uol_id = ".db_getsession("DB_id_usuario")."
  and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."'
  and uol_hora = ".db_getsession("DB_uol_hora"));
if(pg_numrows($result) == 0) {
  $hora = time();
  db_query($conn,"insert into db_usuariosonline
    values(".db_getsession("DB_id_usuario").",
      ".$hora.",
      '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."',
      '".db_getsession("DB_login")."',
      '".$str."',
      '".db_getsession("DB_nome_modulo")."',
      ".time().")") or die("Erro:(27) inserindo arquivo em db_usuariosonline: ".pg_errormessage());
  db_putsession("DB_uol_hora",$hora);
} else {
  db_query("update db_usuariosonline set
    uol_arquivo = '".$str."',
    uol_inativo = ".time()."
    where uol_id = ".db_getsession("DB_id_usuario")."
    and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."'
    and uol_hora = ".db_getsession("DB_uol_hora")."
    ") or die("Erro(26) atualizando db_usuariosonline");
}
