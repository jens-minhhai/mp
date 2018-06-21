<?php

namespace Service\File;

use Service\File\Domain\Delete as Domain;

class Delete
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function delete(array $id)
    {
        return $this->domain->delete($id);
    }
}
