<?php

namespace Moonshiner\BrigthenBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use CustomerManagementFrameworkBundle\CustomerProvider\CustomerProviderInterface;


class CustomerProvider implements UserProviderInterface
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    public function __construct(CustomerProviderInterface $customerProvider)
    {
        $this->customerProvider = $customerProvider;

    }

    public function loadUserByUsername($email)
    {
        $customer = $this->customerProvider->getActiveCustomerByEmail($email);
        if ($customer) {
            return new SecureUser($customer);
        }

        throw new UsernameNotFoundException(sprintf('User %s was not found', $customer));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof CustomerInterface) {
            throw new UnsupportedUserException();
        }

        return $this->customerRepository->findCustomerByEmail($user->getEmail());
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, CustomerInterface::class);
    }
}
