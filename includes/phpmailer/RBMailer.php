<?php

namespace PHPMailer\PHPMailer;
require_once 'PHPMailer.php';
require_once 'SMTP.php';
require_once 'Exception.php';


class RBMailer extends PHPMailer
{
    public $messageContent;
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);
        $this->isSMTP();
        $this->Host = 'lusy.io';
        $this->SMTPAuth = true;
        $this->Username = 'info@lusy.io';
        $this->Password = '~g8#@*2HVne2';
        $this->SMTPSecure = 'ssl';
        $this->Port = 465;
        $this->CharSet = 'UTF-8';
        $this->setFrom('info@lusy.io', 'Lusy.io');
        $this->isHTML();
    }

    public function setMessageContent($template, $args)
    {
        ob_start();
        include 'templates/content-header.php';
        $contentHeader = ob_get_clean();

        ob_start();
        include 'templates/' . $template . '.php';
        $content = ob_get_clean();

        foreach ($args as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $search = '{$' . $key . '}';
                $content = str_replace($search, $value, $content);
            }
        }

        ob_start();
        include 'templates/content-footer.php';
        $contentFooter = ob_get_clean();
        $this->Body = $contentHeader . $content . $contentFooter;
    }
}
