<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Model\User;

class UserFactory
{
    public static function createUserFromArray($arr)
    {
        self::validateUserArray($arr);

        $user = new User();
        $user->setLendmnId($arr['userId']);
        $user->setFirstName($arr['firstName']);
        $user->setLastName($arr['lastName']);
        $user->setPhoneNumber($arr['phoneNumber']);
        /* @todo update documentation no email */
        // $user->setEmail($arr['email']);

        return $user;
    }

    private static function validateUserArray($arr): void
    {
        Factory::checkKeysOfArray($arr, ['userId', 'firstName', 'lastName', 'phoneNumber']);
    }
}
