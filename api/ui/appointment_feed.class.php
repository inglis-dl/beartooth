<?php
/**
 * appointment_feed.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package sabretooth\ui
 * @filesource
 */

namespace sabretooth\ui;
use sabretooth\log, sabretooth\util;
use sabretooth\business as bus;
use sabretooth\database as db;
use sabretooth\exception as exc;

/**
 * datum appointment feed
 * 
 * @package sabretooth\ui
 */
class appointment_feed extends base_feed
{
  /**
   * Constructor
   * 
   * Defines all variables required by the appointment feed.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args An associative array of arguments to be processed by the datum
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'appointment', $args );
  }
  
  /**
   * Returns the data provided by this feed.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return array
   * @access public
   */
  public function get_data()
  {
    // create a list of appointments between the feed's start and end time
    $modifier = new db\modifier();
    $modifier->where( 'date', '>=', $this->start_date );
    $modifier->where( 'date', '<', $this->end_date );

    $event_list = array();
    $db_site = bus\session::self()->get_site();
    foreach( db\appointment::select_for_site( $db_site, $modifier ) as $db_appointment )
    {
      $db_participant = $db_appointment->get_participant();
      $event_list[] = array(
        'id' => $db_appointment->id,
        'title' => $db_participant->first_name.' '.$db_participant->last_name,
        'allDay' => false,
        'start' => strtotime( $db_appointment->date ),
        // assume appointments to be one hour long
        'end' => strtotime( ( $db_appointment->date ) + 3600 ) );
    }

    return $event_list;
  }
}
?>