<?php
/**
 * survey_primary.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\pull;
use cenozo\lib, cenozo\log;

/**
 * pull: survey primary
 * 
 * @package beartooth\ui
 */
class survey_primary extends base_primary
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
    parent::__construct( 'survey', $args );
  }
}
?>
