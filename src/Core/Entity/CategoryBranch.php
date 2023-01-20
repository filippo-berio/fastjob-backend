<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity]
class CategoryBranch
{
    #[Id]
    #[OneToOne(inversedBy: 'categoryBranch', cascade: ['persist'])]
    #[JoinColumn(name: 'id', referencedColumnName: 'id')]
    private Category $category;

    #[ManyToOne]
    private ?CategoryBranch $previous;

    public function __construct(
        Category $category,
        ?CategoryBranch $previous = null
    ) {
        $this->category = $category;
        $this->previous = $previous;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPrevious(): CategoryBranch
    {
        return $this->previous;
    }
}
