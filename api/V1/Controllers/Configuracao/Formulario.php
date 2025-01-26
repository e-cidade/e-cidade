<?php

namespace ECidade\Api\V1\Controllers\Configuracao;

use ECidade\Api\V1\Controllers\Configuracao\Transformers\FormularioTransformer;
use ECidade\Api\V1\ResourceInterface;
use ECidade\Api\V1\Controllers\GenericController;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use League\Fractal;
use League\Fractal\Serializer\JsonApiSerializer;

class Formulario extends GenericController implements ResourceInterface {

    const FILTRO_INSTITUICAO = 'instituicao';
    public function getAll($id, $instituicao = null)
    {
        $offsetLimit  = null;
        if (!empty($this->page)) {
            $offSet = $this->page->getNumber() <= 1 ? 0 : ($this->page->getSize()) * ($this->page->getNumber() - 1);
            $offsetLimit = " limit {$this->page->getSize()} offset {$offSet};";
        }

        $formulario = \ECidade\Configuracao\Formulario\Repository\Formulario::getById($id);
        if (empty($formulario)) {
            throw new NotFoundHttpException("Formul�rio {$id} n�o encontrado.");
        }

        $dados = new \stdClass();
        $dados->fields = array();
        $dados->respostas =array();
        $campoId  = new \stdClass();
        $campoId->identificador = "id_grupo_resposta";
        $campoId->descricao = "Resposta";
        $campoId->tipo = 1;
        $dados->fields[] = $campoId;
        $iColunas = 1;
        $listaColunas = array();
        $filtrarPorInstituicao = false;

        foreach ($formulario->getPerguntasIdentificadoras() as $perguntaIdentificadora) {
            if ($perguntaIdentificadora->getIdentificadorCampo() == self::FILTRO_INSTITUICAO) {
                $filtrarPorInstituicao = true;
            }
        }

        $aIdentColunas = $formulario->getIdentificadorColunasPesquisa($this->request->query->get("tipo"));
        foreach ($formulario->getPerguntas() as $pergunta) {
            if ($iColunas > 5) {
                break;
            }

            if (count($aIdentColunas) == 0 || in_array($pergunta->getIdentificador(), $aIdentColunas)) {
                
                $listaColunas[] = $pergunta->getIdentificador();
                $campo = new \stdClass();
                $campo->identificador = $pergunta->getIdentificador();
                $campo->descricao     = $this->removeHtmlContent($pergunta->getDescricao());
                $campo->tipo          = $pergunta->getTipo();
                $dados->fields[]      = $campo;
                $iColunas++;
            }
        }

        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;

        $aWhere   = array("db101_sequencial = {$formulario->getCodigo()}");
        if ($this->filters->has("filtro")) {
            $filtro = $this->filters->get("filtro");

            $aWhere[] = "(case when db103_avaliacaotiporesposta in(1,3) then (db106_avaliacaoperguntaopcao = " . ((int) $filtro) . " or db104_descricao ilike '%{$filtro}%')
                        else db106_resposta ilike '%{$filtro}%' end) ";
        }
        if ($this->request->query->get("coluna") != "") {
            $coluna = $this->request->query->get("coluna");
            $aWhere[] = "db103_identificador = '{$coluna}' ";
        }
        if ($filtrarPorInstituicao && !empty($instituicao)) {
            $aWhere[] = "COALESCE((SELECT db106_resposta::integer
                FROM avaliacaogrupoperguntaresposta
                JOIN avaliacaoresposta ON avaliacaogrupoperguntaresposta.db108_avaliacaoresposta=avaliacaoresposta.db106_sequencial
                JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
                WHERE db108_avaliacaogruporesposta=db107_sequencial and db104_identificadorcampo = 'instituicao'),0) IN ({$instituicao},0)
                AND db107_datalancamento::varchar || db107_hora = (SELECT db107_datalancamento::varchar || db107_hora FROM avaliacaogruporesposta ORDER BY db107_sequencial DESC LIMIT 1)";
        }
        $sSqlRespostas  = $oDaoAvaliacaoResposta->sql_query_avaliacao(
            null,
            "distinct db107_sequencial, db107_usuario, db107_datalancamento, db107_hora",
            "db107_sequencial {$offsetLimit}",
            implode(" and ", $aWhere)
        );

        $rsRespostas = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar as respostas do formul�rio {$formulario->getNome()}.");
        }

        $dados->respostas = \db_utils::makeCollectionFromRecord($rsRespostas, function ($dados) use ($formulario, $listaColunas) {

            $oResposta = Resposta::make($dados, $formulario);
            $oRespostaRetorno = new \stdClass();
            $oRespostaRetorno->id_grupo_resposta = $oResposta->getCodigo();

            foreach ($oResposta->getRespostas() as $valorResposta) {
                if (!in_array($valorResposta->getPergunta()->getIdentificador(), $listaColunas)) {
                    continue;
                }

                $valor = $valorResposta->getValor();
                $pergunta = $valorResposta->getPergunta();
                if (in_array($pergunta->getTipoResposta(), array(1,3))) {
                    $valor = $valorResposta->getOpcao()->getDescricao();
                }
                if ($valorResposta->getPergunta()->getIdentificador() == '') {
                    continue;
                }

                /**
                 * formatar campos:
                 */
                if ($pergunta->getTipo() == 5) {
                    $valor = db_formatar(substr($valor, 0, 10), 'd');
                }
                $oRespostaRetorno->{$pergunta->getIdentificador()} = $valor;
            }

            return $oRespostaRetorno;
        });
        /**
         * Ordernar os campos
         */
        $perguntasIdentificadoras = $formulario->getPerguntasIdentificadoras();
        $respostas = $dados->respostas;
        if (count($perguntasIdentificadoras) > 0) {
            usort($respostas, function ($atual, $anterior) use ($perguntasIdentificadoras) {

                $identificador = $perguntasIdentificadoras[0]->getIdentificador();
                if (isset($atual->{$identificador})) {
                    return strcasecmp($atual->{$identificador}, $anterior->{$identificador});
                }
                return 1;
            });
        }

        $dados->respostas  = $respostas;
        $transformer = new FormularioTransformer();
        $transformer->setFields($this->fields);
        $result = new Fractal\Resource\Item((array)$dados, $transformer);

        return $this->format($result);
    }

    protected function format($data)
    {
        $fractal = new Fractal\Manager();
        $output = $fractal->createData($data)->toArray();

        return new JsonResponse(\DBString::utf8_encode_all($output));
    }

    protected function removeHtmlContent($text, $tags = '', $invert = false)
    {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) && count($tags) > 0) {
            if (!$invert) {
                return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif (!$invert) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }

        return $text;
    }
}
