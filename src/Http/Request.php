<?php


namespace Crmdesenvolvimentos\PixSicredi\Http;

use Curl\Curl;
use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Util\Support;


class Request
{
    private Api $api;
    public ?string $error = null;
    public array $body = [];
    public ?string $body_json = null;
    public Response $response;
    public ?int $status_code = null;


    public function __construct(Api $api)
    {
        $this->api = $api;
    }


    public function call(string $url, ?string $method = 'GET', ?array $params = []): Request
    {
        $headers = array_merge(
            [
                'Authorization' => 'Bearer ' . $this->api->token,
                'Content-Type' => 'application/json',
            ],
            Support::data_get($params, 'header', [])
        );

        $curl = new Curl();
        $curl->setHeaders($headers);
        $curl->setTimeout($this->api->timeout);
        $this->withCertificate($curl);
        $curl->setOpt(CURLOPT_CAINFO, $this->api->getCertificadoCadeiaCompleta());

        switch (Support::upper($method)) {
            case 'GET' :
                $curl->get($url, Support::data_get($params, 'query', []));
                break;
            case 'PUT' :
                $curl->put($url, $this->prepareBody(Support::data_get($params, 'body', [])));
                break;
            case 'POST' :
                $curl->post($url, $this->prepareBody(Support::data_get($params, 'body', [])));
                break;
            case 'PATCH' :
                $curl->patch($url, $this->prepareBody(Support::data_get($params, 'body', [])));
                break;
            case 'DELETE' :
                $curl->delete($url, [], $this->prepareBody(Support::data_get($params, 'body', [])));
                break;
        }

        $this->status_code = $curl->getHttpStatusCode();
        $this->response = new Response();
        $this->response->setResponseText($curl->rawResponse);
        $this->response->setData((array)$curl->response);

        if ($curl->error) {
            $this->error = $curl->errorCode . ': ' . $curl->errorMessage;
        }

        $curl->close();

        return $this;
    }


    public function authenticate(string $url, string $client_id, string $client_secret): Request
    {
        $curl = new Curl();
        $this->withCertificate($curl);
        $curl->setBasicAuthentication($client_id, $client_secret);
        $curl->post($url);

        $this->status_code = $curl->getHttpStatusCode();
        $this->response = new Response();
        $this->response->setResponseText($curl->rawResponse);
        $this->response->setData((array)$curl->response);

        if ($curl->error) {
            $this->error = $curl->errorCode . ': ' . $curl->errorMessage;
        }

        $curl->close();

        return $this;
    }


    private function withCertificate(Curl $curl): void
    {
        $curl->setOpt(CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_3);
        $curl->setOpt(CURLOPT_SSLCERT, $this->api->getCertificadoPsp());
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 1);
        $curl->setOpt(CURLOPT_SSLKEY, $this->api->getCertificadoAplicacao());
        $curl->setOpt(CURLOPT_KEYPASSWD, $this->api->getPasswordCertificadoAplicacao());
    }


    private function prepareBody(array $body): string
    {
        $this->body = $body;
        $this->body_json = json_encode($body);

        return $this->body_json;
    }

}
