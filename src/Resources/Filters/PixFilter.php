<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Filters;

use Crmdesenvolvimentos\PixSicredi\Util\Support;
use Crmdesenvolvimentos\PixSicredi\Resources\Traits\BasicFilter;


class PixFilter
{
    use BasicFilter;


    public function txid(string $txid): PixFilter
    {
        $this->filters['txid'] = $txid;

        return $this;
    }


    public function cpf(string $cpf): PixFilter
    {
        $this->filters['cpf'] = Support::onlyNumbers($cpf);

        return $this;
    }


    public function cnpj(string $cnpj): PixFilter
    {
        $this->filters['cnpj'] = Support::onlyNumbers($cnpj);

        return $this;
    }


    public function comTxid(): PixFilter
    {
        $this->filters['txIdPresente'] = true;

        return $this;
    }


    public function semTxId(): PixFilter
    {
        $this->filters['txIdPresente'] = false;

        return $this;
    }


    public function comDevolucao(): PixFilter
    {
        $this->filters['devolucaoPresente'] = true;

        return $this;
    }


    public function semDevolucao(): PixFilter
    {
        $this->filters['devolucaoPresente'] = false;

        return $this;
    }

}
