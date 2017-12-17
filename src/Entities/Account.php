<?php

namespace Choredo\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @package Choredo\Entities
 *
 * @ORM\Entity(repositoryClass="Choredo\Repositories\AccountRepository")
 * @ORM\Table(name="accounts")
 * @ORM\HasLifeCycleCallbacks
 */
class Account
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    const API_ENTITY_TYPE = 'accounts';

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     name ="email_address"
     * )
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     name ="first_name"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     name ="last_name"
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     name ="avatar_uri"
     * )
     */
    private $avatarUri;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(
     *     type="datetime",
     *     name ="last_login"
     * )
     */
    private $lastLogin;

    /**
     * Account constructor.
     * @param UuidInterface $id
     * @param string $emailAddress
     * @param string $firstName
     * @param string $lastName
     * @param string $avatarUri
     * @param string $token
     * @param \DateTime $lastLogin
     * @param Family $family
     */
    public function __construct(
        UuidInterface $id,
        string $emailAddress,
        string $firstName,
        string $lastName,
        string $avatarUri,
        string $token,
        \DateTime $lastLogin,
        Family $family
    ) {
        $this->id = $id;
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->avatarUri = $avatarUri;
        $this->token = $token;
        $this->lastLogin = $lastLogin;
        $this->family = $family;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getAvatarUri(): string
    {
        return $this->avatarUri;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): \DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @return Family
     */
    public function getFamily(): Family
    {
        return $this->family;
    }
}
