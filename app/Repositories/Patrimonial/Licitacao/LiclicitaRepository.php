<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_liclicita;
use App\Models\Patrimonial\Licitacao\Liclicita;
use Illuminate\Support\Facades\DB;

class LiclicitaRepository
{
    private Liclicita $model;

    public function __construct()
    {
        $this->model = new Liclicita();
    }


    public function getLicitacao($l20_codigo): object
    {
        $clliclicita = new cl_liclicita();
        $sql = $clliclicita->sql_query_file($l20_codigo);
        $rsLicitacao = DB::select($sql);
        return $rsLicitacao[0];
    }

    public function getAllByFilters(
        ?string $l20_codigo = null,
        ?string $l20_numero = null,
        ?string $l20_edital = null,
        ?string $l20_codtipocom = null,
        ?string $l20_anousu = null,
        ?string $l20_nroedital = null,
        ?string $l20_datacria = null,
        ?string $l20_objeto = null,
        ?string $l08_sequencial = null,
        ?array $orderable = [],
        ?string $search = null,
        ?bool $is_contass,
        int $limit = 15,
        int $offset = 0,
        array $modalidades = [],
        ?string $l20_instit
    ): ?array {
        $query = $this->model->query();

        $this->getJoinsLintagem($query);

        $this->getSelectListagem($query);

        $query->where('l20_instit', $l20_instit ?? 1);

        $query->whereIn('liclicita.l20_codtipocom', function($query) use ($modalidades){
            $query
                ->select('l03_codigo')
                ->from('cflicita')
                ->whereIn('l03_pctipocompratribunal', $modalidades)
            ;
        });

        if(!$is_contass && (is_null($l08_sequencial) || $l08_sequencial == '')){
            $query->where('l20_licsituacao', 0);
        }

        if(!empty($l20_codigo)){
            $query->where('l20_codigo', $l20_codigo);
        }

        if(!empty($l20_numero)){
            $query->where('l20_numero', $l20_numero);
        }

        if(!empty($l20_edital)){
            $query->where('l20_edital', $l20_edital);
        }

        if(!empty($l20_nroedital)){
            $query->where('l20_nroedital', $l20_nroedital);
        }

        if(!is_null($l08_sequencial) && $l08_sequencial != ''){
            $query->where('l20_licsituacao', (int)$l08_sequencial);
        }

        if(!empty($l20_datacria)){
            $query->where('l20_datacria', date('Y-m-d', strtotime(str_replace('/', '-', $l20_datacria))));
        }

        if(!empty($l20_codtipocom)){
            $query->where(function($query) use ($l20_codtipocom){
                $query->whereRaw('LOWER(l03_descr) LIKE ?', ['%' . mb_strtolower(mb_convert_encoding($l20_codtipocom, 'ISO-8859-1')) . '%']);
            });
        }
        if(!empty($l20_objeto)){
            $query->where(function($query) use ($l20_objeto){
                $query->whereRaw('LOWER(l20_objeto) LIKE ?', ['%' . mb_strtolower(mb_convert_encoding($l20_objeto, 'ISO-8859-1')) . '%']);
            });
        }

        if(!empty($l20_anousu)){
            $query->where('l20_anousu', $l20_anousu);
        }

        if(!empty($search)){
            $search = mb_strtolower(mb_convert_encoding($search, 'ISO-8859-1'));
            $searchString = '%'. $search .'%';
            $query->whereRaw(
                '(
                    LOWER(l03_descr) LIKE ?
                    OR (l20_edital IS NOT NULL AND l20_edital = ?)
                    OR (l20_codigo IS NOT NULL AND l20_codigo = ?)
                    OR (l20_numero IS NOT NULL AND l20_numero = ?)
                    OR (l20_anousu IS NOT NULL AND l20_anousu = ?)
                    OR (l20_datacria IS NOT NULL AND l20_datacria = ?)
                    OR LOWER(l20_objeto) LIKE ?
                    OR LOWER(l08_descr) LIKE ?
                    OR (l20_licsituacao) LIKE ?
                    OR LOWER(
                        (
                            CASE
                                WHEN l20_tipojulg IS NOT NULL AND l20_tipojulg = 1 THEN \'Por Item\'
                                WHEN l20_tipojulg IS NOT NULL AND l20_tipojulg = 3 THEN \'Por Lote\'
                                ELSE \'\'
                            END
                        )
                    ) LIKE ?
                    OR LOWER(
                        CASE
                            WHEN l20_nroedital IS NULL THEN \'-\'
                            ELSE l20_nroedital::varchar
                        END
                    ) LIKE ?
                )',
                [
                    $searchString,
                    is_numeric($search) ? $search : null,
                    is_numeric($search) ? $search : null,
                    is_numeric($search) ? $search : null,
                    is_numeric($search) ? $search : null,
                    (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) ? date('Y-m-d', strtotime(str_replace('/', '-', $search))) : null,
                    $searchString,
                    $searchString,
                    $searchString,
                    $searchString,
                    $searchString
                ]
            );
        }


