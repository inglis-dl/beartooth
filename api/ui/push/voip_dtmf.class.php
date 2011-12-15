<?php
/**
 * voip_dtmf.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\push;
use cenozo\lib, cenozo\log, beartooth\util;

/**
 * push: voip dtmf
 *
 * Changes the current user's theme.
 * Arguments must include 'theme'.
 * @package beartooth\ui
 */
class voip_dtmf extends \cenozo\ui\push
{
  /**
   * Constructor.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args Push arguments
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'voip', 'dtmf', $args );
  }
  
  /**
   * Executes the push.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    $voip_call = lib::create( 'business\voip_manager' )->get_call();
    if( is_null( $voip_call ) )
      throw lib::create( 'exception\notice',
        'Unable to send tone since you are not currently in a call.', __METHOD__ );

    lib::create( 'business\voip_manager' )->get_call()->dtmf( $this->get_argument( 'tone' ) );
  }
}
?>
