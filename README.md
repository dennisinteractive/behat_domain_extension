# Behat Domain Extension

The Domain Extension is an integration layer for behat that allows control across multiple domains.
It provides common step definitions for testing domain based features.

## Why?

When testing sites which have multiple domains configured or when testing across multiple domains we need a way to tell Mink which domain we want to run our scenarios against.

# How?

By default Mink supports a single base url that it will prepend all relative urls to. The domain extension allows us to switch this base url in our Scenarios depending on the domain we're testing by using one of the following step definitions:

```
Given I am on the domain "Example"
```
```
And I am on the domain "Example"
```
```
When I go to the domain "Example"
```

From this point on, all the steps we run in the given Scenario will be tested against this new domain.

It also allows the use of aliases for domains, as can be seen above, so that we don't need to refer to domains directly. These can be configured in the Domain Extension config as follows:

```
default:
  extensions:
    Behat\DomainExtension:
      domain_map:
        "Example": 'http://example.com'
```

### Dependencies:
- Behat
- Symfony DPI
- Mink

### Notes

Mink override approach taken from - https://github.com/Behat/MinkExtension/issues/155