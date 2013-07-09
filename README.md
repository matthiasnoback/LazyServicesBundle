# LazyServicesBundle

By Matthias Noback

This bundle allows you to mark services as [lazy](http://symfony.com/doc/current/components/dependency_injection/lazy_services.html)
by adding tags to arguments that refer to them:

    <service id="some_service" class="...">
        <argument type="service" id="mailer" key="mailer" />
        <tag name="lazy_argument" key="mailer" />
    </service>

The argument key can be a string (like in the example above), or a number, indicating
the index of the argument (starting with 0):

    <service id="some_service" class="...">
        <argument type="service" id="event_dispatcher" /><!-- key is 0 -->
        <argument type="service" id="mailer" /><!-- key is 1 -->
        <tag name="lazy_argument" key="1" />
    </service>

Both examples will effectively convert the ``mailer`` service to a lazy-loading
service.

## Installation

Run:

    php composer.phar require matthiasnoback/lazy-services-bundle 0.1.*

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
