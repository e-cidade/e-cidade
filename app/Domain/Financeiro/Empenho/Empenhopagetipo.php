<?php

namespace App\Domain\Financeiro\Empenho;

class Empenhopagetipo
{
     /**
     * @var integer
     */
    private int $e83_codtipo;

     /**
     * @var string
     */
    private string $e83_descr;

     /**
     * @var integer
     */
    private int $e83_conta;

      /**
     * @var integer
     */
    private int $e83_codmod;

     /**
     * @var string
     */
    private string $e83_convenio;

     /**
     * @var integer
     */
    private int $e83_sequencia;

     /**
     * @var string
     */
    private string $e83_codigocompromisso;

    public function getE83Codtipo(): int
    {
        return $this->e83_codtipo;
    }

    public function setE83Codtipo(int $e83_codtipo): self
    {
        $this->e83_codtipo = $e83_codtipo;
        return $this;
    }

    public function getE83Descr(): string
    {
        return $this->e83_descr;
    }

    public function setE83Descr(string $e83_descr): self
    {
        $this->e83_descr = mb_convert_encoding($e83_descr,  "UTF-8", "ISO-8859-1");
        return $this;
    }

    public function getE83Conta(): int
    {
        return $this->e83_conta;
    }

    public function setE83Conta(int $e83_conta): self
    {
        $this->e83_conta = $e83_conta;
        return $this;
    }

    public function getE83Codmod(): int
    {
        return $this->e83_codmod;
    }

    /**
     * @param int $e83_codmod
     * @return self
     */
    public function setE83Codmod(int $e83_codmod): self
    {
        $this->e83_codmod = $e83_codmod;
        return $this;
    }

    public function getE83Convenio(): string
    {
        return $this->e83_convenio;
    }

    public function setE83Convenio(string $e83_convenio): self
    {
        $this->e83_convenio = $e83_convenio;
        return $this;
    }

    public function getE83Sequencia(): int
    {
        return $this->e83_sequencia;
    }

    public function setE83Sequencia(int $e83_sequencia): self
    {
        $this->e83_sequencia = $e83_sequencia;
        return $this;
    }

    public function getE83Codigocompromisso(): string
    {
        return $this->e83_codigocompromisso;
    }

    public function setE83Codigocompromisso(string $e83_codigocompromisso): self
    {
        $this->e83_codigocompromisso = $e83_codigocompromisso;
        return $this;
    }

}
