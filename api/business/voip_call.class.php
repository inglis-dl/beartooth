<?php
/**
 * voip_call.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package sabretooth\business
 * @filesource
 */

namespace sabretooth\business;
use sabretooth\log, sabretooth\util;
use sabretooth\database as db;
use sabretooth\exception as exc;

require_once SHIFT8_PATH.'/library/Shift8.php';

/**
 * The details of a voip call.
 * 
 * @package sabretooth\business
 */
class voip_call extends \sabretooth\base_object
{
  /**
   * Constructor.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param Shift8_Event $event The event from a Shift8::getStatus() call
   * @access public
   */
  public function __construct( $s8_event )
  {
    // check that the contact is valid
    if( is_null( $s8_event ) ||
        !is_object( $s8_event ) ||
        'Shift8_Event' != get_class( $s8_event ) )
      throw exc\argument( 'connect to invalid contact.', __METHOD__ );

    $this->channel = $s8_event->get( 'channel' );
    $this->bridge = $s8_event->get( 'bridgedchannel' );
    $this->state = $s8_event->get( 'channelstatedesc' );
    $this->time = intval( $s8_event->get( 'seconds' ) );

    // get the dialed number by striping the dialing prefix from the extension
    if( !is_null( $s8_event->get( 'extension' ) ) )
    {
      $prefix = voip_manager::self()->get_prefix();
      $this->number = preg_replace( "/^$prefix/", '', $s8_event->get( 'extension' ) );
    }

    // get the peer from the channel which is in the form: SIP/<peer>-HHHHHHHH
    // (where <peer> is the peer (without < and >) and H is a hexidecimal number)
    $slash = strpos( $this->channel, '/' );
    $dash = strrpos( $this->channel, '-' );
    $this->peer = false === $slash || false === $dash || $slash >= $dash
                ? 'unknown'
                : substr( $this->channel, $slash + 1, $dash - $slash - 1 );
  }
    
  /**
   * Disconnects a call (does nothing if already disconnected).
   * This is a convenience method for voip_manager::hang_up()
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function hang_up()
  {
    voip_manager::self()->hang_up( $this );
  }
  
  /**
   * Starts recording (monitoring) the call.
   * This is a convenience method for voip_manager::start_monitoring()
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param string $file The file name the recorded call is to be saved under.
   * @access public
   */
  public function start_monitoring( $file )
  {
    voip_manager::self()->start_monitoring( $this, $file );
  }
  
  /**
   * Stops recording (monitoring) the call.
   * This is a convenience method for voip_manager::stop_monitoring()
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function stop_monitoring()
  {
    voip_manager::self()->stop_monitoring( $this );
  }
  
  /**
   * Get the call's peer
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_peer() { return $this->peer; }

  /**
   * Get the call's channel
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_channel() { return $this->channel; }

  /**
   * Get the call's bridged channel
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_bridge() { return $this->bridge; }

  /**
   * Get the call's state (Up, Ring, etc)
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_state() { return $this->state; }

  /**
   * Get the number called (the extension without dialing prefix)
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return string
   * @access public
   */
  public function get_number() { return $this->number; }

  /**
   * Get the call's time in seconds
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @return int
   * @access public
   */
  public function get_time() { return $this->time; }

  /**
   * The call's peer (should match system user name)
   * 
   * @var string
   * @access private
   */
  private $peer;

  /**
   * The call's channel.
   * 
   * @var string
   * @access private
   */
  private $channel = NULL;

  /**
   * The call's bridged channel.
   * 
   * @var string
   * @access private
   */
  private $bridge = NULL;

  /**
   * The state of the call (Up, Ring, etc)
   * 
   * @var string
   * @access private
   */
  private $state = NULL;

  /**
   * The number called (the extension without dialing prefix)
   * 
   * @var string
   * @access private
   */
  private $number = NULL;

  /**
   * The length of the call in seconds.
   * 
   * @var int
   * @access private
   */
  private $time = NULL;
}
?>
