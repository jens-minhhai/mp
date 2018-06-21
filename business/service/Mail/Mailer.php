<?php

namespace Service\Mail;

use Factory;
use View;

class Mailer
{
    const MODE_PENDING = 1;
    const MODE_SEND_OK = 2;
    const MODE_SEND_NG = 3;

    private $debug = 0;
    private $html = true;
    private $delay = 0;
    private $artisan = '';
    private $driver = '';
    private $template = '';
    private $to = [];
    private $from = [];
    private $bcc = [];
    private $cc = [];
    private $attachment = [];
    private $title = '';
    private $content = '';

    public function sendHardMail(string $mail_name, array $variable = [])
    {
        $email = [
            'content' => $this->buildContentFromTemplate($mail_name, $variable)
        ];

        $mail = $this->makeMail($email);
        if ($this->delay) {
            return $this->makeDelayMail($mail);
        }

        return $this->makeSendMail($mail);
    }

    public function send(array $variable = [])
    {
        $email = $this->template ? $this->getTemplate($variable) : [];
        if (!$email) {
            return false;
        }

        $mail = $this->makeMail($email);
        if ($this->delay) {
            return $this->makeDelayMail($mail);
        }

        return $this->makeSendMail($mail);
    }

    public function makeMail(array $email)
    {
        $merge = ['cc', 'bcc'];
        $attr = [
            'to',
            'from',
            'cc',
            'bcc',
            'attachment',
            'title',
            'content'
        ];

        foreach ($attr as $field) {
            if ($this->$field) {
                if (in_array($field, $merge)) {
                    $email[$field] = array_merge($email[$field], $this->$field);
                } else {
                    $email[$field] = $this->$field;
                }
            }
        }

        return $email;
    }

    protected function makeSendMail(array $mail)
    {
        $flag = false;
        if ($mail['to']) {
            $flag = $this->deliver($this->driver)
                         ->debug($this->debug)
                         ->send($mail);
        }

        $this->log($mail, $flag);
    }

    public function makeDelayMail(array $mail)
    {
        $mail['mode'] = self::MODE_PENDING;

        $trigger = Factory::global_service('mail.detail.domain.delay');

        return $trigger->queue($mail, $this->delay);
    }

    public function deliver(string $driver)
    {
        $config = array_get(container('app'), 'config.mail.' . $driver);

        return Factory::kernel('mailer.' . $this->artisan, $config);
    }

    protected function getTemplate(array $variable = [])
    {
        $trigger = Factory::global_service('mail.template.domain.index');

        $template = $trigger->getByName($this->template);

        if ($template) {
            $content = $template['content'];
            $template['content'] = $this->buildContentFromString($content, $variable);

            if ($template['artisan']) {
                $this->artisan = $template['artisan'];
            }

            if ($template['driver']) {
                $this->artisan = $template['driver'];
            }

            if ($template['delay']) {
                $this->delay = $template['delay'];
            }
        }

        if (empty($template['content'])) {
            $template['content'] = $this->buildContentFromTemplate($this->template, $variable);
        }

        return $template;
    }

    protected function buildContentFromString(string $string, array $variable = [])
    {
        return View::string($string, $variable);
    }

    protected function buildContentFromTemplate(string $template, array $variable = [])
    {
        $template = 'email/' . $template . '.twg';

        return \View::fs($template, $variable);
    }

    public function template(string $template)
    {
        $this->template = $template;

        return $this;
    }

    public function artisan(string $artisan)
    {
        $this->artisan = $artisan;

        return $this;
    }

    public function delay(int $delay)
    {
        $this->delay = $delay;

        return $this;
    }

    public function to(string $address, string $name = '')
    {
        $this->to = array_merge($this->to, [$address => $name]);

        return $this;
    }

    public function from(string $address, string $name = '')
    {
        $this->from = [$address => $name];

        return $this;
    }

    public function cc(string $address, string $name = '')
    {
        $this->cc = array_merge($this->cc, [$address => $name]);

        return $this;
    }

    public function bcc(string $address, string $name = '')
    {
        $this->bcc = array_merge($this->bcc, [$address => $name]);

        return $this;
    }

    public function attachment(string $path)
    {
        $this->attachment = array_push($this->attachment);

        return $this;
    }

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function debug(int $debug)
    {
        $this->debug = $debug;

        return $this;
    }

    public function __construct($driver = 'default', $artisan = 'PhpMailer')
    {
        $this->driver = $driver;
        $this->artisan = $artisan;
    }

    protected function log(array $mail, bool $flag)
    {
        if ($flag) {
            $mail['attemp'] = 0;
            $mail['mode'] = self::MODE_SEND_OK;
        } else {
            $mail['mode'] = self::MODE_SEND_NG;
        }

        // $now = function () {
        //     return 'NOW()';
        // };

        $mail['delivery_time'] = 'NOW()';
        $mail['flush_time'] = 'NOW()';

        $mail['driver'] = $this->driver;
        $mail['artisan'] = $this->artisan;

        $trigger = Factory::global_service('mail.detail.domain.logger');
        $trigger->write($mail);
    }
}
