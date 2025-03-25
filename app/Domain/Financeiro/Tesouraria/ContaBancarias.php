<?php

namespace App\Domain\Financeiro\Tesouraria;

use DateTime;

class ContaBancarias
{
     /**
     * @var integer
     */
    private int $db83_sequencial;

    /**
     * @var string
     */
    private string $db83_descricao;

    /**
     * @var integer
     */
    private int $db83_bancoagencia;

     /**
     * @var string
     */
    private string $db83_codbanco;

    /**
     * @var string
     */
    private string $db83_conta;

    /**
     * @var string
     */
    private string $db83_dvconta;

     /**
     * @var string
     */
    private string$db83_dvagencia;

    /**
     * @var string
     */
    private string $db83_identificador;

    /**
     * @var string
     */
    private string $db83_codigooperacao;

    /**
     * @var integer
     */
    private int $db83_tipoconta;

    /**
     * @var bool
     */
    private bool $db83_contaplano;

    /**
     * @var integer
     */
    private int $db83_convenio;

    /**
     * @var integer
     */
    private int $db83_tipoaplicacao;

    /**
     * @var integer
     */
    private int $db83_numconvenio;

    /**
     * @var DateTime
     */
    private DateTime $db83_dataconvenio;

    /**
     * @var integer
     */
    private int $db83_nroseqaplicacao;

    /**
     * @var string
     */
    private string $db83_codigoopcredito;

    /**
     * @var DateTime
     */
    private DateTime $db83_dataimplantaoconta;


     /**
     * @var DateTime
     */
    private ?DateTime $db83_dataLimite;

     /**
     * @var DateTime
     */
    private ?DateTime $db83_dtreativacaoconta;

    /**
     * @var integer
     */
    private int $db83_fonteprincipal;

    /**
     * @var integer
     */
    private int $db83_codigotce;

    /**
     * @var string
     */
    private string $db83_reduzido;

    /**
     * @var integer
     */
    private int $db83_instituicao;

    public function getDb83Sequencial(): int
    {
        return $this->db83_sequencial;
    }
    
    /**
     * @param integer $db83_sequencial
     * @return self
     */
    public function setDb83Sequencial(int $db83_sequencial): self
    {
        $this->db83_sequencial = $db83_sequencial;
        return $this;
    }
    
    public function getDb83Descricao(): string
    {
        return $this->db83_descricao;
    }
    
    /**
     * @param string $db83_descricao
     * @return self
     */
    public function setDb83Descricao(string $db83_descricao): self
    {
        $this->db83_descricao =  $db83_descricao;
        return $this;
    }
    
    public function getDb83BancoAgencia(): string
    {
        return $this->db83_bancoagencia;
    }
    
    /**
     * @param string $db83_bancoagencia
     * @return self
     */
    public function setDb83CodBanco(string $db83_codbanco): self
    {
        $this->db83_codbanco = $db83_codbanco;
        return $this;
    }
    
    public function getDb83CodBanco(): string
    {
        return $this->db83_codbanco;
    }
    
    /**
     * @param string $db83_bancoagencia
     * @return self
     */
    public function setDb83BancoAgencia(string $db83_bancoagencia): self
    {
        $this->db83_bancoagencia = $db83_bancoagencia;
        return $this;
    }

    public function getDb83Conta(): string
    {
        return $this->db83_conta;
    }
    
    /**
     * @param string $db83_conta
     * @return self
     */
    public function setDb83Conta(string $db83_conta): self
    {
        $this->db83_conta = $db83_conta;
        return $this;
    }
    
    public function getDb83DvConta(): string
    {
        return $this->db83_dvconta;
    }
    
    /**
     * @param string $db83_dvconta
     * @return self
     */
    public function setDb83DvConta(string $db83_dvconta): self
    {
        $this->db83_dvconta = $db83_dvconta;
        return $this;
    }

    public function getDb83DvAgencia(): string
    {
        return $this->db83_dvagencia;
    }
    
    /**
     * @param string $db83_dvagencia
     * @return self
     */
    public function setDb83DvAgencia(string $db83_dvagencia): self
    {
        $this->db83_dvagencia = $db83_dvagencia;
        return $this;
    }

    public function getDb83Identificador(): string
    {
        return $this->db83_identificador;
    }
    
    /**
     * @param string $db83_identificador
     * @return self
     */
    public function setDb83Identificador(string $db83_identificador): self
    {
        $this->db83_identificador = $db83_identificador;
        return $this;
    }
    
    public function getDb83CodigoOperacao(): string
    {
        return $this->db83_codigooperacao;
    }
    
    /**
     * @param string $db83_codigooperacao
     * @return self
     */
    public function setDb83CodigoOperacao(string $db83_codigooperacao): self
    {
        $this->db83_codigooperacao = $db83_codigooperacao;
        return $this;
    }
    
    public function getDb83TipoConta(): string
    {
        return $this->db83_tipoconta;
    }
    
