<?php

namespace Choredo\Entities;

use Assert\Assert;
use Choredo\Entities\Behaviors;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @package Choredo\Entities
 *
 * @ORM\Entity(repositoryClass="Choredo\Repositories\ChildRepository")
 * @ORM\Table(name="children")
 * @ORM\HasLifeCycleCallbacks
 */
class Child
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, name="avatar_uri")
     */
    private $avatarUri;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=7, options={"fixed" = true})
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, name="access_code")
     */
    private $accessCode;

    /**
     * Child constructor.
     * @param UuidInterface $id
     * @param Family $family
     * @param string $name
     * @param string $avatarUri
     * @param string $color
     * @param string $accessCode
     */
    public function __construct(
        UuidInterface $id,
        Family $family,
        string $name,
        string $avatarUri = null,
        string $color = null,
        string $accessCode = null
    ) {
        $this->id = $id;
        $this->family = $family;
        $this->name = $name;
        $this->setAvatarUriAndColor($avatarUri, $color);
        $this->accessCode = $accessCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAccessCode(): string
    {
        return $this->accessCode;
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
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string|null $avatarUri
     * @param string|null $color
     */
    private function setAvatarUriAndColor(string $avatarUri = null, string $color = null)
    {
        if (empty($avatarUri) && empty($color)) {
            throw new \InvalidArgumentException("Either 'avatarUri' or 'color' must be set");
        }

        if ($avatarUri) {
            Assert::that($avatarUri)->url();
            $this->avatarUri = $avatarUri;
        }

        if ($color) {
            Assert::that($color)->regex(
                '/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i',
                'Child::color must be a valid hexadecimal color code'
            );
            $this->color = $color;
        }
    }
}
