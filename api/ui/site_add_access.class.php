<?php
/**
 * site_add_access.class.php
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package sabretooth\ui
 * @filesource
 */

namespace sabretooth\ui;

/**
 * widget site add_access
 * 
 * @package sabretooth\ui
 */
class site_add_access extends base_add_access
{
  /**
   * Constructor
   * 
   * Defines all variables which need to be set for the associated template.
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param string $name The name of the operation.
   * @param array $args An associative array of arguments to be processed by the widget
   * @access public
   */
  public function __construct( $args )
  {
    parent::__construct( 'site', $args );
    
    // This widget is special.  We need a list of users and roles, not an access list, so we
    // override the construction of the list_widget performed by base_add_list's constructor.
    $this->list_widget = new user_list( $args );
    $this->list_widget->set_parent( $this, 'edit' );
    $this->list_widget->set_heading( 'Choose users to grant access to the site' );
  }

  /**
   * Overrides the role list widget's method.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param database\modifier $modifier Modifications to the list.
   * @return int
   * @access protected
   */
  public function determine_role_count( $modifier = NULL )
  {
    // we want to display all roles
    return \sabretooth\database\role::count( $modifier );
  }

  /**
   * Overrides the role list widget's method.
   * 
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @param database\modifier $modifier Modifications to the list.
   * @return array( active_record )
   * @access protected
   */
  public function determine_role_list( $modifier = NULL )
  {
    // we want to display all roles
    return \sabretooth\database\role::select( $modifier );
  }
}
?>
