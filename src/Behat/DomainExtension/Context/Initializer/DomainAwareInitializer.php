<?php

namespace Behat\DomainExtension\Context\Initializer;

use Behat\DomainExtension\Context\DomainAwareInterface;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\Context\Context;

class DomainAwareInitializer implements ContextInitializer
{
    private $domains;

    public function __construct($domains)
    {
        $this->domains = $domains;
    }

    /**
     * {@inheritdocs}
     */
    public function initializeContext(Context $context)
    {
        // All contexts are passed here, only DomainAwareInterface is allowed.
        if (!$context instanceof DomainAwareInterface) {
            return;
        }

        $context->setDomains($this->domains);
    }

}
