# Ecossistema e-Cidade

Este documento registra linhas e iniciativas relacionadas ao e-Cidade
identificadas pela comunidade.

O objetivo é facilitar descoberta e colaboração. A presença ou ausência de um
projeto nesta lista não representa endosso, preferência ou avaliação de
qualidade.

## Metodologia

Para cada iniciativa buscamos registrar:

- repositório de origem;
- mantenedor identificado;
- relação com o e-Cidade;
- condição na organização `e-cidade`;
- evidências públicas de histórico e manutenção;
- situação da licença quando verificável;
- data da última verificação.

As informações são classificadas como fato verificável ou lacuna de informação.
Quando origem, licença ou mantenedor não puderem ser confirmados, isso é
registrado explicitamente em vez de inferido.

## Linhas atualmente representadas

### Contass

- Repositório: [`e-cidade/e-cidade-Contass`](https://github.com/e-cidade/e-cidade-Contass)
- Tipo: distribuição mantida diretamente
- Mantenedor: Contass
- Situação na organização: repositório mantido pelo próprio responsável

### DBSeller

- Origem: [`DBSeller/e-cidade`](https://github.com/DBSeller/e-cidade)
- Mirror: [`e-cidade/e-cidade-DBSeller`](https://github.com/e-cidade/e-cidade-DBSeller)
- Tipo: mirror
- Mantenedor da origem: DBSeller
- Observação histórica: a DBSeller é a criadora original do e-Cidade
- Situação do mirror: ativo e sincronizado

### Sertão Digital

- Origem: [`sertaodigitalorg/e-Cidade-SD`](https://github.com/sertaodigitalorg/e-Cidade-SD)
- Tipo atual: projeto externo conhecido
- Mantenedor identificado: Sertão Digital
- Branch principal: `main`
- Última atividade verificada: 2026-08-24
- Relação histórica: o histórico contém o commit
  `e640eb485bf9a29f1fcda9c289c4ab23cffefe1d`, também presente na linha
  DBSeller, seguido por commits próprios do Sertão Digital
- Licença: arquivos-fonte contêm avisos de GPL versão 2 ou posterior, mas o
  GitHub não detecta uma licença no repositório e os arquivos de licença
  referenciados nos cabeçalhos não foram localizados na verificação
- Situação na organização: candidato a mirror, pendente de esclarecimento
  sobre a situação da licença

### CPD

- Origem candidata: [`w3aewander/e-cidadeCPD`](https://github.com/w3aewander/e-cidadeCPD)
- Tipo atual: projeto externo conhecido
- Descrição do repositório: versão do e-Cidade utilizada pela CPD Municipal
- Mantenedor identificado: `w3aewander`
- Branch principal: `main`
- Última atividade verificada: 2026-10-02
- Licença: GPL-2.0, com arquivo `LICENSE` presente e licença detectada pelo GitHub
- Relação histórica: o repositório possui desenvolvimento próprio recente, mas
  o commit usado como referência comum entre as linhas DBSeller e Sertão
  Digital não está presente em seu histórico
- Situação na organização: candidato a mirror, pendente de confirmação da
  relação histórica e da origem a ser representada

## Critério para novos mirrors

Antes de criar um mirror buscamos confirmar:

1. origem pública;
2. mantenedor;
3. relação histórica com o e-Cidade;
4. licença ou evidências suficientes de licenciamento;
5. existência de desenvolvimento próprio que justifique representar a linha.

O mirror deve preservar autoria e histórico e indicar explicitamente o
repositório de origem.

Última verificação: 2026-10-06.
