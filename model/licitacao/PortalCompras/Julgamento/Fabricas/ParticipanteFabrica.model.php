<?php

require_once("model/licitacao/PortalCompras/Julgamento/Participante.model.php");

class ParticipanteFabrica
{
    /**
     * Cria participante
     *
     * @param array $dados
     * @return Participante
     */
    public function criar(array $dados): Participante
    {
        $participante = new Participante;
        $participante->setCnpj($dados['CNPJ']);
        if(empty($dados['RepresentanteLegal'])){
            $representante = [];
            $representante['Nome'] = $dados['RazaoSocial'];
            $representante['CPF'] = $dados['CPF'];
            $representante['Telefone'] = $dados['Telefone'];
            $representante['Endereco'] = $dados['Endereco'];
            $representante['Numero'] = $dados['Numero'];
            $representante['Complemento'] = $dados['Complemento'];
            $representante['Cidade'] = $dados['Cidade'];
            $representante['CEP'] = $dados['CEP'];
            $representante['DataCadastro'] = $dados['DataCadastro'];
            $representante['DataHomologacao'] = $dados['DataHomologacao'];
            $representante['CD_MUNICIPIO_IBGE'] = $dados['CD_MUNICIPIO_IBGE'];
            $representante['UF'] = $dados['UF'];
            $dados['RepresentanteLegal'] = $representante;
            $participante->setCnpj($dados['CPF']);
        }
        $participante->setRepresentanteLegal($dados['RepresentanteLegal']['Nome']);
        $participante->setRazaoSocial($dados['RazaoSocial']);
        return $participante;
    }

    /**
     * Cria lista de Participantes
     *
     * @param array $participantes
     * @return array
     */
    public function criarLista(array $participantes): array
    {
        $listaParticipantes = [];
        foreach($participantes as $participante) {
            $listaParticipantes[] = $this->criar($participante);
        }

        return $listaParticipantes;
    }
}
