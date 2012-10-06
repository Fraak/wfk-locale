<?php

namespace WfkLocale\Service;

use Zend\ServiceManager\FactoryInterface;
use WfkLocale\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\ConfigFactory as ZendConfigFactory;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 */
class ConfigFactory extends ZendConfigFactory
{
    /**
     * Rewrite router config to prepend a language part route
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return array|\Traversable
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = parent::createService($serviceLocator);

        $mode = !isset($config['wfklocale']['mode']) ? ModuleOptions::MODE_URLKEY : $config['wfklocale']['mode'];
        if ($mode == ModuleOptions::MODE_URLKEY && isset($config['router']))
        {
            $possibleLocales = $config['wfklocale']['enabled'];
            $possibleLocales = array_unique(array_values($possibleLocales));
            $config['router']['routes'] = array(
                'wfklocale-root' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route'    => '/:locale',
                        'constraints' => array(
                            'locale' => '(' . implode('|', $possibleLocales) . ')'
                        )
                    ),
                    'may_terminate' => false,
                    'child_routes' => $config['router']['routes']
                )
            );
        }

        return $config;
    }
}
