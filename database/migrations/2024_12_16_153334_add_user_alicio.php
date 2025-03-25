<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUserAlicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->_run();
    }

    private function _run()
    {
       
        $data = array(
            array('ALICIO MIRANDA DA SILVA JUNIOR',
                  'NAO INFORMADO',
                  '0',
                  'NAO INFORMADO',
                  'GRAVATAI',
                  'RS',
                  '94055310',
                  '2024-01-01 00:00:00.0',
                  '1',
                  '0',
                  '1',
                  '2',
                  '0',
                  '01625809085',
                  'M',
                  '2024-01-01 00:00:00.0',
                  '09:00',
                  'ALICIO MIRANDA DA SILVA JUNIOR',
                  true,
                  '0',
                  '0',
                  null,
                  'user' => array('ALICIO MIRANDA DA SILVA JUNIOR',
                                  'amsj.contass',
                                  '80b124e739582f2ae0a2e4a5ab827df1c29ffdf6',
                                  '1',
                                  '',
                                  '0',
                                  '1',
                                  '2020-04-09 00:00:00.0')
                                  )
                                );

        foreach ($data as $cgm) {

            $arrUser = $cgm['user'];
            unset($cgm['user']);

            $z01_numcgm = DB::select("select nextval('protocolo.cgm_z01_numcgm_seq') as numcgm")[0]->numcgm;
            //$cgm['z01_numcgm'] = $z01_numcgm;
            $cgm = array_merge(['z01_numcgm' => $z01_numcgm], $cgm);
            
            $this->_loadCgm($cgm);

            $id_usuario = DB::select("select nextval('configuracoes.db_usuarios_id_usuario_seq') as id_usuario")[0]->id_usuario;
            //$arrUser['id_usuario'] = $id_usuario;
            $arrUser = array_merge(['id_usuario' => $id_usuario], $arrUser);

            $this->_loadUsers($arrUser);
            $this->_loadDepartamentos($id_usuario);
            $this->_loadPermissions($id_usuario);
            $this->_loadUsuaCgm(array($id_usuario, $z01_numcgm));

            $arrInstits = DB::table('configuracoes.db_config')->pluck('codigo');

            foreach ($arrInstits as $instit) {
                $this->_loadUserInstit([$instit, $id_usuario]);
            }
        }
    }

    /**
     * Faz a carga dos dados na tabela cgm
     * @param Array $data
     */
    private function _loadCgm($data)
    {
        $columns = array(
            'z01_numcgm',
            'z01_nome',
            'z01_ender',
            'z01_numero',
            'z01_bairro',
            'z01_munic',
            'z01_uf',
            'z01_cep',
            'z01_cadast',
            'z01_login',
            'z01_numcon',
            'z01_nacion',
            'z01_estciv',
            'z01_tipcre',
            'z01_cgccpf',
            'z01_sexo',
            'z01_ultalt',
            'z01_hora',
            'z01_nomecomple',
            'z01_trabalha',
            'z01_renda',
            'z01_incmunici',
            'z01_ibge'
        );

        DB::table('protocolo.cgm')->insert(array_combine($columns, $data));
    }

    /**
     * Faz a carga dos dados na tabela db_usuarios
     * @param Array $data
     */
    private function _loadUsers($data)
    {
        $columns = array(
            'id_usuario',
            'nome',
            'login',
            'senha',
            'usuarioativo',
            'email',
            'usuext',
            'administrador',
            'datatoken',
        );

        DB::table('configuracoes.db_usuarios')->insert(array_combine($columns, $data));
    }

    /**
     * Faz a carga dos dados na tabela db_usuacgm
     * @param Array $data
     */
    private function _loadUsuaCgm($data)
    {
        $columns = array('id_usuario', 'cgmlogin');

        DB::table('configuracoes.db_usuacgm')->insert(array_combine($columns, $data));
    }

    /**
     * Faz a carga dos dados na tabela db_userinst
     * @param Array $data
     */
    private function _loadUserInstit($data)
    {
        $columns = array('id_instit', 'id_usuario');

        DB::table('configuracoes.db_userinst')->insert(array_combine($columns, $data));
    }

    /**
     * Copia os mesmos departamentos do usuario dbseller
     * @param int $id_usuario
     */
    private function _loadDepartamentos($id_usuario)
    {
        DB::insert(
            "insert into configuracoes.db_depusu (id_usuario, coddepto, db17_ordem) select ?, coddepto, db17_ordem from configuracoes.db_depusu where id_usuario = 1 order by coddepto",
            [$id_usuario]
        );
    }

    /**
     * Faz a carga das permissoes de acordo com as pemissoes do usuario dbseller
     * @param int $id_usuario
     */
    private function _loadPermissions($id_usuario)
    {
        DB::insert(
            "insert into configuracoes.db_permissao (id_usuario, id_item, permissaoativa, anousu, id_instit, id_modulo) select ?, id_item, permissaoativa, anousu, id_instit, id_modulo from configuracoes.db_permissao where id_usuario = 1 order by id_item", [$id_usuario]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}