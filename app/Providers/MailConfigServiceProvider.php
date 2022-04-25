<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\StaticPage;
use App\Models\SiteSetting;
use View;
use Cache;
use DB;
use Config;

class MailConfigServiceProvider extends ServiceProvider
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
    public function boot()
    {
        if (\Schema::hasTable('site_settings')){

            $mailSettingInfo = DB::table('site_settings')->where(['row_status'=>1])->first();
            //dd( count($mailSettingInfo));
            if ($mailSettingInfo) {
                $mailConfig = [
                    'transport' => $mailSettingInfo->mailer,
                    'host' => $mailSettingInfo->host,
                    'port' => $mailSettingInfo->port,
                    'encryption' => $mailSettingInfo->encryption,
                    'username' => $mailSettingInfo->username,
                    'password' => $mailSettingInfo->password,
                    'timeout' => null
                ];
        
                //To set configuration values at runtime, pass an array to the config helper
                config(['mail.mailers.smtp' => $mailConfig]);
        
                putenv('MAIL_MAILER='.$mailSettingInfo->mailer);
                putenv('MAIL_HOST='.$mailSettingInfo->host);
                putenv('MAIL_PORT='.$mailSettingInfo->port);
                putenv('MAIL_USERNAME='.$mailSettingInfo->username);
                putenv('MAIL_PASSWORD='.$mailSettingInfo->password);
                putenv('MAIL_ENCRYPTION='.$mailSettingInfo->encryption);
            }
        }
    }
}
