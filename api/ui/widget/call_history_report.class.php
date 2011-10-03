<?php
/**
 * call_history.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\widget;
use beartooth\log, beartooth\util;
use beartooth\business as bus;
use beartooth\database as db;
use beartooth\exception as exc;

/**
 * widget call history report
 * 
 * @package beartooth\ui
 */
class call_history_report extends base_report
{
  /**
   * Constructor
   * 
   * Defines all variables which need to be set for the associated template.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args An associative array of arguments to be processed by the widget
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'call_history', $args );

    $this->add_restriction( 'site' );
    $this->add_restriction( 'dates' );

    $this->set_variable( 'description',
      'This report chronologically lists assignment call attempts.  The report includes the '.
      'participant\'s UID, operator\'s name, date of the assignment, result, start and end time '.
      'of each call.' );
  }

  /**
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    parent::finish();

    $this->finish_setting_parameters();
  }
}
?>
