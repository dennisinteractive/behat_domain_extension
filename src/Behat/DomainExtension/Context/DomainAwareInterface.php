<?php

namespace Behat\DomainExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Mink\Mink;

interface DomainAwareInterface extends Context {

  /**
   * Sets the available domains.
   *
   * @param array $domains Domain list
   */
  public function setDomains(array $domains);

}
