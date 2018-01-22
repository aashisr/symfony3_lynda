<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reservation;

class ReservationsController extends Controller
{
    /**
     * @Route("/reservations", name = "reservations")
     */
    public function showIndex(){
        return $this->render('reservations/index.html.twig');
    }

    /**
     * @Route("/reservation/{id_client}", name = "booking")
     */
    public function book(Request $request, $id_client){
        $data['id_client'] = $id_client;
        return $this->render("reservations/book.html.twig", $data);
    }

    /**
     * @Route("/book_room/{id_client}/{id_room}/{date_in}/{date_out}", name = "book_room")
     */
    public function bookRoom($id_client, $id_room, $date_in, $date_out){
        //Create new reservation instance
        $reservation = new Reservation();

        //get dates, symfony needs dates as objects not as just string
        $date_start = new \DateTime($date_in);
        $date_end = new \DateTime($date_out);
        $reservation->setDateIn($date_start);
        $reservation->setDateOut($date_end);

        //get current client
        $client = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id_client);

        //get current room
        $room = $this->getDoctrine()
            ->getRepository('AppBundle:Room')
            ->find($id_room);

        $em = $this->getDoctrine()->getManager();

        //Add client and room to reservation
        $reservation->setClient($client);
        $reservation->setRoom($room);

        //Persist and flush
        $em->persist($reservation);
        $em->flush();

        //Redirect
        return $this->redirectToRoute('index_clients');
    }
}
