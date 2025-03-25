<?php
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
$oGet = db_utils::postMemory($_GET);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$sql = "SELECT db_usuarios.id_usuario,
       z01_numcgm,
       z01_nome,
       login
FROM db_usuarios
JOIN db_usuacgm ON db_usuacgm.id_usuario = db_usuarios.id_usuario
JOIN cgm ON z01_numcgm = cgmlogin
JOIN db_userinst on db_userinst.id_usuario = db_usuarios.id_usuario
WHERE usuarioativo = $filtrar and db_usuarios.id_usuario not in (SELECT db_usuarios.id_usuario
FROM db_usuarios
WHERE login like '%contass%') and id_instit = ".db_getsession('DB_instit')." order by z01_nome";

$rsUsr = db_query($sql) or die(pg_last_error());

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->addpage('R');

$pdf->SetFont('arial', 'B', 10);
$pdf->cell(25, 6, "Cód. Usuário",1, 0, "C", 0);
$pdf->cell(10, 6, "CGM", 1, 0, "C", 0);
$pdf->cell(80, 6, "Nome", 1, 0, "C", 0);
$pdf->cell(25, 6, "Login", 1, 0, "C", 0);
$pdf->cell(140, 6, "Áreas Liberadas", 1, 1, "C", 0);
$pdf->SetFont('arial', '', 8);

for ($usr = 0; $usr < pg_num_rows($rsUsr); $usr++) {

    $oUsr = db_utils::fieldsMemory($rsUsr, $usr);

    $sqlAreasLiberadas = "
        SELECT DISTINCT at26_sequencial AS id,
                at25_descr AS nome
            FROM atendcadarea
            INNER JOIN atendcadareamod ON at26_sequencial = at26_codarea
            WHERE at26_id_item IN
                    ( SELECT id_item
                     FROM
                         (SELECT DISTINCT i.id_modulo AS id_item,
                                          m.descr_modulo,
                                          it.help,
                                          it.funcao,
                                          m.imagem,
                                          m.nome_modulo,
                                          CASE
                                              WHEN u.anousu IS NULL THEN to_char(CURRENT_DATE,'YYYY')::int4
                                              ELSE u.anousu
                                          END
                          FROM
                              (SELECT DISTINCT i.itemativo,
                                               p.id_modulo,
                                               p.id_usuario,
                                               p.id_instit
                               FROM db_permissao p
                               INNER JOIN db_itensmenu i ON p.id_item = i.id_item
                               WHERE i.itemativo = 1
                                   AND p.id_usuario = $oUsr->id_usuario
                                   AND p.id_instit = ".db_getsession('DB_instit')."
                                   AND p.anousu = ".db_getsession('DB_anousu')." ) AS i
                          INNER JOIN db_modulos m ON m.id_item = i.id_modulo
                          INNER JOIN db_itensmenu it ON it.id_item = i.id_modulo
                          LEFT OUTER JOIN db_usumod u ON u.id_item = i.id_modulo
                          AND u.id_usuario = i.id_usuario
                          WHERE i.id_usuario = $oUsr->id_usuario
                              AND i.id_instit = ".db_getsession('DB_instit')."
                              AND libcliente IS TRUE
                          UNION SELECT DISTINCT i.id_modulo AS id_item,
                                                m.descr_modulo,
                                                it.help,
                                                it.funcao,
                                                m.imagem,
                                                m.nome_modulo,
                                                CASE
                                                    WHEN u.anousu IS NULL THEN to_char(CURRENT_DATE,'YYYY')::int4
                                                    ELSE u.anousu
                                                END
                          FROM
                              (SELECT DISTINCT i.itemativo,
                                               p.id_modulo,
                                               h.id_usuario,
                                               p.id_instit
                               FROM db_permissao p
                               INNER JOIN db_permherda h ON h.id_perfil = p.id_usuario
                               INNER JOIN db_usuarios u ON u.id_usuario = h.id_perfil
                               AND u.usuarioativo = '1'
                               INNER JOIN db_itensmenu i ON p.id_item = i.id_item
                               WHERE i.itemativo = 1
                                   AND h.id_usuario = $oUsr->id_usuario
                                   AND p.id_instit = ".db_getsession('DB_instit')."
                                   AND p.anousu = ".db_getsession('DB_anousu')." ) AS i
                          INNER JOIN db_modulos m ON m.id_item = i.id_modulo
                          INNER JOIN db_itensmenu it ON it.id_item = i.id_modulo
                          LEFT OUTER JOIN db_usumod u ON u.id_item = i.id_modulo
                          AND u.id_usuario = i.id_usuario
                          WHERE i.id_usuario = $oUsr->id_usuario
                              AND libcliente IS TRUE
                              AND i.id_instit = 1 ) AS yyyy
                     ORDER BY nome_modulo)
            ORDER BY at25_descr
    ";
    $rsUsrAreas = db_query($sqlAreasLiberadas) or die(pg_last_error());

    $areas = "";
    for ($usrAreas = 0; $usrAreas < pg_num_rows($rsUsrAreas); $usrAreas++) {
        $oUsrAreas = db_utils::fieldsMemory($rsUsrAreas, $usrAreas);
        $areas .= " - " . $oUsrAreas->nome;
    }
    $oUsr->areas = $areas;
    $linhasAreas = $pdf->NbLines(56, $oUsr->areas);
    $alturaCell = $linhasAreas * 3;
    $pdf->SetFont('arial', '', 7);
    $pdf->cell(25, 6, $oUsr->id_usuario,1, 0, "C", 0);
    $pdf->cell(10, 6, $oUsr->z01_numcgm, 1, 0, "C", 0);
    $pdf->cell(80, 6, $oUsr->z01_nome, 1, 0, "C", 0);
    $pdf->cell(25, 6, $oUsr->login, 1, 0, "C", 0);
    $pdf->SetFont('arial', '', 6);
    $pdf->cell(140, 6, $oUsr->areas, 1, 1, "C", 0);
}


$pdf->Output();
