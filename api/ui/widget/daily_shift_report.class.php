<?php
/**
 * daily_shift_report.class.php
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
 * widget daily shift report
 * 
 * @package beartooth\ui
 */
class daily_shift_report extends base_report
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
    parent::__construct( 'daily_shift', $args );

    $this->set_variable( 'description',
      'This report is for supervisors to complete at the end of their shift for remittance to the '.
      'NCC on a daily basis. The report includes operator activity data with operators '.
      'subclassified by language.  Areas are provided for questions/concerns and additional '.
      'comments.' );

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
