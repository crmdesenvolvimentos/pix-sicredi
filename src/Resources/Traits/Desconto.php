<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


use Crmdesenvolvimentos\PixSicredi\Util\Support;

trait Desconto
{

    protected array $desconto = [
        1 => 'valor fixo até a primeira data informada',
        2 => 'percentual até a primeira data informada',
        3 => 'valor por antecipação dia corrido',
        4 => 'valor por antecipação dia útil',
        5 => 'percentual por antecipação dia corrido',
        6 => 'percentual por antecipação dia útil',
    ];



    public function getModalidadesDesconto(): array
    {
        return $this->desconto;
    }


    public function setModalidadeDesconto(int $modalidade): self
    {
        if (!in_array($modalidade, array_keys($this->desconto))) {
            throw new \Exception('modalidade de desconto inválido, deve ser entre 1 e 6');
        }

        $this->valor['desconto']['modalidade'] = $modalidade;

        return $this;
    }


    public function addDescontoDataFixa(string $date, float $valor): self
    {
        $modalidade = $this->modalidadeDesconto();

        if ( !in_array($modalidade, [1,2]) ){
            throw new \Exception('modalidade informada não permite desconto por data fixa');
        }

        if (!isset($this->valor['desconto']['descontoDataFixa'])){
            $this->valor['desconto']['descontoDataFixa'] = [];
        }

        if (count($this->valor['desconto']['descontoDataFixa']) >= 3) {
            throw new \Exception('descontos por data já atingiu o limite máximo de 3 registros');
        }

        if (!Support::validateDate($date)) {
            throw new \Exception('a data de desconto fixo não é uma data válida');
        }

        $this->valor['desconto']['descontoDataFixa'][] = [
            'data' => $date,
            'valorPerc' => number_format($valor, 2)
        ];

        return $this;
    }


    public function setValorDescontoModalidade(float $valor): self
    {
        $modalidade = $this->modalidadeDesconto();

        if ( !in_array($modalidade, [3,4,5,6]) ){
            throw new \Exception('modalidade informada não permite desconto por data fixa');
        }

        $this->valor['desconto']['valorPerc'] = number_format($valor, 2);

        return $this;
    }


    private function modalidadeDesconto()
    {
        $modalidade = Support::data_get($this->valor, 'desconto.modalidade');

        if (is_null($modalidade)){
            throw new \Exception('modalidade de desconto não informado, é exigido como primeiro parâmetro informar a modalidade');
        }

        return $modalidade;
    }

}
