<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use const Choredo\HEX_COLOR_REGEX;

/**
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

    const API_ENTITY_TYPE = 'children';

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
     * @ORM\Column(type="string", nullable=true, length=7, options={"fixed": true})
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=6, name="access_code", options={"fixed": true})
     */
    private $accessCode;

    /**
     * @ORM\OneToMany(targetEntity="Choredo\Entities\Assignment", mappedBy="child")
     */
    private $assignments;

    /**
     * Child constructor.
     *
     * @param UuidInterface $id
     * @param Family        $family
     * @param string        $name
     * @param string        $avatarUri
     * @param string        $color
     * @param string        $accessCode
     */
    public function __construct(
        UuidInterface $id,
        Family $family,
        string $name,
        string $avatarUri = null,
        string $color = null,
        string $accessCode = null
    ) {
        $this->id     = $id;
        $this->family = $family;
        $this->name   = $name;
        $this->setAvatarUriAndColor($avatarUri, $color);
        $this->accessCode  = $accessCode;
        $this->assignments = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAssignments(): ArrayCollection
    {
        return $this->assignments;
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
    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }

    /**
     * @return string
     */
    public function getAvatarUri(): ?string
    {
        return $this->avatarUri;
    }

    /**
     * @return string
     */
    public function getColor(): ?string
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
                HEX_COLOR_REGEX,
                'Child::color must be a valid hexadecimal color code'
            );
            $this->color = $color;
        }
    }
}
