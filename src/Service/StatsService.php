<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats(){
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getbookingsCount();
        $comments = $this->getcommentsCount();

        return compact('users','ads','bookings','comments');
    }

    public function getUsersCount(){
       return $this->manager->createQuery('SELECT COUNT(u) FROM APP\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM APP\Entity\Ad a')->getSingleScalarResult();
     }

    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b) FROM APP\Entity\Booking b')->getSingleScalarResult();
     }

    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM APP\Entity\Comment c')->getSingleScalarResult();
     }


    public function getBestAds(){
       return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN c.author u
            GROUP BY a
            ORDER BY note DESC'
        )
        ->setMaxResults(5)
        ->getResult(); 
    }

    
    public function getBadAds(){
        return $this->manager->createQuery(
             'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
             FROM App\Entity\Comment c
             JOIN c.ad a
             JOIN c.author u
             GROUP BY a
             ORDER BY note ASC'
         )
         ->setMaxResults(5)
         ->getResult(); 
     }
}