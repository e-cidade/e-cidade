select fc_startsession();
begin;
alter table empprestaitem add column e46_obs text default null;
alter table empprestaitem add column e46_codmater integer;
alter table empprestaitem add foreign key (e46_codmater) references compras.pcmater(pc01_codmater);
alter table empprestaitem add column e46_valorunit numeric not null default 0;
alter table empprestaitem add column e46_quantidade numeric not null default 0;
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'e46_obs                         ', 'text                                    ', 'Campo destinado a preenchimento de pendências ou observações referentes ao item.', '0', 'Observação', 1, false, false, false, 0   , 'text', 'Observação');
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1037, (select codcam from db_syscampo where nomecam = 'e46_obs'), 1, 0);
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'e46_codmater                         ', 'int8                                    ', 'Item da prestação de contas.', '0', 'Cód. Item', 1, false, false, false, 0   , 'text', 'Cód. Item');
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1037, (select codcam from db_syscampo where nomecam = 'e46_codmater'), 1, 0);
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'e46_valorunit                         ', 'float8                                    ', 'Valor unitário do item.', '0', 'Valor Unit.', 10, false, false, false, 4   , 'text', 'Valor Unit.');
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1037, (select codcam from db_syscampo where nomecam = 'e46_valorunit'), 1, 0);
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'e46_quantidade                         ', 'float8                                    ', 'Quantidade do item.', '0', 'Quantidade', 10, false, false, false, 4   , 'text', 'Quantidade');
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1037, (select codcam from db_syscampo where nomecam = 'e46_quantidade'), 1, 0);
update db_syscampo set rotulo = 'Valor Total', rotulorel = 'Valor Total' where nomecam = 'e46_valor';
update db_syscampo set rotulo = 'Valor Total', rotulorel = 'Valor Total' where nomecam = 'e46_valor';
update db_syscampo set tamanho = 14 where nomecam = 'e46_cpf';
update db_syscampo set tamanho = 18 where nomecam = 'e46_cnpj';
commit;