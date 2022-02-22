<?php
namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * @var Notification $notifications
     */
    protected $notifications = [];

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->notifications = Auth::check()
            ? Auth::user()->unreadNotifications()->limit(5)->get()
            : collect([]);
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('notifications', $this->notifications);
    }
}
