<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\EnrollEmailEvent;
use App\Listeners\EnrollEmailListener;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Notifications\SendEmailVerificationNotification as QueuedSendEmailVerificationNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EnrollEmailEvent::class => [
            EnrollEmailListener::class,
        ],
        Registered::class => [
            //SendEmailVerificationNotification::class,
            QueuedSendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\Keycloak\KeycloakExtendSocialite::class.'@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
