<?php
/**
 * assignment_primary.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\pull;
use cenozo\lib, cenozo\log;

/**
 * pull: assignment primary
 * 
 * @package beartooth\ui
 */
class assignment_primary extends base_primary
{
  /**
   * Constructor
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args Pull arguments.
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'assignment', $args );
  }
}
?>
