<?php

namespace Behat\DomainExtension\Context;

use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Provides pre-built step definitions for interacting with multiple domains.
 */
class DomainContext extends RawMinkContext implements DomainAwareInterface {

  private $domains;

  /**
   * {@inheritdoc}
   */
  public function setDomains(array $domains) {
    $this->domains = $domains;
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
      $this->visitPath($domains[$domain]);
      return;
    }
    throw new \Exception(sprintf('The domain "%s" has not been configured', $domain));
  }
}
