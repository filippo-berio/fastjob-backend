<?php

namespace App\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
class DirectChat
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['chat_short'])]
    protected int $id;

    /** @var Collection<DirectMessage> */
    #[OneToMany(mappedBy: 'chat', targetEntity: DirectMessage::class)]
    protected Collection $messages;

    #[ManyToOne(targetEntity: PersonInterface::class)]
    #[Groups(['chat_short'])]
    protected PersonInterface $personA;

    #[ManyToOne(targetEntity: PersonInterface::class)]
    #[Groups(['chat_short'])]
    protected PersonInterface $personB;

    public function __construct(
        PersonInterface $personA,
        PersonInterface $personB,
    ) {
        $this->personA = $personA;
        $this->personB = $personB;
        $this->messages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DirectMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    public function getPersonA(): PersonInterface
    {
        return $this->personA;
    }

    public function getPersonB(): PersonInterface
    {
        return $this->personB;
    }

    public function getCompanionOf(PersonInterface $person): PersonInterface
    {
        return $person->getId() === $this->getPersonA()->getId() ?
            $this->getPersonB() :
            $this->getPersonA();
    }
}
