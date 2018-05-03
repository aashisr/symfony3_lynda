<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Reservation;

class AdminController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function showIndex(){
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/createReservation", name="createReservation")
     */
    public function createReservation(Request $request) {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation, array(
            'em' => $this->getDoctrine()->getEntityManager()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData()); die();
        }

        return $this->render(':reservations:clientReservation.html.twig', array(
           'form' => $form->createView()
        ));
    }
}
