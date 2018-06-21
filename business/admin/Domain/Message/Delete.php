<?php

namespace Admin\Domain\Message;

use Admin\Repository\Message\Delete as Repository;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
