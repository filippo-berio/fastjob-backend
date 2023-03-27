<?php

namespace App\Chat\Entity;

use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Entity]
class DirectMessage
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    protected int $id;

    #[Column]
    #[Encrypted]
    protected string $content;

    #[Column]
    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => DateTime::ATOM])]
    #[Groups(['task_full'])]
    protected DateTimeImmutable $createdAt;

    #[ManyToOne]
    protected PersonInterface $author;

    #[Column(type: 'smallint')]
    protected bool $read;

    #[ManyToOne]
    protected DirectChat $chat;

    public function __construct(
        DirectChat $chat,
        string $content,
        PersonInterface $author
    ) {
        $this->content = $content;
        $this->createdAt = new DateTimeImmutable();
        $this->read = false;
        $this->chat = $chat;
        $this->author = $author;
    }

    public function read()
    {
        $this->read = true;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAuthor(): PersonInterface
    {
        return $this->author;
    }
}
