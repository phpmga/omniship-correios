<?php namespace Omniship\Correios\Message;

use Omniship\Common\Message\ResponseInterface;
use PhpSigep\Model\CalcPrecoPrazo;
use PhpSigep\Services\SoapClient\Real;

class CorreiosQuoteRequest extends AbstractRequest
{

    public function getCepOrigem()
    {
        return $this->getParameter('cepOrigem');
    }

    public function setCepOrigem($value)
    {
        return $this->setParameter('cepOrigem', $value);
    }

    public function getCepDestino()
    {
        return $this->getParameter('cepDestino');
    }

    public function setCepDestino($value)
    {
        return $this->setParameter('cepDestino', $value);
    }

    public function getServices()
    {
        return $this->getParameter('services');
    }

    public function setServices($value)
    {
        return $this->setParameter('services', $value);
    }

    public function getAjustarDimensaoMinima()
    {
        return $this->getParameter('ajustarDimensaoMinima');
    }

    public function setAjustarDimensaoMinima($value)
    {
        return $this->setParameter('ajustarDimensaoMinima', $value);
    }

    public function getDimensao()
    {
        return $this->getParameter('dimensao');
    }

    public function setDimensao($value)
    {
        return $this->setParameter('dimensao', $value);
    }

    public function getPeso()
    {
        return $this->getParameter('peso');
    }

    public function setPeso($value)
    {
        return $this->setParameter('peso', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from carrier to
     * carrier, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData() {
        $data = [];
        $data['accessData'] = $this->getAccessData();
        $data['cepOrigem'] = $this->getCepOrigem();
        $data['cepDestino'] = $this->getCepDestino();
        $data['services'] = $this->getServices();
        $data['ajustarDimensaoMinima'] = $this->getAjustarDimensaoMinima();
        $data['dimensao'] = $this->getDimensao();
        $data['peso'] = $this->getPeso();

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data) {
        $params = new CalcPrecoPrazo();
        $params->setAccessData($data['accessData']);
        $params->setCepOrigem($data['cepOrigem']);
        $params->setCepDestino($data['cepDestino']);
        $params->setServicosPostagem($data['services']);
        $params->setAjustarDimensaoMinima($data['ajustarDimensaoMinima']);
        $params->setDimensao($data['dimensao']);
        $params->setPeso($data['peso']);

        $phpSigep = new Real();
        $response = $phpSigep->calcPrecoPrazo($params);

        return new CorreiosQuoteResponse($this, $response);
    }
}
