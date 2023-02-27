<?php

namespace Behat\DomainExtension\Context;

use Behat\Behat\Context\TranslatableContext;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Provides pre-built step definitions for interacting with multiple domains.
 */
class DomainContext extends RawMinkContext implements DomainAwareInterface, TranslatableContext
{

    private $domains;
    private $domainUrl;

    /**
     * {@inheritdoc}
     */
    public function setDomains(array $domains)
    {
        $this->domains = $domains;
    }

    /**
     * {@inheritdoc}
     */
    public function setDomainUrl($url)
    {
        $this->domainUrl = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getDomains()
    {
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
    public function getDomainUrl()
    {
        return $this->domainUrl;
    }

    /**
     * Returns list of definition translation resources paths
     *
     * @return array
     */
    public static function getTranslationResources()
    {
        return glob(__DIR__.'/../../../../i18n/*.xliff');
    }

    /**
     * Change the Mink Extension base url to the domain url
     * to allow Mink Contexts to read our domain url.
     *
     * @BeforeStep
     */
    public function gatherContexts(BeforeStepScope $scope)
    {
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
    public function assertValidDomain($domain)
    {
        $domains = $this->getDomains();

        if (!empty($domains) && isset($domains[$domain])) {
            $this->setDomainUrl($domains[$domain]);
            $this->visitPath($this->getDomainUrl());
            print_r('visiting - ' . $this->getDomainUrl());
            return;
        }
        throw new \Exception(sprintf('The domain "%s" has not been configured', $domain));
    }

    /**
     * Resets to and opens the default url given by the Mink base_url parameter.
     *
     * @see The Mink base_url parameter.
     *
     * @Given I am on the default domain
     * @When I go to the default domain
     */
    public function assertDefaultDomain()
    {
        $base_url = $this->getMinkParameter('base_url');

        if (!empty($base_url)) {
            $this->setDomainUrl($base_url);
            $this->visitPath($this->getDomainUrl());
            return;
        }
        throw new \Exception('The Mink base-url has not been configured');
    }
}
