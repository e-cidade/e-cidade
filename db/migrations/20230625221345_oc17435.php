<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17435 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        SELECT fc_startsession();

        INSERT INTO
          avaliacao (
            db101_sequencial,
            db101_avaliacaotipo,
            db101_descricao,
            db101_obs,
            db101_ativo,
            db101_identificador,
            db101_cargadados,
            db101_permiteedicao
          )
        VALUES
          (3000000, 3, 'CENSO ESCOLA', NULL, TRUE, NULL, NULL, FALSE);

        INSERT INTO
          avaliacaogrupopergunta (
            db102_sequencial,
            db102_avaliacao,
            db102_descricao,
            db102_identificador,
            db102_identificadorcampo,
            db102_ordem
          )
        VALUES
          (3000003, 3000000, 'COMPUTADORES', NULL, NULL, 0),
          (3000014, 3000000, 'Escola cede espa�o para turmas do Brasil Alfabetiz', NULL, NULL, 0),
          (3000004, 3000000, 'FORMA DE OCUPA��O DO PR�DIO', NULL, NULL, 0),
          (3000005, 3000000, 'ESGOTO SANIT�RIO', NULL, NULL, 0),
          (3000001, 3000000, 'INFRA-ESTRUTURA', NULL, NULL, 0),
          (3000015, 3000000, 'Escola abre aos finais de semana para a comunidade', NULL, NULL, 0),
          (3000006, 3000000, 'MATERIAIS DID�TICOS ESPEC�FICOS', NULL, NULL, 0),
          (3000007, 3000000, 'EQUIPAMENTOS EXISTENTES', NULL, NULL, 0),
          (3000008, 3000000, 'DESTINA��O DO LIXO', NULL, NULL, 0),
          (3000009, 3000000, 'ABASTECIMENTO DE �GUA', NULL, NULL, 0),
          (3000011, 3000000, 'ABASTECIMENTO DE ENERGIA', NULL, NULL, 0),
          (3000012, 3000000, 'PREDIO COMPARTILHADO', NULL, NULL, 0),
          (3000013, 3000000, 'OUTRAS INFORMA��ES', NULL, NULL, 0);

        INSERT INTO
          avaliacaopergunta (
            db103_sequencial,
            db103_avaliacaotiporesposta,
            db103_avaliacaogrupopergunta,
            db103_descricao,
            db103_obrigatoria,
            db103_ativo,
            db103_ordem,
            db103_identificador,
            db103_tipo,
            db103_mascara,
            db103_dblayoutcampo,
            db103_perguntaidentificadora,
            db103_camposql,
            db103_identificadorcampo
          )
        VALUES
          (3000025, 1, 3000014, 'Escola cede espa�o para turmas do Brasil Alfabetizado', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000026, 1, 3000013, 'Escola abre aos finais de semana para a comunidade', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000007, 1, 3000004, 'Forma de Ocupa��o do Pr�dio:', TRUE, TRUE, 9, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000008, 3, 3000005, 'Esgoto Sanitario:', TRUE, TRUE, 10, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000009, 3, 3000006, 'Materais Did�ticos Espec�ficos:', TRUE, TRUE, 11, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000010, 3, 3000007, 'Equipamentos Existentes:', TRUE, TRUE, 12, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000011, 3, 3000008, 'Destina��o do Lixo:', TRUE, TRUE, 13, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000014, 3, 3000009, 'Abastecimento de �gua', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000015, 3, 3000011, 'Abastecimento de Energia:', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000001, 1, 3000001, 'Possui computadores:', TRUE, TRUE, 2, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000004, 1, 3000001, 'Acesso � Internet:', TRUE, TRUE, 6, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000005, 1, 3000001, 'Banda Larga:', TRUE, TRUE, 7, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000006, 3, 3000001, 'Local de Funcionamento:', TRUE, TRUE, 8, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000000, 3, 3000001, 'Depend�ncias Existentes na Escola', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000016, 1, 3000012, 'Predio Compartilhado', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000154, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 1', TRUE, TRUE, 2, 'PredioCompartilhadoInep1', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000155, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 2', TRUE, TRUE, 3, 'PredioCompartilhadoInep2', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000156, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 3', TRUE, TRUE, 4, 'PredioCompartilhadoInep3', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000157, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 4', TRUE, TRUE, 5, 'PredioCompartilhadoInep4', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000158, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 5', TRUE, TRUE, 6, 'PredioCompartilhadoInep5', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000159, 2, 3000012, 'C�digo INEP do pr�dio compartilhado 6', TRUE, TRUE, 7, 'PredioCompartilhadoInep6', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000017, 1, 3000013, '�gua consumida pelos Alunos:', TRUE, TRUE, 1, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000018, 1, 3000013, 'Alimenta��o Escolar para os Alunos', TRUE, TRUE, 2, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000019, 2, 3000013, 'N� de Sala de Aula Existentes na Escola:', TRUE, TRUE, 3, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000020, 2, 3000013, 'N� de Salas Utilizadas como Sala de Aula:', TRUE, TRUE, 4, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000021, 1, 3000013, 'Atividade Complementar', TRUE, TRUE, 5, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000022, 1, 3000013, 'Atendimento Educ. Especializado AEE:', TRUE, TRUE, 6, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000023, 1, 3000013, 'Ensino Fundamental em ciclos:', TRUE, TRUE, 7, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000153, 1, 3000013, 'Escola com proposta pedagogica de forma��o por altern�ncia', TRUE, TRUE, 8, 'EscolaFormacaoAlternancia', 1, NULL, NULL, FALSE, NULL, NULL),
          (3000024, 2, 3000003, 'Qtde. de Computadores Uso de Alunos:', TRUE, TRUE, 5, NULL, 1, NULL, NULL, FALSE, NULL, NULL),
          (3000003, 2, 3000003, 'Qtde. de Computadores Uso Administrativo:', TRUE, TRUE, 4, NULL, 1, NULL, NULL, FALSE, NULL, NULL);

        INSERT INTO
          avaliacaoperguntaopcao (
            db104_sequencial,
            db104_avaliacaopergunta,
            db104_descricao,
            db104_aceitatexto,
            db104_identificador,
            db104_peso,
            db104_valorresposta,
            db104_identificadorcampo
          )
        VALUES
          (3000122, 3000025, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000123, 3000025, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000124, 3000026, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000125, 3000026, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000047, 3000007, 'Pr�prio', FALSE, NULL, NULL, NULL, NULL),
          (3000048, 3000007, 'Alugado', FALSE, NULL, NULL, NULL, NULL),
          (3000049, 3000007, 'Cedido', FALSE, NULL, NULL, NULL, NULL),
          (3000050, 3000008, 'Rede P�blica', FALSE, NULL, NULL, NULL, NULL),
          (3000051, 3000008, 'Fossa', FALSE, NULL, NULL, NULL, NULL),
          (3000052, 3000008, 'Inexistente', FALSE, NULL, NULL, NULL, NULL),
          (3000053, 3000009, 'N�o Utiliza', FALSE, NULL, NULL, NULL, NULL),
          (3000054, 3000009, 'Quilombola', FALSE, NULL, NULL, NULL, NULL),
          (3000055, 3000009, 'Ind�gena', FALSE, NULL, NULL, NULL, NULL),
          (3000056, 3000010, 'Aparelho de Televis�o', TRUE, 'aparelho_televisao', 0, NULL, NULL),
          (3000057, 3000010, 'Videocassete', TRUE, 'videocassete', 0, NULL, NULL),
          (3000058, 3000010, 'DVD', TRUE, 'dvd', 0, NULL, NULL),
          (3000059, 3000010, 'Antena Parab�lica', TRUE, 'antena_parabolica', 0, NULL, NULL),
          (3000060, 3000010, 'Copiadora', TRUE, 'copiadora', 0, NULL, NULL),
          (3000061, 3000010, 'Retroprojetor', TRUE, 'retroprojetor', 0, NULL, NULL),
          (3000065, 3000010, 'Fax', TRUE, 'fax', 0, NULL, NULL),
          (3000066, 3000010, 'M�quina Fotogr�fica/Filmadora', TRUE, 'maquina_fotografica', 0, NULL, NULL),
          (3000062, 3000010, 'Impressora', TRUE, 'impressora', 0, NULL, NULL),
          (3000063, 3000010, 'Aparelho de som', TRUE, 'aparelho_som', 0, NULL, NULL),
          (3000064, 3000010, 'Projetor Multim�dia (Data show)', TRUE, 'projetor_multimidia', 0, NULL, NULL),
          (3000032, 3000010, 'Computadores', TRUE, 'equipamentos_computadores', 0, NULL, NULL),
          (3000579, 3000010, 'Impressora Multifuncional', TRUE, 'equipamentos_impressora_multifuncional', 0, NULL, NULL),
          (3000067, 3000011, 'Coleta Peri�dica', FALSE, NULL, NULL, NULL, NULL),
          (3000068, 3000011, 'Queima', FALSE, NULL, NULL, NULL, NULL),
          (3000069, 3000011, 'Joga em outra �rea', FALSE, NULL, NULL, NULL, NULL),
          (3000070, 3000011, 'Recicla', FALSE, NULL, NULL, NULL, NULL),
          (3000071, 3000011, 'Enterra', FALSE, NULL, NULL, NULL, NULL),
          (3000072, 3000011, 'Outros', FALSE, NULL, NULL, NULL, NULL),
          (3000088, 3000014, 'Rede P�blica', FALSE, NULL, NULL, NULL, NULL),
          (3000089, 3000014, 'Po�o Artesiano', FALSE, NULL, NULL, NULL, NULL),
          (3000090, 3000014, 'Cacimba/Cisterna/Po�o', FALSE, NULL, NULL, NULL, NULL),
          (3000091, 3000014, 'Fonte/Rio/Igarap�/Riacho', FALSE, NULL, NULL, NULL, NULL),
          (3000092, 3000014, 'Inexistente', FALSE, NULL, NULL, NULL, NULL),
          (3000093, 3000015, 'Rede P�blica', FALSE, NULL, NULL, NULL, NULL),
          (3000094, 3000015, 'Gerador', FALSE, NULL, NULL, NULL, NULL),
          (3000095, 3000015, 'Outros(Enegria Alternativa)', FALSE, NULL, NULL, NULL, NULL),
          (3000096, 3000015, 'Inexistente', FALSE, NULL, NULL, NULL, NULL),
          (3000030, 3000001, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000031, 3000001, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000035, 3000004, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000036, 3000004, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000037, 3000005, 'Possui', FALSE, NULL, NULL, NULL, NULL),
          (3000038, 3000005, 'N�o Possui', FALSE, NULL, NULL, NULL, NULL),
          (3000039, 3000006, 'Pr�dio Escolar', FALSE, NULL, NULL, NULL, NULL),
          (3000040, 3000006, 'Templo / Igreja', FALSE, NULL, NULL, NULL, NULL),
          (3000041, 3000006, 'Salas de Empresa', FALSE, NULL, NULL, NULL, NULL),
          (3000042, 3000006, 'Casa do Professor', FALSE, NULL, NULL, NULL, NULL),
          (3000043, 3000006, 'Salas em Outra Escola', FALSE, NULL, NULL, NULL, NULL),
          (3000044, 3000006, 'Galp�o / Rancho / Paiol', FALSE, NULL, NULL, NULL, NULL),
          (3000045, 3000006, 'OUTROS', FALSE, NULL, NULL, NULL, NULL),
          (3000046, 3000006, 'Unidade de Interna��o', FALSE, 'LocalFuncionamentoUnidadeInternacao', 0, NULL, NULL),
          (3000560, 3000006, 'Unidade Prisional', FALSE, 'LocalFuncionamentoUnidadePrisional', 0, NULL, NULL),
          (3000007, 3000000, 'Cozinha', FALSE, NULL, NULL, NULL, NULL),
          (3000008, 3000000, 'Biblioteca', FALSE, NULL, NULL, NULL, NULL),
          (3000011, 3000000, 'Berc�rio', FALSE, NULL, NULL, NULL, NULL),
          (3000018, 3000000, 'Banheiro com chuveiro', FALSE, NULL, NULL, NULL, NULL),
          (3000019, 3000000, 'Refeit�tio', FALSE, NULL, NULL, NULL, NULL),
          (3000020, 3000000, 'Despensa', FALSE, NULL, NULL, NULL, NULL),
          (3000021, 3000000, 'Almoxarifado', FALSE, NULL, NULL, NULL, NULL),
          (3000022, 3000000, 'Audit�rio', FALSE, NULL, NULL, NULL, NULL),
          (3000025, 3000000, 'Alojamento de aluno', FALSE, NULL, NULL, NULL, NULL),
          (3000026, 3000000, 'Alojamento de professor', FALSE, NULL, NULL, NULL, NULL),
          (3000027, 3000000, '�rea verde', FALSE, NULL, NULL, NULL, NULL),
          (3000028, 3000000, 'Lavanderia', FALSE, NULL, NULL, NULL, NULL),
          (3000023, 3000000, 'P�tio coberto', FALSE, NULL, NULL, NULL, NULL),
          (3000015, 3000000, 'Banheiro adequado a alunos com defici�ncia ou mobilidade reduzida', FALSE, NULL, NULL, NULL, NULL),
          (3000014, 3000000, 'Banheiro adequado � educa��o infantil', FALSE, NULL, NULL, NULL, NULL),
          (3000013, 3000000, 'Banheiro dentro do pr�dio', FALSE, NULL, NULL, NULL, NULL),
          (3000012, 3000000, 'Banheiro fora do pr�dio', FALSE, NULL, NULL, NULL, NULL),
          (3000126, 3000000, 'Depend�ncias e vias adequadas a alunos com defici�ncia ou mobilidade reduzida', FALSE, NULL, NULL, NULL, NULL),
          (3000003, 3000000, 'Laborat�rio de ci�ncias', FALSE, NULL, NULL, NULL, NULL),
          (3000002, 3000000, 'Laborat�rio de inform�tica', FALSE, NULL, NULL, NULL, NULL),
          (3000010, 3000000, 'Parque infantil', FALSE, NULL, NULL, NULL, NULL),
          (3000024, 3000000, 'P�tio descoberto', FALSE, NULL, NULL, NULL, NULL),
          (3000005, 3000000, 'Quadra de esportes coberta', FALSE, NULL, NULL, NULL, NULL),
          (3000006, 3000000, 'Quadra de esportes descoberta', FALSE, NULL, NULL, NULL, NULL),
          (3000000, 3000000, 'Sala de diretoria', FALSE, NULL, NULL, NULL, NULL),
          (3000009, 3000000, 'Sala de leitura', FALSE, NULL, NULL, NULL, NULL),
          (3000001, 3000000, 'Sala de professores', FALSE, NULL, NULL, NULL, NULL),
          (3000004, 3000000, 'Sala de recursos multifuncionais para Atendimento Educacional Especializado (AEE)', FALSE, NULL, NULL, NULL, NULL),
          (3000017, 3000000, 'Sala de secretaria', FALSE, NULL, NULL, NULL, NULL),
          (3000016, 3000000, 'Nenhuma das depend�ncias relacionadas', FALSE, NULL, NULL, NULL, NULL),
          (3000097, 3000016, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000098, 3000016, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000571, 3000154, NULL, TRUE, 'PredioCompartilhadoInep1_2', 0, NULL, NULL),
          (3000572, 3000155, NULL, TRUE, 'PredioCompartilhadoInep2_2', 0, NULL, NULL),
          (3000576, 3000156, NULL, TRUE, 'PredioCompartilhadoInep3_2', 0, NULL, NULL),
          (3000573, 3000157, NULL, TRUE, 'PredioCompartilhadoInep4_2', 0, NULL, NULL),
          (3000574, 3000158, NULL, TRUE, 'PredioCompartilhadoInep5_2', 0, NULL, NULL),
          (3000575, 3000159, NULL, TRUE, 'PredioCompartilhadoInep6_2', 0, NULL, NULL),
          (3000099, 3000017, 'N�O FILTRADA', FALSE, NULL, NULL, NULL, NULL),
          (3000100, 3000017, 'FILTRADA', FALSE, NULL, NULL, NULL, NULL),
          (3000101, 3000018, 'OFERECE', FALSE, NULL, NULL, NULL, NULL),
          (3000102, 3000018, 'N�O OFERECE', FALSE, NULL, NULL, NULL, NULL),
          (3000103, 3000019, NULL, TRUE, NULL, NULL, NULL, NULL),
          (3000104, 3000020, NULL, TRUE, NULL, NULL, NULL, NULL),
          (3000105, 3000021, 'N�O EXCLUSIVAMENTE', FALSE, NULL, NULL, NULL, NULL),
          (3000106, 3000021, 'N�O OFERECE', FALSE, NULL, NULL, NULL, NULL),
          (3000107, 3000021, 'EXCLUSIVAMENTE', FALSE, NULL, NULL, NULL, NULL),
          (3000108, 3000022, 'N�O EXCLUSIVAMENTE', FALSE, NULL, NULL, NULL, NULL),
          (3000109, 3000022, 'EXCLUSIVAMENTE', FALSE, NULL, NULL, NULL, NULL),
          (3000110, 3000022, 'N�O OFERECE', FALSE, NULL, NULL, NULL, NULL),
          (3000111, 3000023, 'N�O', FALSE, NULL, NULL, NULL, NULL),
          (3000112, 3000023, 'SIM', FALSE, NULL, NULL, NULL, NULL),
          (3000561, 3000153, 'SIM', FALSE, 'PossuiFormacaoPorAlternancia', 0, NULL, NULL),
          (3000562, 3000153, 'N�O', FALSE, 'NaoPossuiFormacaoPorAlternancia', 0, NULL, NULL),
          (3000113, 3000024, NULL, TRUE, NULL, NULL, NULL, NULL),
          (3000033, 3000003, NULL, TRUE, NULL, NULL, NULL, NULL);
        COMMIT;
SQL;
        $this->execute($sql);

    }

    public  function down()
    {
        $sql = <<<SQL
        BEGIN;
        SELECT fc_startsession();

        ALTER TABLE avaliacaoperguntaopcao DISABLE TRIGGER ALL;

        DELETE FROM avaliacaoperguntaopcao
        WHERE db104_avaliacaopergunta IN (
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Escola cede espa�o para turmas do Brasil Alfabetizado'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Escola abre aos finais de semana para a comunidade'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Forma de Ocupa��o do Pr�dio:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Esgoto Sanitario:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Materais Did�ticos Espec�ficos:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Equipamentos Existentes:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Destina��o do Lixo:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Abastecimento de �gua'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Abastecimento de Energia:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Possui computadores:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Acesso � Internet:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Banda Larga:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Local de Funcionamento:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Depend�ncias Existentes na Escola'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Predio Compartilhado'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 1'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 2'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 3'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 4'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 5'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'C�digo INEP do pr�dio compartilhado 6'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = '�gua consumida pelos Alunos:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Alimenta��o Escolar para os Alunos'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'N� de Sala de Aula Existentes na Escola:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'N� de Salas Utilizadas como Sala de Aula:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Atividade Complementar'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Atendimento Educ. Especializado AEE:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Ensino Fundamental em ciclos:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Escola com proposta pedagogica de forma��o por altern�ncia'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Qtde. de Computadores Uso de Alunos:'),
            (select db103_sequencial from avaliacaopergunta where db103_descricao = 'Qtde. de Computadores Uso Administrativo:')
        );

        ALTER TABLE avaliacaoperguntaopcao ENABLE TRIGGER ALL;
        ALTER TABLE avaliacaopergunta DISABLE TRIGGER ALL;

        DELETE FROM avaliacaopergunta
        WHERE db103_avaliacaogrupopergunta IN (
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'Escola cede espa�o para turmas do Brasil Alfabetiz'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'Escola abre aos finais de semana para a comunidade'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'FORMA DE OCUPA��O DO PR�DIO'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'ESGOTO SANIT�RIO'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'MATERIAIS DID�TICOS ESPEC�FICOS'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'EQUIPAMENTOS EXISTENTES'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'DESTINA��O DO LIXO'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'ABASTECIMENTO DE �GUA'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'ABASTECIMENTO DE ENERGIA'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'INFRA-ESTRUTURA'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'PREDIO COMPARTILHADO'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'OUTRAS INFORMA��ES'),
            (select db102_sequencial from avaliacaogrupopergunta where db102_descricao = 'COMPUTADORES')
        );

        ALTER TABLE avaliacaopergunta ENABLE TRIGGER ALL;
        ALTER TABLE avaliacaogrupopergunta DISABLE TRIGGER ALL;

        DELETE FROM avaliacaogrupopergunta
        WHERE db102_avaliacao = 3000000;

        ALTER TABLE avaliacaogrupopergunta ENABLE TRIGGER ALL;
        ALTER TABLE avaliacao DISABLE TRIGGER ALL;

        DELETE FROM avaliacao
        WHERE db101_sequencial = 3000000;

        ALTER TABLE avaliacao ENABLE TRIGGER ALL;
        COMMIT;
SQL;
        $this->execute($sql);
    }
}
