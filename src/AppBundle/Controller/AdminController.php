<?php

namespace AppBundle\Controller;

require_once 'C:/xampp/htdocs/_symfony3_lynda/vendor/autoload.php';
//include_once 'C:/xampp/htdocs/_symfony3_lynda/Script/test.php';


use AppBundle\Form\Type\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Reservation;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
            echo "Submitted";

            $process = new Process('C:\xampp\php\php.exe C:/xampp/htdocs/_symfony3_lynda/Script/test.php');

            $process->run();

            /*try {
                $process->run();
                return  $process->getOutput();
            } catch (ProcessFailedException $e) {
                return $e->getMessage();
            }*/

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();

        }

        return $this->render(':reservations:clientReservation.html.twig', array(
           'form' => $form->createView()
        ));
    }
}
