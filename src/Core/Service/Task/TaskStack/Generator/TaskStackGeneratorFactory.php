<?php

namespace App\Core\Service\Task\TaskStack\Generator;

use Exception;

class TaskStackGeneratorFactory
{
    /**
     * @param iterable<TaskStackGeneratorInterface> $generators
     */
    public function __construct(
        private iterable $generators
    ) {
    }

    public function create(string $alias): TaskStackGeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if ($generator->type() === $alias) {
                return $generator;
            }
        }

        throw new Exception("Не найден генератор тасок $alias");
    }
}
