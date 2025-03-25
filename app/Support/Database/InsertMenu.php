<?php

namespace App\Support\Database;

use Illuminate\Support\Facades\DB;

trait InsertMenu
{
    public function getIdModulo($descrModulo)
    {
        $modulo = DB::table('configuracoes.db_modulos')
            ->select('id_item')
            ->where('descr_modulo', $descrModulo)
            ->first();

        if (empty($modulo)) {
            $modulo = DB::table('configuracoes.db_modulos')
            ->select('id_item')
            ->where('nome_modulo', $descrModulo)
            ->first();
        }

        return $modulo->id_item;
    }

    public function getItemIdByDescr($descricao, $helper = null, $desctec = null)
    {

        $query = DB::table('configuracoes.db_itensmenu')
            ->select('id_item')
            ->where('descricao', $descricao);

        if ($helper != null) {
            $query->where('help', $helper);
        }

        if ($desctec != null){
            $query->where('desctec', $desctec);
        }

        return ($query->first())->id_item;
    }

    public function getLastInsertedId(): int
    {
        $id = DB::table('configuracoes.db_itensmenu')
            ->max('id_item');
        return $id + 1;
    }

    public function getMaxMenuId(): int
    {
        return DB::table('configuracoes.db_itensmenu')->max('id_item');
    }

    public function getNextSeqMenuId($idPrincipalMenu): ?int
    {
        $menu = DB::table('configuracoes.db_menu')->where('id_item', $idPrincipalMenu)->select('menusequencia')->orderBy('menusequencia', 'desc')->first();
        return !empty($menu) ? $menu->menusequencia + 1 : 0;
    }

    public function insertItemMenu($descricao, $link, $titulo, $status = 't')
    {

        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item' => $this->getLastInsertedId(),
            'descricao' => $descricao,
            'help' => $titulo,
            'funcao' => $link,
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => $titulo,
            'libcliente' => $status,
        ]);
    }

    public function insertMenu($descricaoItemPai, $descrModulo, $helperItemPai = null, $desctecItemPai = null)
    {
        $idItemPai = $this->getItemIdByDescr($descricaoItemPai, $helperItemPai, $desctecItemPai);
        $idItemFilho = $this->getMaxMenuId();

        $menusequencia = $this->getNextSeqMenuId($idItemPai);

        $idModulo = $this->getIdModulo($descrModulo);

        DB::table('configuracoes.db_menu')->insert([
            'id_item' => $idItemPai,
            'id_item_filho' => $idItemFilho,
            'menusequencia' => $menusequencia,
            'modulo' => $idModulo
        ]);
    }

    public function insertMenuById($idItemPai, $menusequencia = null, $idModulo = 1)
    {
        $idItemFilho = $this->getMaxMenuId();

        if ($menusequencia == null){
            $menusequencia = $this->getNextSeqMenuId($idItemPai);
        }

        DB::table('configuracoes.db_menu')->insert([
            'id_item' => $idItemPai,
            'id_item_filho' => $idItemFilho,
            'menusequencia' => $menusequencia,
            'modulo' => $idModulo
        ]);
    }
}