        if(!empty($orderable)){
            foreach($orderable as $value){
                $query->orderBy($value->slug, $value->order);
            }
        }

        $total = $query->count();

        $query->limit($limit);
        $query->offset(($offset * $limit));

        $data = $query->get();

        return ['total' => $total, 'data' => $data->toArray()];
    }

    private function getSelectListagem(&$query){
        $query->select(
            'liclicita.l20_codigo',
            'liclicita.l20_numero',
            'l20_anousu',
            'cflicita.l03_descr',
            'l20_edital',
            DB::raw('
                (
                    CASE
                        WHEN l20_nroedital IS NULL THEN \'-\'
                        ELSE l20_nroedital::varchar
                    END
                ) as l20_nroedital
            '),
            'liclicita.l20_datacria',
            'liclicita.l20_objeto',
            'liclicita.l20_licsituacao',
            'l20_tipojulg',
            'l08_descr as dl_situacao',
            DB::raw('
                (
                    CASE
                        WHEN l20_tipojulg IS NOT NULL AND l20_tipojulg = 1 THEN \'Por Item\'
                        WHEN l20_tipojulg IS NOT NULL AND l20_tipojulg = 3 THEN \'Por Lote\'
                        ELSE \'\'
                    END
                ) as l20_tipojulg_desc
            '),
            'l20_tipojulg',
            'l47_sequencial',
            'l21_codigo',
            'pcorcamfornelic_check.has_fornecedor'
        );
    }

    private function getJoinsLintagem(&$query){
        $query->join(
            'db_config',
            'db_config.codigo',
            '=',
            'liclicita.l20_instit'
        );

        $query->join(
            'db_usuarios',
            'db_usuarios.id_usuario',
            '=',
            'liclicita.l20_id_usucria'
        );
        $query->join(
            'cflicita',
            'cflicita.l03_codigo',
            '=',
            'liclicita.l20_codtipocom'
        );
        $query->join(
            'liclocal',
            'liclocal.l26_codigo',
            '=',
            'liclicita.l20_liclocal'
        );
        $query->join(
            'liccomissao',
            'liccomissao.l30_codigo',
            '=',
            'liclicita.l20_liccomissao'
        );
        $query->join(
            'licsituacao',
            'licsituacao.l08_sequencial',
            '=',
            'liclicita.l20_licsituacao'
        );
        $query->join(
            'db_config as dbconfig',
            'dbconfig.codigo',
            '=',
            'cflicita.l03_instit'
        );

        $query->join(
            'cgm',
            'cgm.z01_numcgm',
            '=',
            'db_config.numcgm'
        );

        $query->join(
            'pctipocompra',
            'pctipocompra.pc50_codcom',
            '=',
            'cflicita.l03_codcom'
        );
        $query->join(
            'bairro',
            'bairro.j13_codi',
            '=',
            'liclocal.l26_bairro'
        );
        $query->join(
            'ruas',
            'ruas.j14_codigo',
            '=',
            'liclocal.l26_lograd'
        );
        $query->leftJoin(
            'liclicitaproc',
            'liclicitaproc.l34_liclicita',
            '=',
            'liclicita.l20_codigo'
        );
        $query->leftJoin(
            'protprocesso',
            'protprocesso.p58_codproc',
            '=',
            'liclicitaproc.l34_protprocesso'
        );

        $subedital = DB::table('liclancedital')
            ->select('l47_liclicita', DB::raw('MAX(l47_sequencial) as l47_sequencial'))
            ->groupBy('l47_liclicita');

        $query->leftJoinSub($subedital, 'subedital', function($join){
            $join->on('subedital.l47_liclicita', '=', 'liclicita.l20_codigo');
        });

        $subitem = DB::table('liclicitem')
            ->select('l21_codliclicita', DB::raw('MAX(l21_codigo) as l21_codigo'))
            ->groupBy('l21_codliclicita');
        $query->leftJoinSub($subitem, 'subitem', function($join){
            $join->on('subitem.l21_codliclicita', '=', 'liclicita.l20_codigo');
        });

        $subfornecedor = DB::table('pcorcamfornelic')
            ->select('pc31_liclicita', DB::raw('(CASE WHEN COUNT(pc31_orcamforne) > 0 THEN 1 ELSE 0 END) as has_fornecedor'))
            ->groupBy('pc31_liclicita');
        $query->leftJoinSub($subfornecedor, 'pcorcamfornelic_check', function($join){
            $join->on('pcorcamfornelic_check.pc31_liclicita', '=', 'liclicita.l20_codigo');
        });
    }

    function getNextProcesso(int $anousu, int $instit){
        $query = $this->model->query();
        $query->select(DB::raw('COALESCE(MAX(l20_numero) + 1, 1) as l20_numero'));
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);
        return $query->first();
    }

    function getNextNumeracao(int $anousu, int $instit, int $l20_codtipocom){
        $query = $this->model->query();
        $query->select(DB::raw('COALESCE(MAX(l20_edital) + 1, 1) as l20_edital'));
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);
        $query->where('l20_codtipocom', $l20_codtipocom);

        return $query->first();
    }

    function getNextEdital(int $anousu, int $instit){
        $query = $this->model->query();
        $query->select(DB::raw('COALESCE(MAX(l20_nroedital) + 1, 1) as l20_nroedital'));
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);

        return $query->first();
    }

    function getProcessoByNumero(int $anousu, int $instit, int $l20_codtipocom, int $l20_numero, ?int $l20_codigo = null){
        $query = $this->model->query();
        $query->where('l20_numero', $l20_numero);
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);
        $query->where('l20_codtipocom', $l20_codtipocom);

        if(!empty($l20_codigo)){
            $query->where('l20_codigo', '!=', $l20_codigo);
        }

        return $query->first();
    }

    function getNumeroByEdital(int $anousu, int $instit, int $l20_edital, ?int $l20_codigo = null){
        $query = $this->model->query();
        $query->where('l20_edital', $l20_edital);
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);

        if(!empty($l20_codigo)){
            $query->where('l20_codigo', '!=', $l20_codigo);
        }

        return $query->first();
    }

    function getEditalByNumeroEdital(int $anousu, int $instit, int $l20_nroedital, ?int $l20_codigo = null){
        $query = $this->model->query();
        $query->where('l20_anousu', $anousu);
        $query->where('l20_instit', $instit);
        $query->where('l20_nroedital', $l20_nroedital);

        if(!empty($l20_codigo)){
            $query->where('l20_codigo', '!=', $l20_codigo);
        }

        return $query->first();
    }

    function getNextVal(){
        return $this->model->getNextval();
    }

    function save(Liclicita $data){
        $data->save();
        return $data;
    }

    function find(int $l20_codigo){
        return $this->model
            ->where('l20_codigo', $l20_codigo)
            ->get()
            ->first();
    }

    function delete(Liclicita $aData){
        $aData->delete();
    }

    public function getDispensasInexigibilidadeByCodigo(int $l20_codigo):?object
    {
        $query = $this->model->query();

        $this->joinDispensasInexibilidade($query);
        $this->selectDispensasInexibilidade($query);

        $query->where('l20_codigo', $l20_codigo);

        return $query->get()->first();
    }

    public function getPublicacaoByCodigo(int $l20_codigo):?object
    {
        $query = $this->model->query();

        $this->joinPublicacao($query);
        $this->selectPublicacao($query);

        $query->where('l20_codigo', $l20_codigo);

        return $query->get()->first();
    }

    private function joinDispensasInexibilidade(&$query){

        $query->leftJoin(
            'liccomissaocgm as respAut',
            function($join){
                $join->on('respAut.l31_licitacao', '=', 'liclicita.l20_codigo')
                    ->on('respAut.l31_tipo', '=', DB::raw("'1'"));
            }
        );

        $query->leftJoin(
            'cgm as respAutCgm',
            'respAutCgm.z01_numcgm',
            '=',
            'respAut.l31_numcgm'
        );

        $query->leftJoin(
            'liccomissaocgm as respObras',
            function($join){
                $join->on('respObras.l31_licitacao', '=', 'liclicita.l20_codigo')
                    ->on('respObras.l31_tipo', '=', DB::raw("'10'"));
            }
        );

        $query->leftJoin(
            'cgm as respObrasCgm',
            'respObrasCgm.z01_numcgm',
            '=',
            'respObras.l31_numcgm'
        );

        $query->leftJoin(
            'liclicitaproc',
            'liclicitaproc.l34_liclicita',
            '=',
            'liclicita.l20_codigo'
        );

        $query->leftJoin(
            'protprocesso',
            'protprocesso.p58_codproc',
            '=',
            'liclicitaproc.l34_protprocesso'
        );

        $query->leftJoin(
            'db_depart',
            'db_depart.coddepto',
            '=',
            'liclicita.l20_codepartamento'
        );

        $query->leftJoin(
            'cflicita',
            'cflicita.l03_codigo',
            '=',
            'liclicita.l20_codtipocom'
        );

        $query->leftJoin(
            'liccomissaocgm as respEdital',
            function($join){
                $join->on('respEdital.l31_licitacao', '=', 'liclicita.l20_codigo')
                    ->on('respEdital.l31_tipo', '=', DB::raw("'2'"));
            }
        );

        $query->leftJoin(
            'cgm as respEditalCgm',
            'respEditalCgm.z01_numcgm',
            '=',
            'respEdital.l31_numcgm'
        );

        $query->leftJoin(
            'licpregao',
            'licpregao.l45_sequencial',
            '=',
            'liclicita.l20_equipepregao'
        );

        $query->leftJoin(
            'liccomissaocgm as respConducao',
            function($join){
                $join->on('respConducao.l31_licitacao', '=', 'liclicita.l20_codigo')
                    ->on('respConducao.l31_tipo', '=', DB::raw("'5'"));
            }
        );

        $query->leftJoin(
            'cgm as respConducaoCgm',
            'respConducaoCgm.z01_numcgm',
            '=',
            'respConducao.l31_numcgm'
        );

        
        $subfornecedor = DB::table('pcorcamfornelic')
            ->select('pc31_liclicita', DB::raw('(CASE WHEN COUNT(pc31_orcamforne) > 0 THEN 1 ELSE 0 END) as has_fornecedor'))
            ->groupBy('pc31_liclicita');
        $query->leftJoinSub($subfornecedor, 'pcorcamfornelic_check', function($join){
            $join->on('pcorcamfornelic_check.pc31_liclicita', '=', 'liclicita.l20_codigo');
        });

        $query->join(
            'licsituacao',
            'licsituacao.l08_sequencial',
            '=',
            'liclicita.l20_licsituacao'
        );
    }

    private function selectDispensasInexibilidade(&$query){
        $query->select(
            'liclicita.l20_codigo',
            'liclicita.l20_codepartamento',
            'liclicita.l20_leidalicitacao',
            'liclicita.l20_codtipocom',
            'liclicita.l20_anousu',
            'liclicita.l20_id_usucria',
            'liclicita.l20_instit',
            'liclicita.l20_numero',
            'liclicita.l20_edital',
            'liclicita.l20_nroedital',
            'liclicita.l20_dispensaporvalor',
            'liclicita.l20_naturezaobjeto',
            'liclicita.l20_regimexecucao',
            'liclicita.l20_procadmin',
            'liclicita.l20_datacria',
            'liclicita.l20_recdocumentacao',
            'liclicita.l20_dataaberproposta',
            'liclicita.l20_dataencproposta',
            'liclicita.l20_horacria',
            'liclicita.l20_horaaberturaprop',
            'liclicita.l20_horaencerramentoprop',
            'liclicita.l20_tipojulg',
            'liclicita.l20_tipliticacao',
            'liclicita.l20_tipnaturezaproced',
            'liclicita.l20_criterioadjudicacao',
            'liclicita.l20_amparolegal',
            'liclicita.l20_categoriaprocesso',
            'liclicita.l20_receita',
            'liclicita.l20_objeto',
            'liclicita.l20_justificativa',
            'liclicita.l20_razao',
            'liclicita.l20_justificativapncp',
            'liclicita.l20_dataaber',
            'liclicita.l20_horaaber',
            'liclicita.l20_equipepregao',
            'liclicita.l20_execucaoentrega',
            'liclicita.l20_diames',
            'liclicita.l20_numeroconvidado',
            'liclicita.l20_mododisputa',
            'liclicita.l20_critdesempate',
            'liclicita.l20_destexclusiva',
            'liclicita.l20_subcontratacao',
            'liclicita.l20_limitcontratacao',
            'liclicita.l20_condicoespag',
            'liclicita.l20_clausulapro',
            'liclicita.l20_aceitabilidade',
            'liclicita.l20_validadeproposta',
            'liclicita.l20_prazoentrega',
            'liclicita.l20_local',
            'liclicita.l20_inversaofases',
            'liclicita.l20_descrcriterio',
            'liclicita.l20_cadinicial',
            'liclicita.l20_licsituacao',
            'l08_descr as dl_situacao',
            'db_depart.descrdepto as l20_descricaodep',
            'respAutCgm.z01_numcgm as respAutocodigo',
            'respAutCgm.z01_nome as respAutonome',
            'respObrasCgm.z01_numcgm as respObrascodigo',
            'respObrasCgm.z01_nome as respObrasnome',
            'protprocesso.p58_codproc as p58_numero',
            'protprocesso.p58_requer as l34_protprocessodescr',
            'cflicita.l03_pctipocompratribunal',
            'cflicita.l03_presencial',
            'respEditalCgm.z01_numcgm as respEditalcodigo',
            'respEditalCgm.z01_nome as respEditalnome',
            'licpregao.l45_sequencial',
            DB::raw("
                CASE
                    WHEN licpregao.l45_tipo::varchar = '1' THEN 'Permanente'
                    WHEN licpregao.l45_tipo::varchar = '2' THEN 'Especial'
                END as l45_tipo
            "),
            'respConducaoCgm.z01_numcgm as respConducaocodigo',
            'respConducaoCgm.z01_nome as respConducaonome',
            'pcorcamfornelic_check.has_fornecedor'
        );
    }

    private function joinPublicacao(&$query){
        $query->leftJoin(
            'liccomissaocgm as respAut',
            function($join){
                $join->on('respAut.l31_licitacao', '=', 'liclicita.l20_codigo')
                    ->on('respAut.l31_tipo', '=', DB::raw("'8'"));
            }
        );

        $query->leftJoin(
            'cgm as respAutCgm',
            'respAutCgm.z01_numcgm',
            '=',
            'respAut.l31_numcgm'
        );
    }

    private function selectPublicacao(&$query){
        $query->select(
            'liclicita.l20_codigo',
            'liclicita.l20_dtpulicacaoedital',
            'liclicita.l20_linkedital',
            'liclicita.l20_diariooficialdivulgacao',
            'liclicita.l20_dtpublic',
            'liclicita.l20_dtpulicacaopncp',
            'liclicita.l20_linkpncp',
            'liclicita.l20_datapublicacao1',
            'liclicita.l20_nomeveiculo1',
            'liclicita.l20_datapublicacao2',
            'liclicita.l20_nomeveiculo2',
            'respAutCgm.z01_numcgm as respPubliccodigo',
            'respAutCgm.z01_nome as respPublicnome',
        );
    }

    public function update(int $l20_codigo, array $data){
        $oDispensa = $this->model->findOrFail($l20_codigo);
        $oDispensa->update($data);

        return $oDispensa;
    }

    public function getTribunal(int $l20_codigo){
        return $this->model
            ->select('pctipocompratribunal.l44_sequencial')
            ->join('cflicita', 'cflicita.l03_codigo', '=', 'liclicita.l20_codtipocom')
            ->join('db_config', 'db_config.codigo', '=', 'cflicita.l03_instit')
            ->join('pctipocompra', 'pctipocompra.pc50_codcom', '=', 'cflicita.l03_codcom')
            ->join('pctipocompratribunal', 'pctipocompratribunal.l44_sequencial', '=', 'cflicita.l03_pctipocompratribunal')
            ->join('cgm', 'cgm.z01_numcgm', '=', 'db_config.numcgm')
            ->join('db_tipoinstit', 'db_tipoinstit.db21_codtipo', '=', 'db_config.db21_tipoinstit')
            ->join('pctipocompratribunal AS a', 'a.l44_sequencial', '=', 'pctipocompra.pc50_pctipocompratribunal')
            ->where('liclicita.l20_codigo', $l20_codigo)
            ->first();
    }

    public function getLicLicitaLance($l20_codigo){
        return $this->model
            ->join(
                'liclancedital',
                'liclicita.l20_codigo',
                '=',
                'l47_liclicita'
            )
            ->where('l47_liclicita', $l20_codigo)
            ->first();
    }

}
