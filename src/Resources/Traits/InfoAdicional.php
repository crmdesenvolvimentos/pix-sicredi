<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


use Crmdesenvolvimentos\PixSicredi\Util\Support;

trait InfoAdicional
{

    public function setInformacaoAdicional(string $nome, string $informacao): self
    {
        $this->infoAdicionais[] = [
            'nome' => Support::substr($nome, 0, 50),
            'valor' => Support::substr($informacao, 0, 200),
        ];

        return $this;
    }

}
