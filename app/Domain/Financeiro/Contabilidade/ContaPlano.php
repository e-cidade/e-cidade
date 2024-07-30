<?php

namespace App\Domain\Financeiro\Contabilidade;

class ContaPlano
{
     /**
     * @var integer
     */
    private int $c60_codcon;

     /**
     * @var integer
     */
    private int $c60_anousu;

     /**
     * @var string
     */
    private string $c60_estrut;

      /**
     * @var string
     */
    private string $c60_descr;

     /**
     * @var string
     */
    private string $c60_finali;

     /**
     * @var integer
     */
    private int $c60_codsis;

     /**
     * @var integer
     */
    private int $c60_codcla;

     /**
     * @var integer
     */
    private int $c60_consistemaconta;

     /**
     * @var string
     */
    private string $c60_identificadorfinanceiro;

     /**
     * @var integer
     */
    private int $c60_naturezasaldo;

     /**
     * @var string
     */
    private string $c60_funcao;

     /**
     * @var integer
     */
    private int $c60_tipolancamento;

     /**
     * @var integer
     */
    private int $c60_subtipolancamento;

     /**
     * @var integer
     */
    private int $c60_desdobramneto;

     /**
     * @var integer
     */
    private int $c60_nregobrig;

     /**
     * @var int
     */
    private int $c60_cgmpessoa;

     /**
     * @var integer
     */
    private int $c60_naturezadareceita;

     /**
     * @var integer
     */
    private int $c60_infcompmsc;

    public function getC60Codcon(): int
    {
        return $this->c60_codcon;
    }

    public function setC60Codcon(int $c60_codcon): self
    {
        $this->c60_codcon = $c60_codcon;
        return $this;
    }

    public function getC60Anousu(): int
    {
        return $this->c60_anousu;
    }

    public function setC60Anousu(int $c60_anousu): self
    {
        $this->c60_anousu = $c60_anousu;
        return $this;
    }

    public function getC60Estrut(): string
    {
        return $this->c60_estrut;
    }

    public function setC60Estrut(string $c60_estrut): self
    {
        $this->c60_estrut = $c60_estrut;
        return $this;
    }

    public function getC60Descr(): string
    {
        return $this->c60_descr;
    }

    /**
     * @param string $c60_descr
     * @return self
     */
    public function setC60Descr(string $c60_descr): self
    {
        $this->c60_descr = mb_convert_encoding($c60_descr,  "UTF-8", "ISO-8859-1");
        return $this;
    }

    public function getC60Finali(): string
    {
        return $this->c60_finali;
    }

    public function setC60Finali(string $c60_finali): self
    {
        $this->c60_finali = $c60_finali;
        return $this;
    }

    public function getC60Codsis(): int
    {
        return $this->c60_codsis;
    }

    public function setC60Codsis(int $c60_codsis): self
    {
        $this->c60_codsis = $c60_codsis;
        return $this;
    }

    public function getC60Codcla(): int
    {
        return $this->c60_codcla;
    }

    public function setC60Codcla(int $c60_codcla): self
    {
        $this->c60_codcla = $c60_codcla;
        return $this;
    }

    public function getC60Consistemaconta(): int
    {
        return $this->c60_consistemaconta;
    }

    public function setC60Consistemaconta(int $c60_consistemaconta): self
    {
        $this->c60_consistemaconta = $c60_consistemaconta;
        return $this;
    }

    public function getC60Identificadorfinanceiro(): string
    {
        return $this->c60_identificadorfinanceiro;
    }

    public function setC60Identificadorfinanceiro(string $c60_identificadorfinanceiro): self
    {
        $this->c60_identificadorfinanceiro = $c60_identificadorfinanceiro;
        return $this;
    }

    public function getC60Naturezasaldo(): int
    {
        return $this->c60_naturezasaldo;
    }

    public function setC60Naturezasaldo(int $c60_naturezasaldo): self
    {
        $this->c60_naturezasaldo = $c60_naturezasaldo;
        return $this;
    }

    public function getC60Funcao(): string
    {
        return $this->c60_funcao;
    }

    public function setC60Funcao(string $c60_funcao): self
    {
        $this->c60_funcao = $c60_funcao;
        return $this;
    }

    public function getC60Tipolancamento(): int
    {
        return $this->c60_tipolancamento;
    }

    public function setC60Tipolancamento(int $c60_tipolancamento): self
    {
        $this->c60_tipolancamento = $c60_tipolancamento;
        return $this;
    }

    public function getC60Subtipolancamento(): int
    {
        return $this->c60_subtipolancamento;
    }

    public function setC60Subtipolancamento(int $c60_subtipolancamento): self
    {
        $this->c60_subtipolancamento = $c60_subtipolancamento;
        return $this;
    }

    public function getC60Desdobramneto(): int
    {
        return $this->c60_desdobramneto;
    }

    public function setC60Desdobramneto(int $c60_desdobramneto): self
    {
        $this->c60_desdobramneto = $c60_desdobramneto;
        return $this;
    }

    public function getC60Nregobrig(): int
    {
        return $this->c60_nregobrig;
    }

    public function setC60Nregobrig(int $c60_nregobrig): self
    {
        $this->c60_nregobrig = $c60_nregobrig;
        return $this;
    }

    public function getC60Cgmpessoa(): ?int
    {
        return $this->c60_cgmpessoa ?: null;
    }

    public function setC60Cgmpessoa(int $c60_cgmpessoa): self
    {
        $this->c60_cgmpessoa = $c60_cgmpessoa;
        return $this;
    }

    public function getC60Naturezadareceita(): int
    {
        return $this->c60_naturezadareceita;
    }

    public function setC60Naturezadareceita(int $c60_naturezadareceita): self
    {
        $this->c60_naturezadareceita = $c60_naturezadareceita;
        return $this;
    }

    public function getC60Infcompmsc(): int
    {
        return $this->c60_infcompmsc;
    }

    public function setC60Infcompmsc(int $c60_infcompmsc): self
    {
        $this->c60_infcompmsc = $c60_infcompmsc;
        return $this;
    }
}
