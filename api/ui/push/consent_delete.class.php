<?php
/**
 * consent_delete.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\push;
use cenozo\lib, cenozo\log;

/**
 * push: consent delete
 * 
 * @package beartooth\ui
 */
class consent_delete extends base_delete
{
  /**
   * Constructor.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param array $args Push arguments
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'consent', $args );
  }

  /**
   * Executes the push.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    // we'll need the arguments to send to mastodon
    $args = $this->arguments;

    // replace the consent id with a unique key
    $db_consent = $this->get_record();
    unset( $args['id'] );
    $args['noid']['participant.uid'] = $db_consent->get_participant()->uid;
    $args['noid']['consent.event'] = $db_consent->event;
    $args['noid']['consent.date'] = $db_consent->date;

    parent::finish();

    // now send the same request to mastodon
    $mastodon_manager = lib::create( 'business\cenozo_manager', MASTODON_URL );
    $mastodon_manager->push( 'consent', 'delete', $args );
  }
}
?>
