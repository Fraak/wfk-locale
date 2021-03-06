<?php

namespace WfkLocale\Service;

use WfkLocale\Options\ModuleOptions;
use Zend\Console\Console;
use WfkLocale\Mvc\Route\LocaleRouteStack;
use Zend\Mvc\Router\Http\Segment;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\RouterFactory as ZendRouterFactory;
use Zend\Mvc\Router\Http\RouteInterface as HttpRoute;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 */
class RouterFactory extends ZendRouterFactory
{
	/**
	 * Rewrite router config to prepend a language part route
	 *
	 * @param  ServiceLocatorInterface $serviceLocator
	 * @return array|\Traversable
	 */
	public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
	{
		$originalRouter = parent::createService($serviceLocator, $cName, $rName);

		/** @var $options ModuleOptions */
		$options = $serviceLocator->get('wfklocale-options');
		if ($options->getMode() == ModuleOptions::MODE_URLKEY)
		{
			return new LocaleRouteStack($originalRouter, $serviceLocator->get('wfklocale-options'));
		}

		return $originalRouter;
	}
}