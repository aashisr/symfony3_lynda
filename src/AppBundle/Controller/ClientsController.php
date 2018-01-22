<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;

class ClientsController extends Controller
{

    private $titles = ['mr', 'ms', 'mrs', 'dr', 'mx'];

    /**
     * @Route("/guests", name = "index_clients")
     */
    public function showIndex(){
        $data = [];
        //$data['clients'] = $this->client_data;
        //Get all data from database
        $clients = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->findAll();
        $data['clients'] = $clients;

        return $this->render('clients/index.html.twig', $data);
    }

    /**
     * @Route("/guests/modify/{id_client}", name = "modify_clients")
     */
    public function showDetails(Request $request, $id_client)
    {
        $data = [];
        //$data['clients'] = $this->client_data;
        //Get data from repository or database
        $client_repo = $this->getDoctrine()
                            ->getRepository('AppBundle:Client');

        $data['mode'] = 'modify';
        //$data['form'] = [];

        $data['titles'] = $this->titles;

        $form = $this->createFormBuilder()
                     ->add('name')
                     ->add('last_name')
                     ->add('title')
                     ->add('address')
                     ->add('zip_code')
                     ->add('city')
                     ->add('state')
                     ->add('email')
                     ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            //Find client data to be updated by id
            $client = $client_repo->find($id_client);

            //Set values to be updated
            $client->setTitle($form_data['title']);
            $client->setName($form_data['name']);
            $client->setLastName($form_data['last_name']);
            $client->setAddress($form_data['address']);
            $client->setZipCode($form_data['zip_code']);
            $client->setCity($form_data['city']);
            $client->setState($form_data['state']);
            $client->setEmail($form_data['email']);

            $em = $this->getDoctrine()
                        ->getManager();
            $em->flush();

            return $this->redirectToRoute('index_clients');

        } else {
            //$client_data = $this->client_data[$id_client];
            //Find client by id
            $client = $client_repo->find($id_client);
            //Get client data
            $client_data['id'] = $client->getId();
            $client_data['title'] = $client->getTitle();
            $client_data['name'] = $client->getName();
            $client_data['last_name'] = $client->getLastName();
            $client_data['address'] = $client->getAddress();
            $client_data['zip_code'] = $client->getZipCode();
            $client_data['city'] = $client->getCity();
            $client_data['state'] = $client->getState();
            $client_data['email'] = $client->getEmail();
            //Get titles from titles array above
            $client_data['titles'] = $this->titles;

            $data['form'] = $client_data;
        }

        return $this->render('clients/form.html.twig', $data);
    }

    /**
     * @Route("/guests/new", name = "new_client")
     */
    public function showNew(Request $request)
    {
        $data = [];
        $data['mode'] = 'new_client';
        $data['titles'] = $this->titles;
        $data['form']['title'] = '';

        $form = $this->createFormBuilder()
            ->add('name')
            ->add('last_name')
            ->add('title')
            ->add('address')
            ->add('zip_code')
            ->add('city')
            ->add('state')
            ->add('email')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            //Create doctrine manager
            $em = $this->getDoctrine()->getManager();
            //Create client instance
            $client = new Client();
            //create all client attributes

            $client->setTitle($form_data['title']);
            $client->setName($form_data['name']);
            $client->setLastName($form_data['last_name']);
            $client->setAddress($form_data['address']);
            $client->setZipCode($form_data['zip_code']);
            $client->setCity($form_data['city']);
            $client->setState($form_data['state']);
            $client->setEmail($form_data['email']);

            //Persist(save) data with manager
            $em->persist($client);
            //Create and execute query
            $em->flush();
            
            //redirect to show clients page
            return $this->redirectToRoute('index_clients');
        }

        return $this->render('clients/form.html.twig', $data);
    }
}
