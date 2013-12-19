<?php

Loader::registerClassLoader( new ClassLoader_FilePathArray( array(
    'PHPMailer' => dirname(__FILE__).'/class.phpmailer.php',
    'POP3' => dirname(__FILE__).'/class.pop3.php',
    'SMTP' => dirname(__FILE__).'/class.smtp.php'
) ) );
