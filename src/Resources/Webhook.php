<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources;

use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Http\Request;
use Crmdesenvolvimentos\PixSicredi\Resources\Filters\WebhookFilters;

class Webhook
{

    public Api $api;
    public Request $request;


    public function __construct(Api $api)
    {
        $this->api = $api;
    }


    public function create(string $key, string $url): Webhook
    {
        $request = (new Request($this->api))->call(
            $this->api->getUrl('/webhook/' . $key),
            'PUT',
            ['body' => ['webhookUrl' => $url]]
        );

        $this->request = $request;

        return $this;
    }


    public function consult(string $key): Webhook
    {
        $this->request = (new Request($this->api))
            ->call($this->api->getUrl('/webhook/' . $key));

        return $this;
    }


    public function list(WebhookFilters $filters): Webhook
    {
        $this->request = (new Request($this->api))
            ->call(
                $this->api->getUrl('/webhook/'),
                'GET',
                $filters->toArray()
            );

        return $this;
    }


    public function delete(string $key): Webhook
    {
        $request = (new Request($this->api))
            ->call($this->api->getUrl('/webhook/' . $key), 'DELETE');

        $this->request = $request;

        return $this;
    }

}
