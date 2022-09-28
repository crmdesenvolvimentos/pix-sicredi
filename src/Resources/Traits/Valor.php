<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


trait Valor
{

    protected array $modalidades = [
        0 => 'fixed value',
        1 => 'suggested value'
    ];


    public function setValor(float $valor): self
    {
        if ($valor <= 0) {
            throw new \Exception('valor deve ser maior que 0');
        }

        $this->valor['original'] = number_format($valor, 2);

        return $this;
    }


    public function setModalidadeAlteracao(int $modalidadeAlteracao): self
    {
        if (!in_array($modalidadeAlteracao, array_keys($this->modalidades))) {
            throw new \Exception('modalidade inválida, deve ser 0 ou 1');
        }

        $this->valor['modalidadeAlteracao'] = $modalidadeAlteracao;

        return $this;
    }

}
