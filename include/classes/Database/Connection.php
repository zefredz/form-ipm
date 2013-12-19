<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Database_Connection generic interface
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
interface Database_Connection
{
    /**
     * Connect to the database
     * @throws  Database_Exception
     */
    public function connect();
    
    /**
     * Select a database
     * @param   string $database database name
     * @throws  Database_Exception on failure
     */
    public function selectDatabase( $database );
    
    /**
     * Execute a query and returns the number of affected rows
     * @return  int
     * @throws  Database_Exception
     */
    public function exec( $sql );
    
    /**
     * Execute a query and returns the result set
     * @return  Database_ResultSet
     * @throws  Database_Exception
     */
    public function query( $sql );
    
    /**
     * Returns the number of rows affected by the last query
     * @return  int
     * @throws  Database_Exception
     */
    public function affectedRows();
    
    /**
     * Get the ID generated from the previous INSERT operation
     * @return  int
     * @throws  Database_Exception
     */
    public function insertId();
    
    /**
     * Escape dangerous characters in the given string
     * @param   string $str
     * @return  string
     */
    public function escape( $str );
    
    /**
     * Escape dangerous characters and enquote the given string
     * @param   string $str
     * @return  string
     */
    public function quote( $str );
}

