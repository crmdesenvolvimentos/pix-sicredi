<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


use Crmdesenvolvimentos\PixSicredi\Util\Support;


trait Devedor
{

    public function setCpf(string $cpf): self
    {
        if (!Support::validateCpf($cpf)) {
            throw new \Exception('cpf inválido');
        }

        if (!is_null(Support::data_get($this->devedor, 'cnpj'))) {
            throw new \Exception('cnpj informado, não é permitido informar o cpf');
        }

        $this->devedor['cpf'] = Support::onlyNumbers($cpf);

        return $this;
    }


    public function setCnpj(string $cnpj): self
    {
        if (!Support::validateCnpj($cnpj)) {
            throw new \Exception('cnpj inválido');
        }

        if (!is_null(Support::data_get($this->devedor, 'cpf'))) {
            throw new \Exception('cpf informado, não é permitido informar o cnpj');
        }

        $this->devedor['cnpj'] = Support::onlyNumbers($cnpj);

        return $this;
    }


    public function setNome(string $nome): self
    {
        $this->devedor['nome'] = $nome;

        return $this;
    }

}
