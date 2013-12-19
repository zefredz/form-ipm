<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Mysql specific Database_Connection
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Database_Connection_Mysql implements Database_Connection
{
    protected $host, $username, $password, $database;
    protected $dbLink;
    
    /**
     * Create a new Mysql_Database_Connection instance
     * @param   string $host database host
     * @param   string $username database user name
     * @param   string $password database user password
     * @param   string $database name of the database to select (optional)
     */
    public function __construct( $host, $username, $password, $database = null )
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->dbLink = false;
    }
    
    protected function isConnected()
    {
        return !empty($this->dbLink);
    }
    
    /**
     * @see Database_Connection
     */
    public function connect()
    {
        if ( $this->isConnected() )
        {
            throw new Database_Exception("Already to database server {$this->username}@{$this->host}");
        }
        
        $this->dbLink = @mysql_connect( $this->host, $this->username, $this->password );
        
        if ( ! $this->dbLink )
        {
            throw new Database_Exception("Cannot connect to database server {$this->username}@{$this->host}");
        }
        
        if ( !empty( $this->database ) )
        {
            $this->selectDatabase( $this->database );
        }
        
        return $this;
    }
    
    /**
     * @see Database_Connection
     */
    public function selectDatabase( $database )
    {
        if ( ! $this->isConnected() )
        {
            throw new Database_Exception("No connection found to database server, please connect first");
        }
        
        if ( ! @mysql_select_db( $database, $this->dbLink ) )
        {
            throw new Database_Exception("Cannot select database {$database} on {$this->username}@{$this->host}");
        }
        
        $this->database = $database;
        
        return $this;
    }
    
    /**
     * @see Database_Connection
     */
    public function affectedRows()
    {
        if ( ! $this->isConnected() )
        {
            throw new Database_Exception("No connection found to database server, please connect first");
        }
        
        return @mysql_affected_rows( $this->dbLink );
    }
    
    /**
     * @see Database_Connection
     */
    public function insertId()
    {
        if ( ! $this->isConnected() )
        {
            throw new Database_Exception("No connection found to database server, please connect first");
        }
        
        return @mysql_insert_id( $this->dbLink );
    }
    
    /**
     * @see Database_Connection
     */
    public function exec( $sql )
    {
        if ( ! $this->isConnected() )
        {
            throw new Database_Exception("No connection found to database server, please connect first");
        }
        
        if ( false === @mysql_query( $sql ) )
        {
            throw new Database_Exception( "Error in {$sql} : ".@mysql_error($this->dbLink), @mysql_errno($this->dbLink) );
        }
        
        return $this->affectedRows();
    }
    
    /**
     * @see Database_Connection
     */
    public function query( $sql )
    {
        if ( ! $this->isConnected() )
        {
            throw new Database_Exception("No connection found to database server, please connect first");
        }
        
        if ( false === ( $result = @mysql_query( $sql ) ) )
        {
            throw new Database_Exception( "Error in {$sql} : ".@mysql_error($this->dbLink), @mysql_errno($this->dbLink) );
        }
        
        $tmp = new Database_ResultSet_Mysql( $result );
        
        return $tmp;
    }
    
    /**
     * @see Database_Connection
     */
    public function escape( $str )
    {
        return mysql_real_escape_string( $str, $this->dbLink );
    }
    
    /**
     * @see Database_Connection
     */
    public function quote( $str )
    {
        return "'".$this->escape($str)."'";
    }
}

