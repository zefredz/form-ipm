<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Main class loader and class loader helpers
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Loader
{
   private $_classLoaders = array();
   
   /**
    * Load a class from the framework class folder
    * @param    string $className
    */
   public function load( $className )
   {
       $path = dirname(__FILE__) . '/' . str_replace( '_', '/', $className ) . '.php';
           
       if ( file_exists($path))
       {
           require $path;
       }
   }
   
   /**
    * Register the main class loader
    */
   public function register()
   {
       spl_autoload_register( array( $this, 'load' ) );
   }
   
   // Helpers
   
   /**
    * Register another class loader (helper build upon spl_autoload_register)
    * @param    ClassLoader $classLoader
    */
   public static function registerClassLoader( ClassLoader $classLoader )
   {
       spl_autoload_register( array( $classLoader, 'load' ) );
   }
   
   /**
    * Helper to register autoloaders from the vendor folder subfolders.
    * Alias of Loader::register__autoload__FromSubFolders.
    * @see Loader::register__autoload__FromSubFolders
    */
   public static function registerVendorClassLoaders( $vendorPluginsFolder, $silent = false )
   {
        self::register__auloload__FromSubFolders( $vendorPluginsFolder, $silent );
   }
   
   /**
    * Register autoloaders from the subfolders of the given folder. The
    * autoloader are defined in an __autoload__.php file in each subfolder
    * @param string $rootFolder path to the folder in which the subfolders
    *   containing the autoloader declarations are located
    * @param bool $silent ignore exceptions (default false)
    * @throws RuntimeException if one subfolder does not contain an autoloader declaration
    */
   public static function register__auloload__FromSubFolders ( $rootFolder, $silent = false )
   {
        $thirdpartyLibsIterator = new DirectoryIterator( $rootFolder );

        foreach ( $thirdpartyLibsIterator as $candidateLib )
        {
            if ( $candidateLib->isDir() && !$candidateLib->isDot() )
            {
                try
                {
                    self::register__auloload__FromFolder( $candidateLib->getPathname() );
                }
                catch ( RuntimeException $e )
                {
                    if ( !$silent )
                    {
                        throw $e;
                    }
                }
            }
        }
   }
   
   /**
    * Helper to register the application classes autoloader. Alias of
    * self::register__auloload__FromFolder
    * @see self::register__auloload__FromFolder
    */
   public static function registerApplicationLoader( $applicationPath )
   {
        self::register__auloload__FromFolder( $applicationPath );
   }
   
   /**
    * Register an autoloader from the __autoload__.php autoload declaration file
    * located in the given folder
    * @param string $folderPath path to the folder containing the autoloader
    *   declaration file
    * @throws RuntimeException if no autoloader declaration file found in the
    *   given folder
    */
   public static function register__auloload__FromFolder ( $folderPath )
   {
        if ( file_exists( rtrim( $folderPath, '/' ) . '/__autoload__.php' ) )
        {
            require rtrim( $folderPath, '/' ) . '/__autoload__.php';
        }
        else
        {
            throw new RuntimeException("No __autoload__.php file found in $folderPath");
        }
   }
}
