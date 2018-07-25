<?php

namespace Omniship\Correios;
use Omniship\Common\AbstractCarrier;
use Omniship\Common\Helper;
use PhpSigep\Bootstrap;
use PhpSigep\Config;
use PhpSigep\Model\AccessDataHomologacao;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\ServicoDePostagem;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Correios Class
 */
class Carrier extends AbstractCarrier
{
    public function getName()
    {
        return 'Correios';
    }

    /**
     * Initialize this carrier with default parameters
     *
     * @param  array $parameters
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }

        Helper::initialize($this, $parameters);

        $this->bootstrap();

        return $this;
    }

    private function bootstrap() {
        $config = new Config();
        $config->setAccessData($this->parameters->get('accessData'));
        $config->setEnv($this->parameters->get('env'));
        $config->setCacheOptions(
            array(
                'storageOptions' => array(
                    // Qualquer valor setado neste atributo será mesclado ao atributos das classes
                    // "\PhpSigep\Cache\Storage\Adapter\AdapterOptions" e "\PhpSigep\Cache\Storage\Adapter\FileSystemOptions".
                    // Por tanto as chaves devem ser o nome de um dos atributos dessas classes.
                    'enabled' => false,
                    'ttl' => 10,// "time to live" de 10 segundos
                    'cacheDir' => sys_get_temp_dir(), // Opcional. Quando não inforado é usado o valor retornado de "sys_get_temp_dir()"
                ),
            )
        );
        Bootstrap::start($config);
    }

    public function getDefaultParameters()
    {
        $dimensao = new Dimensao();
        $dimensao->setTipo(Dimensao::TIPO_PACOTE_CAIXA);
        $dimensao->setAltura(15); // em centímetros
        $dimensao->setComprimento(17); // em centímetros
        $dimensao->setLargura(12); // em centímetros

        $settings = parent::getDefaultParameters();
        $settings['accessData'] = new AccessDataHomologacao();
        $settings['env'] = Config::ENV_DEVELOPMENT;
        $settings['services'] = ServicoDePostagem::getAll();
        $settings['cepOrigem'] = '87013-210';
        $settings['cepDestino'] = '87509-645';
        $settings['ajustarDimensaoMinima'] = true;
        $settings['dimensao'] = $dimensao;
        $settings['peso'] = 0.150; // 150 gramas

        return $settings;
    }

    public function quote(array $parameters = []) {
        return $this->createRequest('\Omniship\Correios\Message\CorreiosQuoteRequest', $parameters);
    }

    public function track(array $options = []) {

    }
}
