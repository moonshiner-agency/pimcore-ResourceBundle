<?php
namespace Moonshiner\BrigthenBundle\Security\User;

use Pimcore\Model\User as PimcoreUser;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface as GoogleTwoFactorInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Proxy user to pimcore model and expose roles as ROLE_* array. If we can safely change the roles on the user model
 * this proxy can be removed and the UserInterface can directly be implemented on the model.
 */
class SecureUser implements UserInterface, EquatableInterface, GoogleTwoFactorInterface
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->user->getId();
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->user->getEmail();
    }

    public function getName()
    {
        return $this->user->getEmail();
    }

    public function save()
    {
        return;
    }

    public function setPassword($pass = null)
    {
        return;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCustomer()
    {
        return $this->user;
    }

    public function getClass()
    {
        return $this->user->getClass();
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo( $user )
    {
        return true;
        return $user instanceof self && $user->getId() === $this->getId();
    }

    /**
     * Return true if the user should do two-factor authentication.
     *
     * @return bool
     */
    public function isGoogleAuthenticatorEnabled(): bool
    {
        return false;
    }

    /**
     * Return the user name.
     *
     * @return string
     */
    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->user->getName();
    }

    /**
     * Return the Google Authenticator secret
     * When an empty string or null is returned, the Google authentication is disabled.
     *
     * @return string
     */
    public function getGoogleAuthenticatorSecret(): string
    {
        return '';
    }
}
