<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Task.Paymenthistoryclean
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Factory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Task\Paymenthistoryclean\Extension\Paymenthistoryclean;

return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   4.2.0
     */
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new Paymenthistoryclean(
									$container->get(DispatcherInterface::class),
                  (array) PluginHelper::getPlugin('task', 'paymenthistoryclean')
                );
                $plugin->setApplication(Factory::getApplication());
								$plugin->setDatabase($container->get(DatabaseInterface::class));

                return $plugin;
            }
        );
    }
};
