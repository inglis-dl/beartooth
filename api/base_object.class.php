<?php
/**
 * base_object.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth
 * @filesource
 */

namespace beartooth;

/**
 * base_object: master base object
 *
 * The base_object class from which all other beartooth classes extend
 * @package beartooth
 */
abstract class base_object
{
  /**
   * Returns the name of the class without namespaces
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_class_name()
  {
    return substr( strrchr( get_called_class(), '\\' ), 1 );
  }
}
?>
