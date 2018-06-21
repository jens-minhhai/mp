<?php

namespace Service\File;

use Kernel\Lib\Fs;
use Service\File\Domain\Link as Domain;

class Link
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function target($id)
    {
        return $this->domain->target($id);
    }
}
