<?php
/**
 * Created by PhpStorm.
 * User: Aashis
 * Date: 4/26/2018
 * Time: 5:10 PM
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Client;

class ReservationType extends AbstractType
{
/*    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }*/

    /**
     * This form is just for learning to create dynamic forms,
     * does not contain any business logic
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('client', EntityType::class, array(
            'class' => 'AppBundle\Entity\Client',
            'placeholder' => 'Choose client',
            'choice_label' => 'name'
        ));

        $formModifier = function (FormInterface $form, Client $client = null) use ($options) {
            $em = $options['em'];
            $allRooms = $em->getRepository('AppBundle:Room')->findAll();
            $roomsByClient = $em->getRepository('AppBundle:Room')->findRoomsByClient($client);

            if ($client === null){
                $rooms = $allRooms;
            } else {
                $rooms = $roomsByClient;
            }

            $form->add('room', EntityType::class, array(
                'class' => 'AppBundle\Entity\Room',
                'placeholder' => 'Choose room',
                'choices' => $rooms,
                'choice_label' => 'name',
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {

                //Entity i.e. Reservation
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getClient());
            }
        );

        $builder->get('client')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $client = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $client);
            }
        );

        $builder->add('save',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Reservation::class,
            'em' => null
        ));
    }
}