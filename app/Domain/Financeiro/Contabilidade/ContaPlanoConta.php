<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlanoConta
{
     /**
     * @var integer
     */
    private int $c63_codcon;

     /**
     * @var integer
     */
    private int $c63_anousu;

     /**
     * @var string
     */
    private string $c63_banco;

      /**
     * @var string
     */
    private string $c63_agencia;

     /**
     * @var string
     */
    private string $c63_conta;

     /**
     * @var string
     */
    private string $c63_dvconta;

     /**
     * @var string
     */
    private string $c63_dvagencia;

     /**
     * @var string
     */
    private string $c63_identificador;

     /**
     * @var string
     */
    private string $c63_codigooperacao;

     /**
     * @var integer
     */
    private int $c63_tipoconta;

    public function getC63Codcon(): int
    {
        return $this->c63_codcon;
    }

    public function setC63Codcon(int $c63_codcon): self
    {
        $this->c63_codcon = $c63_codcon;
        return $this;
    }

    public function getC63Anousu(): int
    {
        return $this->c63_anousu;
    }

    public function setC63Anousu(int $c63_anousu): self
    {
        $this->c63_anousu = $c63_anousu;
        return $this;
    }

    public function getC63Banco(): string
    {
        return $this->c63_banco;
    }

    public function setC63Banco(string $c63_banco): self
    {
        $this->c63_banco = $c63_banco;
        return $this;
    }

    public function getC63Agencia(): string
    {
        return $this->c63_agencia;
    }

    /**
     * @param string $c63_agencia
     * @return self
     */
    public function setC63Agencia(string $c63_agencia): self
    {
        $this->c63_agencia = mb_convert_encoding($c63_agencia,  "UTF-8", "ISO-8859-1");
        return $this;
    }

    public function getC63Conta(): string
    {
        return $this->c63_conta;
    }

    public function setC63Conta(string $c63_conta): self
    {
        $this->c63_conta = $c63_conta;
        return $this;
    }

    public function getC63Dvconta(): string
    {
        return $this->c63_dvconta;
    }

    public function setC63Dvconta(string $c63_dvconta): self
    {
        $this->c63_dvconta = $c63_dvconta;
        return $this;
    }

    public function getC63Dvagencia(): string
    {
        return $this->c63_dvagencia;
    }

    public function setC63Dvagencia(string $c63_dvagencia): self
    {
        $this->c63_dvagencia = $c63_dvagencia;
        return $this;
    }

    public function getC63Identificador(): string
    {
        return $this->c63_identificador;
    }

    public function setC63Identificador(string $c63_identificador): self
    {
        $this->c63_identificador = $c63_identificador;
        return $this;
    }

    public function getC63Codigooperacao(): string
    {
        return $this->c63_codigooperacao;
    }

    public function setC63Codigooperacao(string $c63_codigooperacao): self
    {
        $this->c63_codigooperacao = $c63_codigooperacao;
        return $this;
    }

    public function getC63Tipoconta(): string
    {
        return $this->c63_tipoconta;
    }

    public function setC63Tipoconta(string $c63_tipoconta): self
    {
        $this->c63_tipoconta = $c63_tipoconta;
        return $this;
    }

}
