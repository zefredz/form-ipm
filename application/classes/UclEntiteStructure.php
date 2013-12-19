<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Helper to retreive the structure of UCL entities from the new LDAP entity "tags"
 *
 * @todo        check and upgrade if necessary
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class UclEntiteStructure
{
    private static $UCL_Structure = array(
        'secteurs' => array( 'AS', 'SST', 'SSH', 'SSS', 'LS' ),
        'facultes' => array(
            'AC', 'AUST',
            'SGSI', 'AULS', 'BIUL',
            'EPL', 'AGRO', 'LOCI', 'SC',
            'DRT', 'ESPO', 'FIAL', 'LSM-LLN', 'PSP', 'TECO',
            'FASB', 'FSM', 'FSP', 'MEDE'
        ),
        'instituts' => array(
            'DDUV', 'IoNS', 'IREC', 'IRSS', 'LDRI',
            'IACCHOS', 'IL&C', 'ILSM', 'IMMAQ', 'INCAL', 'IPSY', 'ISP', 'JUR-I', 'RSCS', 'ISPOLE',
            'ELI', 'ICTEAM', 'IMCN', 'iMMC', 'IRMP', 'ISV',
        ),
        'plateformes' => array(
            'ASS', 'ANIM', 'ATEL',
            'CENTAL',
            'CISM', 'CRC', 'WINFAB'
        ),
        'departements' =>array(
            'ADEF', 'ADAE', 'ADFI', 'ADPI', 'ADRE', 'ADRI', 'DIC', 'RHUM', 'SPER', 'SVAC',
            'SIMM', 'SISE', 'SIBX', 'SISG', 'SIPR', 'SIPS', 'SISU', 'SIWS', 'SRI'
        )
    );
    
    protected $rawStruct, $structArr;
    
    public function __construct( $rawStruct )
    {
        $this->rawStruct = $rawStruct;
        
        $this->structArr = array(
            'firstlvl' => array(),
            'secondlvl' => array(),
            'thirdlvl' => array(),
            'fourthlvl' => array()
        );
    }
    
    public function parse()
    {
        foreach ( $this->rawStruct as $entite )
        {
            if ( $this->isFirstLevel( $entite ) )
            {
                $this->structArr['firstlvl'][] = $entite;
            }
            elseif ( $this->isSecondLevel( $entite ) )
            {
                $this->structArr['secondlvl'][] = $entite;
            }
            elseif ( $this->isThirdLevel( $entite ) )
            {
                $this->structArr['thirdlvl'][] = $entite;
            }
            else
            {
                $this->structArr['fourthlvl'][] = $entite;
            }
        }
    }
    
    public function getParsedStructure()
    {
        return $this->structArr;
    }
    
    protected function isFirstLevel( $str )
    {
        return in_array( $str, self::$UCL_Structure['secteurs'] );
    }
    
    protected function isSecondLevel( $str )
    {
        return in_array( $str, self::$UCL_Structure['facultes'] )
            || in_array( $str, self::$UCL_Structure['instituts'] )
            || in_array( $str, self::$UCL_Structure['plateformes'] );
    }
    
    protected function isThirdLevel( $str )
    {
        return in_array( $str, self::$UCL_Structure['departements'] );
    }
}

