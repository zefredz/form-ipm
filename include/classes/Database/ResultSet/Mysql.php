<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Mysql _Database_Connection Result Set class
 * implements iterator and countable interfaces for
 * array-like behaviour.
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Database_ResultSet_Mysql implements Database_ResultSet
{
    protected $mode;
    protected $idx;
    protected $valid;
    protected $numrows;
    protected $resultSet;
    
    /**
     * @param   resource $result Mysql native resultset
     * @param   mixed $mode fetch mode (optional, default FETCH_ASSOC)
     */
    public function __construct( $result, $mode = self::FETCH_ASSOC )
    {
        $this->resultSet = $result;
        $this->mode = $mode;
        // set to 0 if false;
        $this->numrows = (int) @mysql_num_rows( $this->resultSet );
        $this->idx = 0;
    }
    
    public function __destruct()
    {
        @mysql_free_result($this->resultSet);
        
        unset( $this->numrows );
        unset( $this->mode );
        unset( $this->valid );
        unset( $this->idx );
    }
    
    // --- Database_ResultSet  ---
    
    /**
     * Set the fetch mode
     * @see     Database_ResultSet
     * @return  void
     */
    public function setFetchMode( $mode )
    {
        $this->mode = $mode;
        
        return $this;
    }
    
    /**
     * Get the number of rows in the result set
     * @see     Database_ResultSet
     * @return  int
     */
    public function numRows()
    {
        return $this->numrows;
    }
    
    /**
     * Check if the result set is empty
     * @see     Database_ResultSet
     * @return  boolean
     */
    public function isEmpty()
    {
        return (0 == $this->numRows());
    }
    
    /**
     * Get the next row in the Result Set
     * @see     Database_ResultSet
     * @param   mixed $mode fetch mode (optional, use internal fetch mode :
     *      FETCH_ASSOC by default or set by setFetchMode())
     * @return  mixed result row, returned data type depends on fetch mode :
     *      FETCH_ASSOC, FETCH_NUM or FETCH_BOTH : array
     *      FETCH_OBJECT : object representation of the current row
     *      FETCH_VALUE : value of the first field in the current row
     */
    public function fetch( $mode = null )
    {
        $mode = empty( $mode ) ? $this->mode : $mode;
        
        if ( $mode == self::FETCH_OBJECT )
        {
            return @mysql_fetch_object( $this->resultSet );
        }
        // FIXME : FETCH_VALUE should not be called twice !
        elseif ( $mode == self::FETCH_VALUE || $mode == self::FETCH_COLUMN )
        {
            $res = @mysql_fetch_array( $this->resultSet, self::FETCH_NUM );
            
            // use side effect of the [] operator : will return null if !$res
            return $res[0];
        }
        else
        {
            return @mysql_fetch_array( $this->resultSet, $mode );
        }
    }
    
    // --- Countable  ---
    
    /**
     * Count the number of rows in the result set
     * Usage :
     *      $size = count( $resultSet );
     * 
     * @see     Countable
     * @return  int size of the result set (ie number of rows)
     */
    public function count()
    {
        return $this->numRows();
    }
    
    // --- Iterator ---
    
    /**
     * Check if the current position in the result set is valid
     * @see     Iterator
     * @return  boolean
     */
    public function valid()
    {
        return $this->valid;
    }
    
    /**
     * Return the current row
     * @see     Iterator
     * @see     Database_ResultSet::fetch() for return value data type
     * @return  mixed, current row
     */
    public function current()
    {
        // Go to the correct data
        $this->seek( $this->idx );
        
        return $this->fetch( $this->mode );
    }
    
    /**
     * Advance to the next row in the result set
     * @see     Iterator
     */
    public function next()
    {
        $this->idx++;
        $this->valid = $this->idx < $this->numRows();
    }
    
    /**
     * Rewind to the first row
     * @see     Iterator
     */
    public function rewind()
    {
        $this->idx = 0;
        $this->valid = @mysql_data_seek( $this->resultSet, 0 );
    }
    
    /**
     * Return the index of the current row
     * @see     Iterator
     * @return  int
     */
    public function key()
    {
        return $this->idx;
    }
    
    // --- SeekableIterator ---
    
    /**
     * Usage :
     *      $resultSet->seek( 5 );
     *      $r = $resultSet->fetch();
     *      
     * @see     SeekableIterator
     * @param   int $idx
     * @return  void
     * @throws  OutOfBoundsException if invalid index
     */
    public function seek( $idx )
    {
        if ( $idx < $this->numRows() && $idx >= 0 && ! $this->isEmpty() && $this->valid() )
        {
            $this->idx = $idx;
            @mysql_data_seek( $this->resultSet, $this->idx );
        }
        else
        {
            throw new OutOfBoundsException('Invalid seek position');
        }
    }
}

