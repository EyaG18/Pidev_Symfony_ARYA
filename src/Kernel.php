<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/packages/cache.yaml');
        $loader->load($confDir.'/packages/debug.yaml');
        $loader->load($confDir.'/packages/doctrine_migrations.yaml');
        $loader->load($confDir.'/packages/doctrine.yaml');
        $loader->load($confDir.'/packages/framework.yaml');
        $loader->load($confDir.'/packages/mailer.yaml');
        $loader->load($confDir.'/packages/messenger.yaml');
        $loader->load($confDir.'/packages/monolog.yaml');
        $loader->load($confDir.'/packages/notifier.yaml');
        $loader->load($confDir.'/packages/routing.yaml');
        $loader->load($confDir.'/packages/security.yaml');
        $loader->load($confDir.'/packages/translation.yaml');
        $loader->load($confDir.'/packages/twig.yaml');
        $loader->load($confDir.'/packages/validator.yaml');
        $loader->load($confDir.'/packages/web_profiler.yaml');
        $loader->load($confDir.'/services.yaml');
    }















}
