<?php
/**
 * appointment_list.class.php
 * 
 * @author Dean Inglis <inglisd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\pull;
use cenozo\lib, cenozo\log, beartooth\util;

/**
 * Class for appointment list pull operations.
 * 
 * @abstract
 * @package beartooth\ui
 */
class appointment_list extends \cenozo\ui\pull\base_list
{
  /**
   * Constructor
   * 
   * @author Dean Inglis <inglisd@mcmaster.ca>
   * @param array $args Pull arguments.
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'appointment', $args );
  

    $now_datetime_obj = util::get_datetime_object();
    $interval = lib::create( 'business\setting_manager' )->get_setting( 
        'appointment', 'update interval' );

    if( $interval == 'M' )
    {
      $timeStamp = mktime( 0, 0, 0, date( 'm' ), 1, date( 'Y' ) );
      $firstDay = date( 'Y:m:d H:i:s', $timeStamp );
      $this->start_datetime = new \DateTime( $firstDay );
      $this->end_datetime = clone $this->start_datetime;
      $this->end_datetime->add( new \DateInterval( 'P1M' ) );
    }
    else if( $interval == 'W' )
    {
      $timeStamp = mktime( 1, 0, 0, date( 'm' ), date( 'd' ) - date( 'w' ), date( 'Y' ) );
      $firstDay = date( 'Y:m:d', $timeStamp ) . ' 00:00:00';
      $this->start_datetime = new \DateTime( $firstDay );
      $this->end_datetime = clone $this->start_datetime;
      $this->end_datetime->add( new \DateInterval( 'P1W' ) );
    }
    else if( $interval == 'D' )
    {
      $this->start_datetime = clone $now_datetime_obj;
      $this->start_datetime->setTime(0,0);
      $this->end_datetime = clone( $this->start_datetime );
      $this->end_datetime->add( new \DateInterval( 'P1D' ) );
    }
    else
    {
      throw lib::create( 'exception\notice', 
        'Invalid appointment list interval (must be either M, W or D): '.$interval, __METHOD__ );
    }
  }

  /**
   * Returns the data provided by this appointment list.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return array
   * @access public
   */
  public function finish()
  {
    $event_list = array();

    // create a list of appointments between the start and end time
    $db_user = lib::create( 'business\session' )->get_user();
    $class_name = lib::get_class_name( 'database\onyx_instance' );
    $db_onyx = $class_name::get_unique_record( 'user_id' , $db_user->id );
    
    $modifier = lib::create( 'database\modifier' );
    $modifier->where( 'datetime', '>=', $this->start_datetime->format( 'Y-m-d H:i:s' ) );
    $modifier->where( 'datetime', '<', $this->end_datetime->format( 'Y-m-d H:i:s' ) );

    // determine whether this is a site instance of onyx or an interviewer's laptop
    $appointment_list = NULL;
    if( is_null( $db_onyx->interviewer_user_id ) )
    {
      $class_name = lib::get_class_name( 'database\appointment' );
      $appointment_list = $class_name::select_for_site( $db_onyx->get_site(), $modifier );
    }
    else
    {
      $class_name = lib::get_class_name( 'database\role' );
      $db_role = $class_name::get_unique_record( 'name', 'interviewer' );
      $class_name = lib::get_class_name( 'database\access' );
      $db_access = $class_name::get_unique_record(
        array( 'user_id', 'site_id', 'role_id' ),
        array( $db_onyx->interviewer_user_id, $db_onyx->site_id, $db_role->id ) );
      $class_name = lib::get_class_name( 'database\appointment' );
      $appointment_list = $class_name::select_for_access( $db_access, $modifier );
    }

    if( is_null( $appointment_list ) )
      throw lib::create( 'exception\runtime', 
        'Cannot get an appointment list for onyx', __METHOD__ );

    foreach( $appointment_list as $db_appointment )
    {
      $start_datetime_obj = util::get_datetime_object( $db_appointment->datetime );
      $db_participant = $db_appointment->get_participant();

      $mastodon_manager = lib::create( 'business\cenozo_manager', MASTODON_URL );
      $participant_obj = new \stdClass();
      if( $mastodon_manager->is_enabled() )
      {
        $participant_obj = $mastodon_manager->pull( 'participant', 'primary',
                             array( 'uid' => $db_participant->uid ) );
      }
      else
      {
        throw lib::create( 'exception\runtime', 
          'Onyx requires populated dob and gender data from Mastodon', __METHOD__ );
      }

      $db_address = $db_participant->get_primary_address();

      $event_list[] = array(
        'uid'        => $db_participant->uid,
        'first_name' => $db_participant->first_name,
        'last_name'  => $db_participant->last_name,
        'dob'        => is_null( $participant_obj->data->date_of_birth )
                      ? ''
                      : util::get_datetime_object( 
                          $participant_obj->data->date_of_birth )->format( 
                            'Y-m-d' ),
        'gender'    => $participant_obj->data->gender,
        'datetime'  => $start_datetime_obj->format( \DateTime::ISO8601 ),
        'street'    => is_null( $db_address ) ? 'NA' : $db_address->address1,
        'city'      => is_null( $db_address ) ? 'NA' : $db_address->city,
        'province'  => is_null( $db_address ) ? 'NA' : $db_address->get_region()->name,
        'postcode'  => is_null( $db_address ) ? 'NA' : $db_address->postcode,
        'consent_to_draw_blood' =>  false ); // TODO: implement this field in mastodon
    }

    return $event_list;
  }

  /**
   * The start date/time of the appointment list
   * @var string
   * @access protected
   */
  protected $start_datetime = NULL;
  
  /**
   * The end date/time of the appointment list
   * @var string
   * @access protected
   */
  protected $end_datetime = NULL;
}
?>
