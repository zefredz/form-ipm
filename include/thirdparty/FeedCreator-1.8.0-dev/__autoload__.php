<?php

Loader::registerClassLoader( new ClassLoader_FilePathArray( array(
    'FeedItem' => dirname(__FILE__).'/feedcreator.class.php',
    'EnclosureItem' => dirname(__FILE__).'/feedcreator.class.php',
    'FeedImage' => dirname(__FILE__).'/feedcreator.class.php',
    'HtmlDescribable' => dirname(__FILE__).'/feedcreator.class.php',
    'FeedHtmlField' => dirname(__FILE__).'/feedcreator.class.php',
    'UniversalFeedCreator' => dirname(__FILE__).'/feedcreator.class.php',
    'FeedCreator' => dirname(__FILE__).'/feedcreator.class.php',
    'FeedDate' => dirname(__FILE__).'/feedcreator.class.php',
    'RSSCreator10' => dirname(__FILE__).'/feedcreator.class.php',
    'RSSCreator091' => dirname(__FILE__).'/feedcreator.class.php',
    'RSSCreator20' => dirname(__FILE__).'/feedcreator.class.php',
    'PIECreator01' => dirname(__FILE__).'/feedcreator.class.php',
    'AtomCreator10' => dirname(__FILE__).'/feedcreator.class.php',
    'AtomCreator03' => dirname(__FILE__).'/feedcreator.class.php',
    'MBOXCreator' => dirname(__FILE__).'/feedcreator.class.php',
    'OPMLCreator' => dirname(__FILE__).'/feedcreator.class.php',
    'HTMLCreator' => dirname(__FILE__).'/feedcreator.class.php',
    'JSCreator' => dirname(__FILE__).'/feedcreator.class.php'
) ) );
