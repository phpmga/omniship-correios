<?php namespace Omniship\Correios\Message;

abstract class AbstractRequest extends \Omniship\Common\Message\AbstractRequest
{
    public function getAccessData()
    {
        return $this->getParameter('accessData');
    }

    public function setAccessData($value)
    {
        return $this->setParameter('accessData', $value);
    }
}
