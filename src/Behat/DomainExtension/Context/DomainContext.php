<?php

namespace Behat\DomainExtension\Context;

use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Provides pre-built step definitions for interacting with multiple domains.
 */
class DomainContext extends RawMinkContext implements DomainAwareInterface {

  private $domains;
  private $domain_url;

  /**
   * {@inheritdoc}
   */
  public function setDomains(array $domains) {
    $this->domains = $domains;
  }

  /**
   * {@inheritdoc}
   */
  public function setDomainUrl($url) {
    $this->domain_url = $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getDomains() {
    if (null === $this->domains) {
      throw new \RuntimeException(
        'Trying to use the domain context with no configured domains.'
      );
    }

    return $this->domains;
  }

  /**
   * {@inheritdoc}
   */
  public function getDomainUrl() {
    return $this->domain_url;
  }

  /**
   * Change the Mink Extension base url to the domain url
   * to allow Mink Contexts to read our domain url.
   *
   * @BeforeStep
   */
  public function gatherContexts(BeforeStepScope $scope) {
    $environment = $scope->getEnvironment();

    foreach ($environment->getContexts() as $context) {
      if ($context instanceof RawMinkContext && !empty($this->getDomainUrl())) {
        $context->setMinkParameter('base_url', $this->getDomainUrl());
      }
    }
  }

  /**
   * Opens domain given it's alias.
   * Example: Given I am on the domain "Example"
   * Example: And I am on the domain "Example"
   * Example: When I go to the domain "Example"
   *
   * @see The domain_map parameter for configuring domain aliases.
   *
   * @Given I am on the domain :domain
   * @When I go to the domain :domain
   */
  public function assertValidDomain($domain) {
    $domains = $this->getDomains();

    if (!empty($domains) && isset($domains[$domain])) {
      $this->setDomainUrl($domains[$domain]);
      $this->visitPath($domains[$domain]);
      return;
    }
    throw new \Exception(sprintf('The domain "%s" has not been configured', $domain));
  }
}
