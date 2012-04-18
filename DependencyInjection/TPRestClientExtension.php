<?php

namespace TP\Bundle\TPRestClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * DIC extension
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class TPRestClientExtension extends Extension
{
    /**
     * Todo, read config
     * -- app/config/config.yml
     * 
     * tp_rest_client:
     *   base_url: "http://api.domain.tld"
     *   default_format: "json"
     *   headers:
     *     - "X-Api-Version: 1"
     */
}