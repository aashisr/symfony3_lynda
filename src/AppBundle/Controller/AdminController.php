<?php

namespace AppBundle\Controller;

require_once __DIR__ . '/../../../vendor/autoload.php';

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
            'em' => $this->getDoctrine()->getManager()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            echo "Submitted ";

            //Finds the php executable path
            //In this case, it is C:\xampp\php\php.exe
            $phpBinaryFinder = new PhpExecutableFinder();
            $phpBinaryPath = $phpBinaryFinder->find();

            //echo $phpBinaryPath;
            //echo __DIR__ . '/../../../Script/test.php';

            $process = new Process("{$phpBinaryPath} ". __DIR__ . '/../../../Script/test.php');

            $process->start();
            echo "Process started ";
            $process->wait();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();
            echo "Process finished ";

        }

        return $this->render(':reservations:clientReservation.html.twig', array(
           'form' => $form->createView()
        ));
    }
}
