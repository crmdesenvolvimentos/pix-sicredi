<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources;

use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Http\Request;
use Crmdesenvolvimentos\PixSicredi\Util\Support;
use Crmdesenvolvimentos\PixSicredi\Resources\Traits\Valor;
use Crmdesenvolvimentos\PixSicredi\Resources\Traits\Devedor;
use Crmdesenvolvimentos\PixSicredi\Resources\Filters\CobFilters;
use Crmdesenvolvimentos\PixSicredi\Resources\Traits\InfoAdicional;

class Cob
{
    use Devedor, Valor, InfoAdicional;

    public Api $api;
    protected ?string $chave = null;
    protected ?string $txid = null;
    protected array $calendario = ['expiracao' => 86400];
    protected array $devedor = [];
    protected ?string $location = null;
    protected array $valor = [];
    protected ?string $solicitacaoPagador = null;
    protected array $infoAdicionais = [];
    public Request $request;


    public function __construct(Api $api)
    {
        $this->api = $api;
    }


    public function setChave(string $chave): Cob
    {
        if (Support::length($chave) > 77) {
            throw new \Exception('a chave pix deve ter no máximo 77 caracteres');
        }

        $this->chave = $chave;

        return $this;
    }


    public function setTxId(string $txid): Cob
    {
        if (!preg_match('/^[a-zA-Z0-9]{26,35}$/', $txid)) {
            throw new \Exception('txid inválido, deve ser alfanumérico entre 26 e 35 caracteres');
        }

        $this->txid = $txid;

        return $this;
    }


    public function setExpiracao(int $secounds): Cob
    {
        $this->calendario['expiracao'] = $secounds;

        return $this;
    }


    public function setLocation(string $location): Cob
    {
        $this->location = $location;

        return $this;
    }


    public function setSolicitacaoPagador(string $solicitacaopagador): Cob
    {
        $this->solicitacaoPagador = Support::substr($solicitacaopagador, 0, 140);

        return $this;
    }


    public function create(): Cob
    {
        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/cob/' . $this->txid),
                $this->txid ? 'PUT' : 'POST',
                ['body' => $this->getBody()]
            );

        $this->request = $request;

        return $this;
    }


    public function update(): Cob
    {
        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/cob/' . $this->txid),
                'PATCH',
                ['body' => $this->getBody(true)]
            );

        $this->request = $request;

        return $this;
    }


    public function consult(string $txid): Cob
    {
        $request = (new Request($this->api))
            ->call($this->api->getUrl('/cob/' . $txid));

        $this->request = $request;

        return $this;
    }


    public function cancel(string $txid): Cob
    {
        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/cob/' . $txid),
                'PATCH',
                ['body' => ['status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR']]
            );

        $this->request = $request;

        return $this;
    }


    public function list(CobFilters $filter): Cob
    {
        $request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/cob/'),
                'GET',
                $filter->toArray()
            );

        $this->request = $request;

        return $this;
    }


    public function getBody(?bool $filled = true): array
    {
        $data = [
            'chave' => $this->chave,
            'txid' => $this->txid,
            'calendario' => $this->calendario,
            'devedor' => $this->devedor,
            'location' => $this->location,
            'valor' => $this->valor,
            'solicitacaoPagador' => $this->solicitacaoPagador,
            'infoAdicionais' => $this->infoAdicionais
        ];

        if ($filled) {
            $data = array_filter($data, function($row){ return !empty($row); });
        }

        return $data;
    }

}
