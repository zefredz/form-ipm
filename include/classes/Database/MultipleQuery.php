<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Utility class to execute multiple SQL queries on a Database_Connection
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
class Database_MultipleQuery
{
    protected $sqlQueryArray = array();
    protected $onErrorCallback = null;
    protected $connection;
    
    public function __construct( $connection, $sqlQueryString )
    {
        $this->sqlQueryArray = $this->parse( $sqlQueryString );
        $this->connection = $connection;
    }
    
    public function setErrorCallback( $callback )
    {
        $this->onErrorCallback = $callback;
        
        return $this;
    }
    
    public function exec()
    {
        $this->executeQueries( $this->sqlQueryArray );
        
        return $this;
    }
    
    protected function parse( $sql )
    {
        $ret = array();
        
        $sql          = rtrim($sql, "\n\r");
        $sql_len      = strlen($sql);
        $char         = '';
        $string_start = '';
        $in_string    = FALSE;
        $nothing      = TRUE;
        
        for ($i = 0; $i < $sql_len; ++$i)
        {
            $char = $sql[$i];
            // We are in a string, check for not escaped end of strings except for
            // backquotes that can't be escaped
            if ($in_string)
            {
                for (;;)
                {
                    $i         = strpos($sql, $string_start, $i);
                    // No end of string found -> add the current substring to the
                    // returned array
                    if (!$i)
                    {
                        $ret[] = array('query' => $sql, 'empty' => $nothing);
                        return $ret;
                    }
                    // Backquotes or no backslashes before quotes: it's indeed the
                    // end of the string -> exit the loop
                    else if ($string_start == '`' || $sql[$i-1] != '\\')
                    {
                        $string_start      = '';
                        $in_string         = FALSE;
                        break;
                    }
                    // one or more Backslashes before the presumed end of string...
                    else
                    {
                        // ... first checks for escaped backslashes
                        $j                     = 2;
                        $escaped_backslash     = FALSE;
                        while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                            $escaped_backslash = !$escaped_backslash;
                            $j++;
                        }
                        // ... if escaped backslashes: it's really the end of the
                        // string -> exit the loop
                        if ($escaped_backslash)
                        {
                            $string_start  = '';
                            $in_string     = FALSE;
                            break;
                        }
                        // ... else loop
                        else
                        {
                            $i++;
                        }
                    } // end if...elseif...else
                } // end for
            } // end if (in string)
            
            // lets skip comments (/*, -- and #)
            else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') 
                || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*'))
            {
                $i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
                // didn't we hit end of string?
                if ($i === FALSE)
                {
                    break;
                }
                if ($char == '/') $i++;
            }
            
            // We are not in a string, first check for delimiter...
            else if ($char == ';')
            {
                // if delimiter found, add the parsed part to the returned array
                $ret[]      = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
                $nothing    = TRUE;
                $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
                $sql_len    = strlen($sql);
                if ($sql_len)
                {
                    $i      = -1;
                }
                else
                {
                    // The submited statement(s) end(s) here
                    return $ret;
                }
            } // end else if (is delimiter)
    
            // ... then check for start of a string,...
            else if (($char == '"') || ($char == '\'') || ($char == '`'))
            {
                $in_string    = TRUE;
                $nothing      = FALSE;
                $string_start = $char;
            } // end else if (is start of string)
            elseif ($nothing)
            {
                $nothing = FALSE;
            }
        } // end for
    
        // add any rest to the returned array
        if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql))
        {
            $ret[] = array('query' => $sql, 'empty' => $nothing);
        }
    
        return $ret;
    }
    
    protected function executeQueries( $queryString )
    {
        foreach ( $this->sqlQueryArray as $query )
        {
            try
            {
                $sql = $query['query'];
                $this->connection->exec( $sql );
            }
            catch( Exception $e )
            {
                if ( ! is_null( $this->onErrorCallback )
                    && is_callable( $this->onErrorCallback )
                    && call_user_func( $this->onErrorCallback, $sql, $e ) )
                {
                    continue;
                }
                else
                {
                    throw $e;
                }
            }
        }
    }
}

