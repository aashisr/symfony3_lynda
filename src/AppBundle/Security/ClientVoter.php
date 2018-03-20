<?php
/**
 * Created by PhpStorm.
 * User: Aashis
 * Date: 3/20/2018
 * Time: 8:00 PM
 */

namespace AppBundle\Security;

use AppBundle\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClientVoter extends Voter {

    const EDIT = 'edit';
    const VIEW = 'view';

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, array(self::VIEW, self::EDIT))){
            return false;
        }

        if (!$subject instanceOf Client){
            return false;
        }

        error_log("supports returns true");
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $clients = [1,2];
        $client = $subject;
        $clientId = $client->getId();

        if(in_array($clientId,$clients)){
            return true;
        }

        return false;

    }
}