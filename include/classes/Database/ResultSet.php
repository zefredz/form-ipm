<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Database_ResultSet generic interface
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
interface Database_ResultSet extends SeekableIterator, Countable
{
    /**
     * Associative array fetch mode constant
     */
    const FETCH_ASSOC = MYSQL_ASSOC;
    
    /**
     * Numeric index array fetch mode constant
     */
    const FETCH_NUM = MYSQL_NUM;
    
    /**
     * Associative and numeric array fetch mode constant
     */
    const FETCH_BOTH = MYSQL_BOTH;
    
    /**
     * Object fetch mode constant
     */
    const FETCH_OBJECT = 'FETCH_OBJECT';
    
    /**
     * Fetch the value of the first column  of the first row of the result set
     */
    const FETCH_VALUE = 'FETCH_VALUE';
    
    /**
     * Fetch the value of the first column of each row of the result set
     */
    const FETCH_COLUMN = 'FETCH_COLUMN';
    
    /**
     * Set fetch mode
     * @param   mixed $mode fetch mode
     */
    public function setFetchMode( $mode );
    
    /**
     * Get the next row in the Result Set
     * @param   mixed $mode fetch mode (optional, use internal fetch mode :
     *      FETCH_ASSOC by default or set by setFetchMode())
     * @return  mixed result row, returned data type depends on fetch mode :
     *      FETCH_ASSOC, FETCH_NUM or FETCH_BOTH : array
     *      FETCH_OBJECT : object representation of the current row
     *      FETCH_VALUE : value of the first field in the current row
     */
    public function fetch( $mode = null );
    
    /**
     * Get the number of rows in the result set
     * @return  int
     */
    public function numRows();
    
    /**
     * Check if the result set is empty
     * @return  boolean
     */
    public function isEmpty();
}

