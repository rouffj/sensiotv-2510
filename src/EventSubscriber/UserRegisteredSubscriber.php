<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\UserRegisteredEvent;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $accountConfirmationEmail = [
            'from' => 'team@sensiotv.io',
            'to' => $user->getEmail(),
            'subject' => $user->getFirstName().', Your are is created !',
            'body' => 'Hi ' .$user->getFirstName().', Happy to see you on sensiotv ;)',
        ];

        dump($accountConfirmationEmail);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}
