<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AnnonceType;
use App\Form\AdminBookingType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo, $page , PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
        ->setPage($page);
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * 
     * @param Booking $ad
     * @return Response 
     */

    public function edit(Booking $booking, Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annonce <strong>{$booking->getId()} </strong> a bien été enregistré "
            );
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * 
     * @param Booking $booking
     * @param ObjetManager $manager
     * @return Response
     */
    public function delete(Booking $booking){
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($booking);
        $manager->flush();

        $this->addflash(
            'success',
            "La réservation a été suprimé"
        );
        return $this->redirectToRoute('admin_booking_index');
    }
}