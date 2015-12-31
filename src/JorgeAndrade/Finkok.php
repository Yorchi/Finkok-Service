<?php namespace JorgeAndrade;

use JorgeAndrade\Exceptions\FinkokException;
use SoapClient;

class Finkok
{
    protected $username;

    protected $password;

    protected $url;

    public function __construct($username, $password, $sandbox = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->sandbox = $sandbox;
        $this->url = $sandbox ? 'http://demo-facturacion.finkok.com/servicios/soap/' : 'https://facturacion.finkok.com/servicios/soap/';
    }

    public function createNewClient($rfc)
    {
        $client = new SoapClient("{$this->url}registration.wsdl");
        $params = $this->prepareParams(["taxpayer_id" => $rfc]);
        $response = $client->__soapCall('add', array($params));

        if ($response->addResult->success) {
            return $response->addResult->message;
        }
        throw new FinkokException($response->addResult->message);

    }

    public function activeClient($rfc)
    {
        $client = new SoapClient("{$this->url}registration.wsdl");
        $params = $this->prepareParams(["taxpayer_id" => $rfc, "status" => 'A']);
        $response = $client->__soapCall('edit', array($params));

        if ($response->editResult->success) {
            return $response->editResult->message;
        }
        throw new FinkokException($response->editResult->message);
    }

    public function suspendClient($rfc)
    {
        $client = new SoapClient("{$this->url}registration.wsdl");
        $params = $this->prepareParams(["taxpayer_id" => $rfc, "status" => 'S']);
        $response = $client->__soapCall('edit', array($params));

        if ($response->editResult->success) {
            return $response->editResult->message;
        }

        throw new FinkokException($response->editResult->message);
    }

    public function getClients()
    {
        $client = new SoapClient("{$this->url}registration.wsdl");
        $params = $this->prepareParams();
        $response = $client->__soapCall('get', array($params));

        return $response->getResult->users;
    }

    public function getClient($rfc)
    {
        $client = new SoapClient("{$this->url}registration.wsdl");
        $params = $this->prepareParams(["taxpayer_id" => $rfc]);
        $response = $client->__soapCall('get', array($params));

        return $response->getResult->users;
    }

    public function timbrar($xml)
    {
        $xmlContent = file_get_contents($xml);

        $client = new SoapClient("{$this->url}stamp.wsdl");
        $params = $this->prepareParamsForTimbreOrCancel(["xml" => $xmlContent]);
        $response = $client->__soapCall('stamp', array($params));

        if (!isset($response->stampResult->UUID)) {
            throw new FinkokException($response->stampResult->Incidencias->Incidencia->MensajeIncidencia, $response->stampResult->Incidencias->Incidencia->CodigoError);
        }
        file_put_contents($xml, $response->stampResult->xml);
        return $response->stampResult;
    }

    public function cancelar($rfc, $uuids = [], $cer, $key)
    {
        $cerContent = file_get_contents($cer);
        $keyContent = file_get_contents($key);

        $client = new SoapClient("{$this->url}cancel.wsdl");

        $params = $this->prepareParamsForTimbreOrCancel([
            "UUIDS" => ['uuids' => $uuids],
            "taxpayer_id" => $rfc,
            "cer" => $cerContent,
            "key" => $keyContent,
        ]);

        $response = $client->__soapCall($service, array($params));

        return $response->cancelResult;
    }

    protected function prepareParams($params = [])
    {
        $defaultParams = array(
            "reseller_username" => $this->username,
            "reseller_password" => $this->password,
        );

        return array_merge($defaultParams, $params);
    }

    protected function prepareParamsForTimbreOrCancel($params = [])
    {
        $defaultParams = array(
            "username" => $this->username,
            "password" => $this->password,
        );

        return array_merge($defaultParams, $params);
    }

}
