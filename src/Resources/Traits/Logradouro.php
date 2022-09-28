<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;


use Crmdesenvolvimentos\PixSicredi\Util\Support;


trait Logradouro
{

    public function setEmail(string $email): self
    {
        $this->devedor['email'] = $email;

        return $this;
    }


    public function setLogradouro(string $logradouro): self
    {
        $this->devedor['logradouro'] = Support::substr($logradouro, 0, 200);

        return $this;
    }


    public function setCep(string $cep): self
    {
        $this->devedor['cep'] = preg_replace('/[^0-9]/', '', $cep);

        return $this;
    }


    public function setCidade(string $cidade): self
    {
        $this->devedor['cidade'] = Support::substr($cidade, 0, 200);

        return $this;
    }


    public function setUf(string $uf): self
    {
        $this->devedor['uf'] = Support::upper(Support::substr($uf, 0, 2));

        return $this;
    }

}
