<?php


namespace Crmdesenvolvimentos\PixSicredi;


use Crmdesenvolvimentos\PixSicredi\Http\Request;
use Crmdesenvolvimentos\PixSicredi\Resources\Payload;
use Crmdesenvolvimentos\PixSicredi\Resources\PixRecebido;
use Crmdesenvolvimentos\PixSicredi\Resources\Cobv;
use Crmdesenvolvimentos\PixSicredi\Util\Support;
use Crmdesenvolvimentos\PixSicredi\Resources\Webhook;
use Crmdesenvolvimentos\PixSicredi\Resources\Cob;
use Exception;

class Api
{

    const BASE_DEVELOPMENT = 'https://api-pix-h.sicredi.com.br';
    const BASE_PRODUCTION = 'https://api-pix.sicredi.com.br';
    const BASE_PATH = 'api/v2';
    const PRODUCTION = 'production';
    const DEVELOPMENT = 'development';
    const ENVIRONMENT = [self::PRODUCTION, self::DEVELOPMENT];

    public string $environment = 'production';
    protected string $client_id;
    protected string $client_secret;
    protected string $certificado_sicredi;
    protected string $certificado_cadeia_completa;
    protected string $certificado_aplicacao;
    protected string $password_certificado_aplicacao;
    public int $timeout = 5;
    public ?string $token = null;


    public function setEnvironment(string $environment): Api
    {
        $value = strtolower($environment);

        if (in_array($value, self::ENVIRONMENT)) {
            $this->environment = $value;
        } else {
            throw new Exception('ambiente inválido');
        }

        return $this;
    }


    public function getClientId(): string
    {
        return (string)$this->client_id;
    }


    public function setClientId(string $client_id): Api
    {
        $this->client_id = $client_id;
        return $this;
    }


    public function getClientSecret(): string
    {
        return (string)$this->client_secret;
    }


    public function setClientSecret(string $client_secret): Api
    {
        $this->client_secret = $client_secret;
        return $this;
    }


    public function getCertificadoSicredi(): string
    {
        return (string)$this->certificado_sicredi;
    }


    public function setCertificadoSicredi(string $certificado_sicredi): Api
    {
        if (!file_exists($certificado_sicredi)) {
            throw new Exception('certificado sicredi não encontrado');
        }

        $this->certificado_sicredi = $certificado_sicredi;

        return $this;
    }


    public function getCertificadoCadeiaCompleta(): string
    {
        return (string)$this->certificado_cadeia_completa;
    }


    public function setCertificadoCadeiaCompleta(string $certificado_cadeia_completa): Api
    {
        if (!file_exists($certificado_cadeia_completa)) {
            throw new Exception('certificado cadeia completa não encontrado');
        }

        $this->certificado_cadeia_completa = $certificado_cadeia_completa;

        return $this;
    }


    public function getCertificadoAplicacao(): string
    {
        return (string)$this->certificado_aplicacao;
    }


    public function setCertificadoAplicacao(string $certificado_aplicacao): Api
    {
        if (!file_exists($certificado_aplicacao)) {
            throw new Exception('certificado da aplicação não encontrado');
        }

        $this->certificado_aplicacao = $certificado_aplicacao;

        return $this;
    }


    public function getPasswordCertificadoAplicacao(): string
    {
        return (string)$this->password_certificado_aplicacao;
    }


    public function setPasswordCertificadoAplicacao(string $password_certificado_aplicacao): Api
    {
        $this->password_certificado_aplicacao = $password_certificado_aplicacao;

        return $this;
    }


    public function setTimeout($timeout): Api
    {
        $this->timeout = (int)$timeout;

        return $this;
    }


    public function setToken(string $token): Api
    {
        $this->token = $token;

        return $this;
    }


    public function cob(): Cob
    {
        return new Cob($this);
    }


    public function cobv(): Cobv
    {
        return new Cobv($this);
    }


    public function loteCobv(): LoteCobv
    {
        return new LoteCobv($this);
    }


    public function webhook(): Webhook
    {
        return new Webhook($this);
    }


    public function payload(): Payload
    {
        return new Payload($this);
    }


    public function pixRecebido(): PixRecebido
    {
        return new PixRecebido($this);
    }


    public function tokenRequest(): Request
    {
        $scopes = [
            'cob.read', 'cob.write', // criar e consultar cobranças
            'cobv.read', 'cobv.write', // criar e consultar cobranças com vencimento
            'lotecobv.read', 'lotecobv.write', // criar e consultar lotes de cobranças
            'pix.write', 'pix.read', // consultar e criar pix
            'webhook.read', 'webhook.write', // criar e consultar webhook
            'payloadlocation.read', 'payloadlocation.write' // criar e consultar payload
        ];

        $resquest = (new Request($this))->authenticate(
            $this->getUrl(
                'oauth/token?grant_type=client_credentials&scope=' . implode('+', $scopes),
                false
            ),
            $this->client_secret,
            $this->client_id
        );

        if ($resquest->status_code === 200) {
            $this->setToken($resquest->response->getDataValue('access_token'));
        }

        return $resquest;
    }


    public function getUrl(?string $path = null, $includePathApi = true): string
    {
        $base = self::DEVELOPMENT;

        switch ($this->environment) {
            case self::PRODUCTION :
                $base = self::BASE_PRODUCTION;
                break;
            case self::DEVELOPMENT:
                $base = self::BASE_DEVELOPMENT;
                break;
        }

        if ($includePathApi)
            return $base . Support::start(self::BASE_PATH, '/') . Support::start($path, '/');
        else
            return $base . Support::start($path, '/');
    }

}
