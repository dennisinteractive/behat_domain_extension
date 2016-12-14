<?php

namespace Behat\DomainExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DomainExtension implements ExtensionInterface {

  /**
   * Extension configuration ID.
   */
  const DOMAIN_ID = 'domain';

  /**
   * {@inheritDoc}
   */
  public function getConfigKey() {
    return self::DOMAIN_ID;
  }

  /**
   * {@inheritDoc}
   */
  public function initialize(ExtensionManager $extensionManager) {
  }

  /**
   * {@inheritDoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
    $loader->load('services.yml');

    $this->loadParameters($container, $config);
    $this->loadContextInitializer($container);
  }

  /**
   * {@inheritDoc}
   */
  public function process(ContainerBuilder $container) {
  }

  /**
   * {@inheritDoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder->
      children()->
        arrayNode('domain_map')->
          info("Targeting specific domains can be accomplished once they have been defined." . PHP_EOL
            . '  Wikipedia: "https://en.wikipedia.org"' . PHP_EOL
            . '  Weather dotcom: "https://weather.com/en-GB"'. PHP_EOL
          )->
          useAttributeAsKey('key')->
          prototype('variable')->
          end()->
        end()->
      end()->
    end();
  }

  private function loadContextInitializer(ContainerBuilder $container) {
    $definition = new Definition('Behat\DomainExtension\Context\Initializer\DomainAwareInitializer', array(
      '%domain.domain_map%',
    ));
    $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
    $container->setDefinition(self::DOMAIN_ID.'.context_initializer', $definition);
  }

  /**
   * Load test parameters.
   */
  private function loadParameters(ContainerBuilder $container, array $config) {
    // Store config in parameters array to be passed into the DomainContext.
    $parameters = array();
    foreach ($config as $key => $value) {
      $parameters[$key] = $value;
    }
    $container->setParameter('domain.parameters', $parameters);
    $container->setParameter('domain.domain_map', $parameters['domain_map']);
  }
}
