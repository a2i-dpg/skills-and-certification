<?php

namespace App\Providers;

use App\Models\Header;
use App\Models\Institute;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

use App\Models\StaticPage;
use App\Models\SiteSetting;
use View;
use Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadHelpers();

        $this->app->singleton('currentInstitute', static function ($app) {
            $currentInstituteSlug = $app->request->input('current_institute_slug');
            $currentInstitute = null;

            if ($currentInstituteSlug) {
                $currentInstitute = Institute::where('slug', $currentInstituteSlug)->first();

                if (!$currentInstitute) {
                    abort('404', 'Not found');
                }
            }

            return $currentInstitute;
        });

        /** dynamic headers fetching and binding with app instance */

        $this->app->singleton('navHeaders', static function ($app) {
            $currentInstituteSlug = $app->request->input('current_institute_slug');
            $currentInstitute = null;
            $headers = [];

            if ($currentInstituteSlug) {
                $currentInstitute = Institute::where('slug', $currentInstituteSlug)->first();

                if (!$currentInstitute) {
                    $headers =  Header::whereNull('institute_id')->get();
                }else {
                    $headers = Header::where('institute_id', $currentInstitute->id)->get();
                }
            }

            return $headers;
        });
        

        View::composer('*', function ($view) {
            $staticPageFooter = Cache::rememberForever('staticPageFooter', function () {
                return StaticPage::where(['row_status'=>1])->get();
            });
            $view->with('staticPageFooter', $staticPageFooter);
        });

        View::composer('*', function ($view) {
            $siteSettingInfo = Cache::rememberForever('siteSettingInfo', function () {
                return SiteSetting::where(['row_status'=>1])->first();
            });
            $view->with('siteSettingInfo', $siteSettingInfo);
        });


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if(App::environment('production')) {
            $url->forceScheme('https');
        }

        $this->loadViewsFrom(resource_path('views'), 'master');
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(app_path('Helpers/functions') . DIRECTORY_SEPARATOR . '*.php') as $filename) {
            require_once $filename;
        }
    }
}
