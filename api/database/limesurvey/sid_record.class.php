<?php
/**
 * sid_record.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\database
 * @filesource
 */

namespace beartooth\database\limesurvey;
use beartooth\log, beartooth\util;
use beartooth\business as bus;
use beartooth\database as db;
use beartooth\exception as exc;

/**
 * Access to limesurvey's *_SID tables.
 * 
 * Since limesurvey's database structure for some tables is dynamic this class overrides
 * much of the functionality in record class as is appropriate.
 * 
 * @package beartooth\database
 */
abstract class sid_record extends record
{
  public static function set_sid( $sid )
  {
    static::$table_sid = $sid;
  }

  /**
   * Returns the name of the table associated with this record.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   * @static
   */
  public static function get_table_name()
  {
    if( is_null( static::$table_sid ) )
    {
      throw new exc\runtime(
        'The survey id (table_sid) must be set before using this class.', __METHOD__ );
    }

    return sprintf( '%s_%s',
                    parent::get_table_name(),
                    static::$table_sid );
  }
  
  /**
   * The current survey table's sid.  Be sure to set this before calling the class constructor.
   * @var int
   * @access public
   */
  protected static $table_sid = NULL;
}
?>
