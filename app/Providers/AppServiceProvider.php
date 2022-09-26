<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $fullDomain = $this->app->request->server->all()["HTTP_HOST"];
        if(isset($fullDomain)){
           $subdomain = explode('.', $fullDomain)[0];
           if($subdomain != 'agencydashboard'){
              User::check_subdomain($subdomain);
          }
      }        
  }
}
