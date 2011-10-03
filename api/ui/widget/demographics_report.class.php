<?php
/**
 * demographics_report.class.php
 * 
 * @author Dean Inglis <inglisd@mcmaster.ca>
 * @package beartooth\ui
 * @filesource
 */

namespace beartooth\ui\widget;
use beartooth\log, beartooth\util;
use beartooth\business as bus;
use beartooth\database as db;
use beartooth\exception as exc;

/**
 * widget demographics report
 * 
 * @package beartooth\ui
 */
class demographics_report extends base_report
{
  /**
   * Constructor
   * 
   * Defines all variables which need to be set for the associated template.
   * @author Dean Inglis <inglisd@mcmaster.ca>
   * @param array $args An associative array of arguments to be processed by the widget
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'demographics', $args );

    $this->add_restriction( 'site' );
    $this->add_restriction( 'qnaire' );
    $this->add_restriction( 'consent' );
    $this->add_restriction( 'province' );
    
    $this->set_variable( 'description',
      'This report lists participant demographics.  The report can be moderated by site, '.
      'questionnaire, province and consent status.' );
  }

  /**
   * @author Dean Inglis <inglisd@mcmaster.ca>
   * @access public
   */
  public function finish()
  {
    parent::finish();
  
    $this->finish_setting_parameters();
  }
}
?>
