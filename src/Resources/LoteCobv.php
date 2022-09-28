<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources;


use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Http\Request;
use Crmdesenvolvimentos\PixSicredi\Resources\Filters\LoteFilter;

class LoteCobv
{

    public Api $api;
    protected string $loteId;
    protected string $descricao;
    protected array $cobsv = [];


    public function __construct(Api $api)
    {
        $this->api = $api;
        $this->cobsv = [];
    }


    public function cobv(): Cobv
    {
        return new Cobv($this->api);
    }


    public function addCobv(Cobv $cobv): LoteCobv
    {
        $this->cobsv[] = $cobv;

        return $this;
    }


    public function create(int $loteId, string $descricao): LoteCobv
    {
        $this->loteId = $loteId;
        $this->descricao = $descricao;

        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/lotecobv/' . $loteId),
                'PUT',
                ['body' => $this->getBody()]
            );

        $this->request = $request;

        return $this;
    }


    public function update(int $loteId, string $descricao): LoteCobv
    {
        $this->loteId = $loteId;
        $this->descricao = $descricao;

        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/lotecobv/' . $loteId),
                'PATCH',
                ['body' => $this->getBody()]
            );

        $this->request = $request;

        return $this;
    }


    public function consult(int $loteId): LoteCobv
    {
        $this->loteId = $loteId;

        $request = (new Request($this->api))
            ->call($this->api->getUrl('/lotecobv/' . $loteId));

        $this->request = $request;

        return $this;
    }


    public function list(LoteFilter $filters): LoteCobv
    {
        $this->request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/lotecobv/'),
                'GET',
                $filters->toArray()
            );

        return $this;
    }


    protected function getBody(): array
    {
        if (empty($this->cobsv)) {
            throw new \Exception('nenhum pix vencimento foi informado neste lote');
        }

        $cobsv = [];

        foreach ($this->cobsv as $item) {
            $cobsv[] = $item->getBody(true);
        }

        return [
            'descricao' => $this->descricao,
            'cobsv' => $cobsv
        ];
    }

}
