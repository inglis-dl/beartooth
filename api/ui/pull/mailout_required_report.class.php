<?php
/**
 * mailout_required_report.class.php
 * 
 * @author Dean Inglis <inglisd@mcmaster.ca>
 * @filesource
 */

namespace beartooth\ui\pull;
use cenozo\lib, cenozo\log, beartooth\util;

/**
 * Mailout required report data.
 * 
 * @abstract
 */
class mailout_required_report extends \cenozo\ui\pull\base_report
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
    parent::__construct( 'mailout_required', $args );
  }

  /**
   * Builds the report.
   * @author Dean Inglis <inglisd@mcmaster.ca>
   * @author Val DiPietro <dipietv@mcmaster.ca>
   * @author Patrick Emond <emondpd@mcmaster.ca>
   * @access protected
   */
  protected function build()
  {
    $participant_class_name = lib::get_class_name( 'database\participant' );
    $tokens_class_name = lib::get_class_name( 'database\limesurvey\tokens' );
    $survey_class_name = lib::get_class_name( 'database\limesurvey\survey' );
    $source_class_name = lib::get_class_name( 'database\source' );
    $phase_class_name = lib::get_class_name( 'database\phase' );
    $consent_class_name = lib::get_class_name( 'database\consent' );

    // get the report arguments
    $restrict_site_id =   $this->get_argument( 'restrict_site_id', 0 );
    $restrict_source_id = $this->get_argument( 'restrict_source_id' );

    $db_qnaire = lib::create( 'database\qnaire', $this->get_argument( 'restrict_qnaire_id' ) );

    // prepare the date items
    $report_date = util::get_datetime_object()->format( 'Y-m-d' );

    // the question which we need to check
    $question_code = 'INT_5';

    if( $restrict_source_id )
    {
      $db_source = lib::create( 'database\source', $restrict_source_id );
      $source_title = $db_source->name;
    }
    else
    {
      $source_title = "All Sources";
    }

    $this->add_title(
      'Participant Information Package Required Report For '.strtoupper( $source_title ) );
    $this->add_title(
      sprintf( 'Listing of those who requested a new information package during '.
               'the %s interview', $db_qnaire->name ) ) ;

    // specify the type of consents we would like to avoid
    $consent_types = array(
      'written accept',
      'verbal deny',
      'written deny',
      'retract',
      'withdraw' );

    $contents = array();

    // filtering participants according to widget
    $participant_mod = lib::create( 'database\modifier' );
    if( $restrict_site_id )
    $participant_mod->where( 'participant_site.site_id', '=', $restrict_site_id );
    if( $restrict_source_id ) $participant_mod->where( 'source_id', '=', $restrict_source_id );
    // $participant_mod->where( 'consent.event', 'not in', $consent_types );
    $participant_mod->where( 'status', '=', NULL );
    $participant_mod->where( 'interview.qnaire_id', '=', $db_qnaire->id );
    $participant_mod->where_bracket( true );
    $participant_mod->where( 'participant_last_consent.consent_id', '=', NULL );
    $participant_mod->where_bracket( true, true );
    $participant_mod->where( 'participant_last_consent.accept', '=', true );
    $participant_mod->where( 'participant_last_consent.written', '=', false );
    $participant_mod->where_bracket( false );
    $participant_mod->where_bracket( false );
    $participant_mod->group( 'participant.id' );

    // get the survey id for the qnaire's first phase
    $db_phase = $phase_class_name::get_unique_record(
      array( 'qnaire_id', 'rank' ),
      array( $db_qnaire->id, 1 ) );
    $survey_class_name::set_sid( $db_phase->sid );

    foreach( $participant_class_name::select( $participant_mod ) as $db_participant )
    {
      $interview_mod = lib::create( 'database\modifier' );
      $interview_mod->where( 'qnaire_id', '=', $db_qnaire->id );
      $db_participant->get_interview_list( $interview_mod );
      $db_interview = current( $db_participant->get_interview_list( $interview_mod ) );

      // figure out the token and from that get this participant's surveys
      $survey_mod = lib::create( 'database\modifier' );
      $survey_mod->where( 'token', 'LIKE', $db_interview->id.'_%' );
      $survey_mod->order_desc( 'startdate' );

      // go through each survey response and check to see if the question code has been set
      $include_participant = false;
      $answer_date = "";
      foreach( $survey_class_name::select( $survey_mod ) as $db_survey )
      {
        if( 'YES' == $db_survey->get_response( $question_code ) ||
            'NO'  == $db_survey->get_response( $question_code ) )
        { // question was answered, include the participant (no need to keep searching)
          $include_participant = true;
          $answer_date = util::from_server_datetime( $db_survey->startdate, 'Y-m-d' );
          break;
        }
      }

      if( $include_participant )
      {
        $db_address = $db_participant->get_first_address();
        if( is_null( $db_address ) ) continue;
        $db_region = $db_address->get_region();

        $contents[] = array(
          $db_participant->language,
          $db_participant->uid,
          $db_participant->first_name,
          $db_participant->last_name,
          $db_address->address1,
          $db_address->address2,
          $db_address->city,
          $db_region->abbreviation,
          $db_region->country,
          $db_address->postcode,
          $report_date,
          $answer_date );
        }
    }// end participant loop

    $header = array(
      "Language",
      "UID",
      "First Name",
      "Last Name",
      "Address",
      "Address2",
      "City",
      "Prov/State",
      "Country",
      "Postcode",
      "Date of Report",
      "Date Answered" );

    $this->add_table( NULL, $header, $contents, NULL );
  }
}
?>