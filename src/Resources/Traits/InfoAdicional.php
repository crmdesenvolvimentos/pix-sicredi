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


    public function addInformacaoAdicional(array $data): self
    {
        try {
            foreach ($data as $info) {
                $this->setInformacaoAdicional(
                    $info['nome'],
                    Support::data_get($info, 'informacao', Support::data_get($info, 'valor'))
                );
            }

            return $this;
        }
        catch (\Exception | \Throwable $e) {
            throw new \Exception('array contendo as informações adicionais é inválido, deve conter as keys (nome, valor)');
        }
    }

}
