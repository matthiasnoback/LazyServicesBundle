# LazyServicesBundle

By Matthias Noback

[![Build Status](https://secure.travis-ci.org/matthiasnoback/LazyServicesBundle.png)](http://travis-ci.org/matthiasnoback/LazyServicesBundle)

## Installation

Run:

    php composer.phar require matthiasnoback/lazy-services-bundle 0.2.*

Then register the bundle in ``/app/AppKernel.php``:

    <?php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new Matthias\LazyServicesBundle\MatthiasLazyServicesBundle,
            );
        }
    }

## Usage

### Lazy arguments

This bundle allows you to mark services as [lazy](http://symfony.com/doc/current/components/dependency_injection/lazy_services.html)
by adding tags to **arguments** that refer to them:

    <service id="some_service" class="...">
        <argument type="service" id="mailer" key="mailer" />
        <tag name="lazy_argument" key="mailer" />
    </service>

The argument key can be a *string* (like in the example above), or a *number*, indicating
the index of the argument (starting with 0):

    <service id="some_service" class="...">
        <argument type="service" id="event_dispatcher" /><!-- key is 0 -->
        <argument type="service" id="mailer" /><!-- key is 1 -->
        <tag name="lazy_argument" key="1" />
    </service>

Both examples will effectively convert the ``mailer`` service to a lazy-loading
service.

When the referenced service does not exist, it will be skipped silently.

### Lazy services by configuration

For your convenience, this bundle also allows you to mark services as lazy by adding its service id
to a list of lazy services in your application configuration:

    # in config.yml:

    matthias_lazy_services:
        lazy_service_ids:
            - mailer
            - ...

When the referenced service does not exist, it will be skipped silently.
