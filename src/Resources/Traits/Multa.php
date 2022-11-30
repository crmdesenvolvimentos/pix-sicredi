<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


trait Multa
{

    protected array $multa = [
        1 => 'valor fixo',
        2 => 'percentual'
    ];



    public function getModalidadesMulta(): array
    {
        return $this->multa;
    }


    public function setModalidadeMulta(int $modalidade): self
    {
        if (!in_array($modalidade, array_keys($this->multa))) {
            throw new \Exception('invalid multa modality, must be 1 or 2');
        }

        $this->valor['multa']['modalidade'] = $modalidade;

        return $this;
    }


    public function setValorMultaModalidade(float $valor): self
    {
        if ($valor <= 0) {
            throw new \Exception('valor multa deve ser maior que 0');
        }

        $this->valor['multa']['valorPerc'] = number_format($valor, 2, '.', '');

        return $this;
    }

}
