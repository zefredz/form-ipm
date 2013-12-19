<?php

Loader::registerClassLoader( new ClassLoader_FilePathArray( array(
    'UUID' => dirname(__FILE__).'/class.uuid.php'
) ) );
