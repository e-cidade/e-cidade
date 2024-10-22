<?php

namespace App\Support\Session;

final class LegacySession
{
    const DB_USE_PCASP = 'DB_use_pcasp';
    const DB_ANO_PCASP = 'DB_ano_pcasp';
    const DB_INSTIT = 'DB_instit';
    const DB_CODDEPTO = 'DB_coddepto';
    const DB_DATAUSU = 'DB_datausu';
    const DB_ANOUSU = 'DB_anousu';
    const DB_ID_USUARIO = 'DB_id_usuario';
    const DB_LOGIN = 'DB_login';
    const DB_IP = 'DB_ip';
    const DB_MODULO = 'DB_modulo';
    const DB_NOME_MODULO = 'DB_nome_modulo';
    const DB_UOL_HORA = 'DB_uol_hora';
    const DB_ACESSADO = 'DB_acessado';
    const DB_BASE = 'DB_base';
    const DB_NBASE = 'DB_NBASE';
    const DB_ITEMMENU_ACESSADO = 'DB_itemmenu_acessado';

    const DEFAULT_KEYS = [
        self::DB_USE_PCASP,
        self::DB_ANO_PCASP,
        self::DB_INSTIT,
        self::DB_CODDEPTO,
        self::DB_DATAUSU,
        self::DB_ANOUSU,
        self::DB_ID_USUARIO,
        self::DB_LOGIN,
        self::DB_IP,
        self::DB_MODULO,
        self::DB_NOME_MODULO,
        self::DB_UOL_HORA,
        self::DB_ACESSADO,
        self::DB_BASE,
        self::DB_NBASE,
        self::DB_ITEMMENU_ACESSADO,
    ];
}
