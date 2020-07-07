<?php
namespace Mailketing\MailketingLaravelDriver;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->register(MailketingTransportServiceProvider::class);
    }
}
