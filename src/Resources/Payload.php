<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources;

use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Http\Request;
use Crmdesenvolvimentos\PixSicredi\Util\Support;
use Crmdesenvolvimentos\PixSicredi\Resources\Filters\PayloadFilter;


class Payload
{
    const TIPOSCOB = ['cob', 'cobv'];

    public Api $api;
    public Request $request;


    public function __construct(Api $api)
    {
        $this->api = $api;
    }


    public function create(string $tipo): Payload
    {
        $tipo = Support::lower($tipo);

        if (!in_array($tipo, self::TIPOSCOB)){
            throw new \Exception('tipo de cobrança inválido, deve ser cob ou cobv');
        }

        $request = (new Request($this->api))->call(
            $this->api->getUrl('/loc/'),
            'POST',
            ['body' => ['tipoCob' => $tipo]]
        );

        $this->request = $request;

        return $this;
    }


    public function consult(string $id): Payload
    {
        $this->request = (new Request($this->api))
            ->call($this->api->getUrl('/loc/' . $id));

        return $this;
    }


    public function list(PayloadFilter $filters): Payload
    {
        $this->request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/loc/'),
                'GET',
                $filters->toArray()
            );

        return $this;
    }


    public function delete(int $id): Payload
    {
        $request = (new Request($this->api))
            ->call($this->api->getUrl('/loc/' . $id . '/txid'), 'DELETE');

        $this->request = $request;

        return $this;
    }

}
