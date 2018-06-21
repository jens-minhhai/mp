<?php

namespace Admin\Helper;

use Service\Mail\Mailer;

class Common
{
    public function sendmail(string $template, array $variable = [])
    {
        $mailer = new Mailer();
        return $mailer->template($template)
                       ->send($variable);
    }
}
