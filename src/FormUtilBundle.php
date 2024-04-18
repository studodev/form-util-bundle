<?php

namespace Studodev\FormUtilBundle;

use Studodev\FormUtilBundle\Form\Extension\ClientValidationExtension;
use Studodev\FormUtilBundle\Form\Extension\FileAcceptExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class FormUtilBundle extends AbstractBundle
{
    public const CONFIG_KEY_CLIENT_VALIDATION = 'disable_client_validation';
    public const CONFIG_KEY_ACCEPT_ATTRIBUTE = 'enable_constraint_based_accept_attribute';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->booleanNode(self::CONFIG_KEY_CLIENT_VALIDATION)->defaultFalse()->end()
                ->booleanNode(self::CONFIG_KEY_ACCEPT_ATTRIBUTE)->defaultFalse()->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->services()->get(ClientValidationExtension::class)
            ->arg(0, $config[self::CONFIG_KEY_CLIENT_VALIDATION])
        ;

        $container->services()->get(FileAcceptExtension::class)
            ->arg(0, $config[self::CONFIG_KEY_ACCEPT_ATTRIBUTE])
        ;
    }
}
