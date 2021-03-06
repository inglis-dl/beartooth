<?php
/**
 * appointment_calendar.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package sabretooth\ui
 * @filesource
 */

namespace sabretooth\ui\widget;
use sabretooth\log, sabretooth\util;
use sabretooth\business as bus;
use sabretooth\database as db;
use sabretooth\exception as exc;

/**
 * widget appointment calendar
 * 
 * @package sabretooth\ui
 */
class appointment_calendar extends base_calendar
{
  /**
   * Constructor
   * 
   * Defines all variables required by the appointment calendar.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args An associative array of arguments to be processed by the widget
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'appointment', $args );
    $this->set_heading( 'Appointments for '.bus\session::self()->get_site()->name );
  }
  
  /**
   * Set the rows array needed by the template.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    parent::finish();
    $this->set_variable( 'allow_all_day', false );
    $this->set_variable( 'editable', true );
  }
}
?>
