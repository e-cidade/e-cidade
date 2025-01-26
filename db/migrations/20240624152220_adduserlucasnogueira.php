<?php

use ECidade\Suporte\Phinx\PostgresMigration;;

class AddUserLucasNogueira extends PostgresMigration
{

    public function up()
    {
        $this->_run();
    }

    public function down()
    {
    }

    private function _run()
    {
        $data = array(
            array('LUCAS ALEXANDRO NOGUEIRA', 'NAO INFORMADO', '0', 'NAO INFORMADO', 'IBITIUVA', 'SP', '14750000', '2021-03-16 00:00:00.0', '1', '0', '1', '2', '0', '11457397684', 'M', '2021-03-16 00:00:00.0', '16:42', 'LUCAS ALEXANDRO NOGUEIRA', 'true', '0', '0', 'null', 'user' => array('LUCAS ALEXANDRO NOGUEIRA', 'lan.contass', 'd3d72981ee358e6c4562e2b97afebb0e75ce5aa9', '1', '', '0', '1', '2020-04-09 00:00:00.0'))
        );

        foreach ($data as $cgm) {

            $arrUser = $cgm['user'];
            unset($cgm['user']);

            $z01_numcgm = current($this->fetchRow("select nextval('cgm_z01_numcgm_seq')"));
            array_unshift($cgm, $z01_numcgm);
            $this->_loadCgm($cgm);

            $id_usuario = current($this->fetchRow("select nextval('db_usuarios_id_usuario_seq')"));
            array_unshift($arrUser, $id_usuario);
            $this->_loadUsers($arrUser);
            $this->_loadDepartamentos($id_usuario);
            $this->_loadPermissions($id_usuario);

            $this->_loadUsuaCgm(array($id_usuario, $z01_numcgm));

            $arrInstits = $this->fetchAll("select codigo from db_config");

            foreach ($arrInstits as $instit) {
                $this->_loadUserInstit(array($instit['codigo'], $id_usuario));
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

        $this->table('cgm', array('schema' => 'protocolo'))->insert($columns, array($data))->saveData();
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

        $this->table('db_usuarios', array('schema' => 'configuracoes'))->insert($columns, array($data))->saveData();
    }

    /**
     * Faz a carga dos dados na tabela db_usuacgm
     * @param Array $data
     */
    private function _loadUsuaCgm($data)
    {
        $columns = array('id_usuario', 'cgmlogin');

        $this->table('db_usuacgm', array('schema' => 'configuracoes'))->insert($columns, array($data))->saveData();
    }

    /**
     * Faz a carga dos dados na tabela db_userinst
     * @param Array $data
     */
    private function _loadUserInstit($data)
    {
        $columns = array('id_instit', 'id_usuario');

        $this->table('db_userinst', array('schema' => 'configuracoes'))->insert($columns, array($data))->saveData();
    }

    /**
     * Copia os mesmos departamentos do usuario dbseller
     * @param int $id_usuario
     */
    private function _loadDepartamentos($id_usuario)
    {
        $this->execute("insert into db_depusu select {$id_usuario}, coddepto, db17_ordem from db_depusu where id_usuario = 1 order by 2");
    }

    /**
     * Faz a carga das permissoes de acordo com as pemissoes do usuario dbseller
     * @param int $id_usuario
     */
    private function _loadPermissions($id_usuario)
    {
        $this->execute("insert into db_permissao select {$id_usuario}, id_item, permissaoativa, anousu, id_instit, id_modulo from db_permissao where id_usuario = 1 order by 2");
    }
}
