<?php
namespace Kernel\Mailer;

use PHPMailer\PHPMailer\PHPMailer as Mailer;
use PHPMailer\PHPMailer\Exception;

class PhpMailer
{
    private $charset = 'utf-8';
    private $debug = 0;
    private $driver = [];
    private $html = true;

    public function __construct(array $driver)
    {
        $this->driver = $driver;
    }

    public function send(array $email = [])
    {
        try {
            return $this->makeSend($email);
        } catch (Exception $e) {
            // log error
        }

        return false;
    }

    private function makeSend(array $email = [])
    {
        $mailer = $this->makeConnection();

        $this->makeMailAttribute($mailer, 'setFrom', $email['from']);
        $this->makeMailAttribute($mailer, 'addAddress', $email['to']);

        if (!empty($email['cc'])) {
            $this->makeMailAttribute($mailer, 'addCC', $email['cc']);
        }

        if (!empty($email['bcc'])) {
            $this->makeMailAttribute($mailer, 'addBCC', $email['bcc']);
        }

        $mailer->Subject = $email['title'] ?? '';
        $mailer->Body = $email['content'] ?? '';

        if ($mailer->send()) {
            return true;
        }

        return false;
    }

    private function makeMailAttribute($mailer, $func, $attr)
    {
        $mail = key($attr);
        $name = current($attr);
        $mailer->$func($mail, $name);
    }

    private function makeMailAttributeList($mailer, $func, $attr)
    {
        foreach ($attr as $target) {
            self::makeMailAttribute($mailer, $func, $target);
        }
    }

    private function makeConnection()
    {
        extract($this->driver);

        $mailer = new Mailer();
        if ($transport == 'smtp') {
            $mailer->isSMTP(); // Set mailer to use SMTP
            $mailer->Host = $host; // Specify main and backup SMTP servers
            $mailer->SMTPAuth = true; // Enable SMTP authentication
            $mailer->Username = $username; // SMTP username
            $mailer->Password = $password; // SMTP password
            $mailer->SMTPSecure = $protocol; // Enable TLS encryption, `ssl` also accepted
            $mailer->Port = $port;
            $mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    ]
            ];
            $mailer->SMTPDebug = $this->debug;
            $mailer->SMTPAuth = true;
        }

        $mailer->CharSet = $this->charset;
        $mailer->isHTML($this->html);

        return $mailer;
    }

    public function debug(int $debug = 0)
    {
        $this->debug = $debug;

        return $this;
    }

    public function html(bool $html = true)
    {
        $this->html = $html;

        return $this;
    }
}
