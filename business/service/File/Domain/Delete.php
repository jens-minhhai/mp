<?php

namespace Service\File\Domain;

use Service\File\Repository\Delete as Repository;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
