// $Id$
// vim: expandtab sw=4 ts=4 sts=4:

/** 
 * French locale for Date.js library by Henrik Lindqvist
 *
 * @version     1.0 $Revision$
 * @copyright   2001-2009 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license      http://www.gnu.org/licenses/lgpl-3.0.txt
 *              GNU LESSER GENERAL PUBLIC LICENSE Version 3.0 or later
 * @package     core.js
 *
 */

(function (d) {

d.i18n['fr'] = 
d.i18n['fr-FR'] = {
  months: {
    abbr: [ 'jan', 'fév', 'mar', 'avr', 'mai', 'jun', 'jul', 'aou', 'sep', 'oct', 'nov', 'déc' ],
    full: [ 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' ]
  },
  days: {
    abbr: [ 'dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam' ],
    full: [ 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi' ]
  },
  week: {   // Used by date pickers
    abbr: 'sem',
    full: 'semaine'
  },
  ad: 'AD',
  am: 'AM',
  pm: 'PM',
  gmt: 'GMT',
  z: ':',   // Hour - minute separator
  Z: '',    // Hour - minute separator
  fdow: 1,  // First day of week
  mdifw: 1  // Minimum days in first week
};

})(Date);