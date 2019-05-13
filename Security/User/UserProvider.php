<?php

namespace Moonshiner\BrigthenBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Pimcore\Bundle\AdminBundle\Security\User\User as SecurityUser;
use Pimcore\Model\User;


class UserProvider implements UserProviderInterface
{

    public function loadUserByUsername($usernameOrEmail)
    {

        $user = User::getByName($usernameOrEmail);
        if ($user) {
            return new SecurityUser($user);
        }

        throw new UsernameNotFoundException(sprintf('User %s was not found', $usernameOrEmail));
    }

    public function refreshUser(UserInterface $user)
    {
        return new SecurityUser($user);
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, SecurityUser::class);
    }
}
