# pix-sicredi
Api de integração com o Pix Cobrança do Banco Sicredi

### Esta api pode ser utilizada com outros PSP

### Api desenvolvida com base na documentação do Bacen: https://bacen.github.io/pix-api/


## Configurar Api

```php
<?php
    
use Crmdesenvolvimentos\PixSicredi\Api;

# configurar instância da Api
$api = (new Api)
    ->setEndpoint('URL_ENDPOINT')
    ->setOauthPath('PATH_AUTENTICACAO')
    ->setApiPath('PATH_VERSAO_API')
    ->setClientId('CLIENT_ID')
    ->setClientSecret('CLIENT_SECRET')
    ->setCertificadoPsp('CERTIFICADO_RECEBIDO_DO_PSP')
    ->setCertificadoAplicacao('SEU_CERTIFICADO')
    ->setPasswordCertificadoAplicacao('SENHA_DO_SEU_CERTIFICADO')
    ->setCertificadoCadeiaCompleta('CERTIFICADO_DO_PSP_ADICIONAL-OPCIONAL-SE-REQUERIDO');


# autenticar com o PSP - Token - se tiver sucesso já irá setar na instância o token
$api->requestToken();
```

## Criar um Pix Cob - Cobrança imediata

```php
<?php
    
use Crmdesenvolvimentos\PixSicredi\Api;
use Crmdesenvolvimentos\PixSicredi\Resources\Cob;

# opção1 : utilizando a instância da api já iniciada e autenticada
$cob = $api->cob();

#opção 2: Instanciado a classe Cob e passando a instância da api já iniciada e autenticada
$cob = new Cob($api);

# passando os parâmetros e enviando a requisiao
$cob = $api->cob()
    ->setChave('CHAVE_DO_PIX')
    ->setTxId('ID_DA_TRANSACAO')//opcional, se não informado será gerado pelo PSP
    ->setValor(100.00)
    ->setExpiracao(86400)
    ->setCnpj('CNPJ_DEVEDOR') //ou setCpf('CPF_DEVEDOR') 
    ->setNome('NOME_DEVEDOR')
    ->setSolicitacaoPagador('REFERENCIA_DO_PAGAMENTO')
    ->setInformacaoAdicional('CHAVE', 'INFORMACAO')
    ->setInformacaoAdicional('OUTRA_CHAVE', 'OUTRA_INFORMACAO')
    ->create();

# acessando a resposta contendo o array com os dados de retorno
$cob->request->response->getData();

# acessando a resposta em texto no formato json
$cob->request->response->getResponseText();
```

#### Métodos da Classe Cob 
    - create() - criar uma cobrança
    - update() - atualizar uma cobrança
    - consult($txid) - consultar uma cobranca
    - cancel($txid) - cancelar uma cobrança
    - list() - listar as cobranças por meio de filtros

