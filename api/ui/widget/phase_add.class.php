<?php
/**
 * phase_add.class.php
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
 * widget phase add
 * 
 * @package sabretooth\ui
 */
class phase_add extends base_view
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
    parent::__construct( 'phase', 'add', $args );
    
    // add items to the view
    $this->add_item( 'qnaire_id', 'hidden' );
    $this->add_item( 'sid', 'enum', 'Survey' );
    $this->add_item( 'rank', 'enum', 'Stage' );
    $this->add_item( 'repeated', 'boolean', 'Repeated' );
  }

  /**
   * Finish setting the variables in a widget.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    parent::finish();
    
    // this widget must have a parent, and it's subject must be a qnaire
    if( is_null( $this->parent ) || 'qnaire' != $this->parent->get_subject() )
      throw new exc\runtime(
        'Phase widget must have a parent with qnaire as the subject.', __METHOD__ );
    
    // create enum arrays
    $surveys = array();
    $modifier = new db\modifier();
    $modifier->where( 'active', '=', 'Y' );
    $modifier->where( 'anonymized', '=', 'N' );
    $modifier->where( 'tokenanswerspersistence', '=', 'Y' );
    foreach( db\limesurvey\surveys::select( $modifier ) as $db_survey )
      $surveys[$db_survey->sid] = $db_survey->get_title();
    $num_phases = $this->parent->get_record()->get_phase_count();
    $ranks = array();
    for( $rank = 1; $rank <= ( $num_phases + 1 ); $rank++ ) $ranks[] = $rank;
    $ranks = array_combine( $ranks, $ranks );
    end( $ranks );
    $last_rank_key = key( $ranks );
    reset( $ranks );

    // set the view's items
    $this->set_item( 'qnaire_id', $this->parent->get_record()->id );
    $this->set_item( 'sid', key( $surveys ), true, $surveys );
    $this->set_item( 'rank', $last_rank_key, true, $ranks );
    $this->set_item( 'repeated', false, true );

    $this->finish_setting_items();
  }
}
?>