    /**
     * @param string $db83_tipoconta
     * @return self
     */
    public function setDb83TipoConta(string $db83_tipoconta): self
    {
        $this->db83_tipoconta = $db83_tipoconta;
        return $this;
    }
    
    public function getDb83ContaPlano(): bool
    {
        return $this->db83_contaplano;
    }
    
    /**
     * @param bool $db83_contaplano
     * @return self
     */
    public function setDb83ContaPlano(bool $db83_contaplano): self
    {
        $this->db83_contaplano = $db83_contaplano;
        return $this;
    }
    
    public function getDb83Convenio(): ?int
    {
        return $this->db83_convenio ?: null;
    }
    
    /**
     * @param int $db83_convenio
     * @return self
     */
    public function setDb83Convenio(int $db83_convenio): self
    {
        $this->db83_convenio = $db83_convenio;
        return $this;
    }
    
    public function getDb83TipoAplicacao(): string
    {
        return $this->db83_tipoaplicacao;
    }
    
    /**
     * @param string $db83_tipoaplicacao
     * @return self
     */
    public function setDb83TipoAplicacao(string $db83_tipoaplicacao): self
    {
        $this->db83_tipoaplicacao = $db83_tipoaplicacao;
        return $this;
    }
    
    public function getDb83NumConvenio(): ?int
    {
        return $this->db83_numconvenio ?: null;
    }
    
    /**
     * @param int $db83_numconvenio
     * @return self
     */
    public function setDb83NumConvenio(int $db83_numconvenio): self
    {
        $this->db83_numconvenio = $db83_numconvenio;
        return $this;
    }
        
    public function getDb83NroSeqAplicacao(): ?int
    {
        return $this->db83_nroseqaplicacao ?: null;
    }
    
    /**
     * @param int $db83_nroseqaplicacao
     * @return self
     */
    public function setDb83NroSeqAplicacao(int $db83_nroseqaplicacao): self
    {
        $this->db83_nroseqaplicacao = $db83_nroseqaplicacao;
        return $this;
    }
    
    public function getDb83CodigoOpCredito(): ?string
    {
        return $this->db83_codigoopcredito ?: null;
    }
    
    /**
     * @param string $db83_codigoopcredito
     * @return self
     */
    public function setDb83CodigoOpCredito(string $db83_codigoopcredito): self
    {
        $this->db83_codigoopcredito = $db83_codigoopcredito;
        return $this;
    }
    
    public function getDb83DataImplantaoConta(): ?DateTime
    {
        return $this->db83_dataimplantaoconta;
    }
    
    /**
     * @param string $db83_dataimplantaoconta
     * @return self
     */
    public function setDb83DataImplantaoConta(?DateTime $db83_dataimplantaoconta): self
    {
        $this->db83_dataimplantaoconta = $db83_dataimplantaoconta;
        return $this;
    }

    public function getDb83DataLimite(): ?DateTime
    {
        return $this->db83_dataLimite ?: null;
    }
    
    /**
     * @param string $db83_dataLimite
     * @return self
     */
    public function setDb83DataLimite(?DateTime $db83_dataLimite): self
    {
        $this->db83_dataLimite = $db83_dataLimite;
        return $this;
    }

    public function getDb83DataReativacaoConta(): ?DateTime
    {
        return $this->db83_dtreativacaoconta ?: null;
    }
    
    /**
     * @param string $db83_dtreativacaoconta
     * @return self
     */
    public function setDb83DataReativacaoConta(?DateTime $db83_dtreativacaoconta): self
    {

        $this->db83_dtreativacaoconta = $db83_dtreativacaoconta;
        return $this;
    }

    public function getDb83FontePrincipal(): string
    {
        return $this->db83_fonteprincipal;
    }
    
    /**
     * @param string $db83_fonteprincipal
     * @return self
     */
    public function setDb83FontePrincipal(string $db83_fonteprincipal): self
    {
        $this->db83_fonteprincipal = $db83_fonteprincipal;
        return $this;
    }
    
    public function getDb83CodigoTce(): int
    {
        return $this->db83_codigotce;
    }
    
    /**
     * @param string $db83_codigotce
     * @return self
     */
    public function setDb83CodigoTce(int $db83_codigotce): self
    {
        $this->db83_codigotce = $db83_codigotce;
        return $this;
    }
    
    public function getDb83Reduzido(): string
    {
        return $this->db83_reduzido;
    }
    
    /**
     * @param string $db83_reduzido
     * @return self
     */
    public function setDb83Reduzido(string $db83_reduzido): self
    {
        $this->db83_reduzido = $db83_reduzido;
        return $this;
    }
    
    public function getDb83Instituicao(): string
    {
        return $this->db83_instituicao;
    }
    
    /**
     * @param string $db83_instituicao
     * @return self
     */
    public function setDb83Instituicao(string $db83_instituicao): self
    {
        $this->db83_instituicao = $db83_instituicao;
        return $this;
    }
    
}
