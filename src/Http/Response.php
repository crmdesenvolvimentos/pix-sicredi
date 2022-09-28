<?php


namespace Crmdesenvolvimentos\PixSicredi\Http;

use Crmdesenvolvimentos\PixSicredi\Util\Support;

class Response
{

    private string $response_text;
    private array $data = [];


    public function getResponseText(): string
    {
        return (string)$this->response_text;
    }


    public function setResponseText(string $response_text): Response
    {
        $this->response_text = $response_text;

        return $this;
    }


    public function getData(): array
    {
        return (array)$this->data;
    }


    public function getDataValue($key, $default = null)
    {
        return Support::data_get($this->data, $key, $default);
    }


    public function setData(array $data): void
    {
        $dot = Support::dot($data);

        $this->data = Support::undot($dot);
    }


    public function isEmpty(): bool
    {
        return empty($this->data);
    }

}
