<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Assertion Handler and Helper
 *
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Debug_Assertion
{
    protected $log;
    
    public function __construct( Log $log )
    {
        $this->log = $log;
    }
    
    public function handle( $file, $line, $code )
    {
        $time = date( "Y-m-d H:i:s" );
        $this->log->log( "assertion failure : $code in $file at line $line" );
    }
    
    public function register()
    {
        assert_options(ASSERT_ACTIVE, 1);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_QUIET_EVAL, 1);
        assert_options(ASSERT_CALLBACK, array($this,'handle'));
    }
}
