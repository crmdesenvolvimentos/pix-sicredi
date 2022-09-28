<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


trait Abatimento
{

    protected array $abatimento = [
        1 => 'valor fixo',
        2 => 'percentual',
    ];


    public function setModalidadeAbatimento(int $modalidade): self
    {
        if (!in_array($modalidade, array_keys($this->abatimento))) {
            throw new \Exception('modalidade de abatimento inválido, deve ser entre 1 ou 2');
        }

        $this->valor['abatimento']['modalidade'] = $modalidade;

        return $this;
    }


    public function setValorAbatimentoModalidade(float $valor): self
    {
        if ($valor <= 0) {
            throw new \Exception('valor do abatimento deve ser maior que 0');
        }

        $this->valor['abatimento']['valorPerc'] = number_format($valor, 2);

        return $this;
    }

}
