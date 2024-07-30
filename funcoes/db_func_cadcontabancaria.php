<?

$campos = 'distinct contabancaria.db83_sequencial,
                    contabancaria.db83_descricao,
                    bancoagencia.db89_codagencia,
                    bancoagencia.db89_digito,
                    contabancaria.db83_conta,
                    contabancaria.db83_dvconta,
                    case
                        when contabancaria.db83_tipoconta = 1 then \'Conta Corrente\'
                    when contabancaria.db83_tipoconta = 2 then \'Conta Poupança\'
                    when contabancaria.db83_tipoconta = 3 then \'Conta Aplicação\'
                    when contabancaria.db83_tipoconta = 4 then \'Conta Salário\'
                    end as "dl_Descricao Tipo de Conta",
                    c61_reduz,
                    c61_codtce "dl_Cod. TCE",
                    c61_codigo
               ';
              

?>