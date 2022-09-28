<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Filters;

use Crmdesenvolvimentos\PixSicredi\Resources\Traits\BasicFilter;

class PayloadFilter
{
    use BasicFilter;


    public function comTxid(): PayloadFilter
    {
        $this->filters['txIdPresente'] = true;

        return $this;
    }


    public function semTxId(): PayloadFilter
    {
        $this->filters['txIdPresente'] = false;

        return $this;
    }


    public function somenteCob(): PayloadFilter
    {
        $this->filters['tipoCob'] = 'cob';
    }


    public function somenteCobv(): PayloadFilter
    {
        $this->filters['tipoCob'] = 'cobv';
    }

}
