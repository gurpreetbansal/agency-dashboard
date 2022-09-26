<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    
    /*public function boot()
    {
       View::composer('*', 'App\Http\ViewComposers\AgencyComposerViews');
    }*/

    public function boot()
    {
        //View::composer('*', 'App\Http\ViewComposers\AgencyComposer');
        // ['includes.vendor.header','includes.vendor.breadcrumb','includes.viewkey.breadcrumb'],

       // View::composer(
       //      ['includes.vendor.breadcrumb','includes.viewkey.breadcrumb'],
       //      'App\Http\ViewComposers\AgencyComposer'
       //  );

       View::composer(
            ['includes.vendor.breadcrumb','includes.viewkey.breadcrumb','includes.viewkey.sidebar'],
            'App\Http\ViewComposers\BreadCrumbComposer@compose'
        );

       View::composer(
            ['includes.viewkey.sa-breadcrumb','includes.vendor.audit-breadcrumb'],
            'App\Http\ViewComposers\AuditBreadCrumbComposer@compose'
        );

       View::composer(
            ['includes.vendor.header'],
            'App\Http\ViewComposers\HeaderComposer@compose'
        );

       View::composer(
            ['layouts.main_layout'],
            'App\Http\ViewComposers\FooterComposer@compose'
        );
    }
}
