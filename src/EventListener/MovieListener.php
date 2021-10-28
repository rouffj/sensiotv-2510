<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieListener
{
    public function postPersist(LifecycleEventArgs $event): void
    {
        $movie = $event->getObject();

        $peopleWantedNotificationOnMovieImport = ['joseph3@joseph.io', 'tom@gmail.com'];

        $email = [
            'from' => 'team@sensiotv.io',
            'to' => implode(',', $peopleWantedNotificationOnMovieImport),
            'subject' => sprintf('Nice, the movie %s has been imported, watch it now !', $movie->getTitle()),
            'body' => sprintf('Hello there, Watch it by going to this url: %s/movie/%s ', $_SERVER['HTTP_HOST'], $movie->getId()),
        ];

        dump($email);
        // Put your logic here before or after a DB change occured inside an entity like:
        // - Log column change
        // - Send a notification / email each time an entity is added into DB
        // - ...
    }
}

