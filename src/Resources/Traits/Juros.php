<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


trait Juros
{

    protected array $juros = [
        1 => 'valor por dias corridos',
        2 => 'percentual por dias corridos',
        3 => 'percentual por mês em dias corridos',
        4 => 'percentual por ano em dias corridos',
        5 => 'valor por dias úteis',
        6 => 'percentual por dias úteis',
        7 => 'percentual por mês em dias úteis',
        8 => 'percentual por ano em dias úteis',
    ];


    public function getModalidadesJuros(): array
    {
        return $this->juros;
    }



    public function setModalidadeJuros(int $modalidade): self
    {
        if (!in_array($modalidade, array_keys($this->juros))) {
            throw new \Exception('modalidade de juros inválido, deve ser entre 1 e 8');
        }

        $this->valor['juros']['modalidade'] = $modalidade;

        return $this;
    }


    public function setValorJurosModalidade(float $valor): self
    {
        if ($valor <= 0) {
            throw new \Exception('valor multa deve ser maior que 0');
        }

        $this->valor['juros']['valorPerc'] = number_format($valor, 2, '.', '');

        return $this;
    }

}
