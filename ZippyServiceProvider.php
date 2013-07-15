<?php

namespace Zippy;

use Alchemy\Zippy\Zippy;
use Alchemy\Zippy\Adapter\AdapterContainer;
/**
* Zippy service provider for Silex
*
* = Parameters:
* zippy.adapters: (optional) adapters modifier, call it before the main zippy service
* zippy.class: (optional) override the default zippy class name (usage: extends)
*
* = Services:
* Zippy: A new Zippy object, loaded with the zippy.adapters
*
* @author Arnaud LEMAIRE <alemaire@quantis.fr>
*/
class ZippyServiceProvider implements ServiceProviderInterface
{
    /**
    * Register Zippy with Silex
    *
    * @param Application $app Application to register with
    */
    public function register(Application $app)
    {
        // Register a Zippy ServiceBuilder
        $app['zippy'] = function ($app) {   
            $factory = new $app['zippy.class']($app['zippy.adapters']);

            $factory->addStrategy(new ZipFileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TarFileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TarGzFileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TarBz2FileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TB2FileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TBz2FileStrategy($app['zippy.adapters']));
            $factory->addStrategy(new TGzFileStrategy($app['zippy.adapters']));

            return $factory;
        };

        // Register the adapters configuration, if you want to modify an adapter, call it before $app['zippy'];
        $app['zippy.adapters'] = $app->share(function($c) {
            return AdapterContainer::load();
        });
        
        //If you want to override default zippy class name (or extend it)
        $app['zippy.class'] = 'Zippy';
    }

    public function boot(Application $app)
    {
    }
}