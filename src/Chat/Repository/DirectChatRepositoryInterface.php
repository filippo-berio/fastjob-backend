<?php

namespace App\Chat\Repository;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\PersonInterface;

interface DirectChatRepositoryInterface
{
    /**
     * @param PersonInterface $person
     * @return DirectChat[]
     */
    public function getForPerson(PersonInterface $person): array;

    public function getForCompanions(PersonInterface $personA, PersonInterface $personB): ?DirectChat;

    public function find(int $id): ?DirectChat;

    public function save(DirectChat $chat): DirectChat;
}
