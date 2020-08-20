<?php

namespace DevignersPlace\CaseTracker\Includes;

/**
 * The Class to take care of performing some random and conventional tasks
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/Includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class TasksPerformer
{
    /**
     * Writes an Error or Logs a text to a file
     *
     * @param $text The String to be written to the log file.
     * @since  1.0.0
     * @access public
     * @return void
     */
    public static function writeToLog($text)
    {
        $dfile = WP_CONTENT_DIR.'/plugins/case-tracker/logs/logs.txt';
        $myfile = fopen($dfile, "a");
        fwrite($myfile, $text);
        fclose($myfile);
    }

    /**
     * Enqueues FontAwesome
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueueFontawesome()
    {
        wp_enqueue_style(
            'fontawesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css',
            '',
            '5.14.0',
            'all'
        );
    }

    /**
     * Custom Css for the icon used for the court-case custom post type.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function courtCaseCPTCustomCss()
    {
        echo "<style type='text/css' media='screen'>
            #adminmenu .menu-icon-josbiz-court-case div.wp-menu-image.svg {
                transform: scale(1.3);
            }
        </style>";
    }

    /**
      * Returns the full name of a registered User
     *
     * @param $user_id The ID of the registered User.
     * @since  1.0.0
     * @access public
     * @return string
     */
    public static function getUserFullName($user_id)
    {
        if (!empty($user_id)) {
            $user_object = get_user_by('id', $user_id);
            return $user_object->data->display_name;
        } else {
            return null;
        }
    }

    /**
      * Returns the Email of a registered User
     *
     * @param $user_id The ID of the registered User.
     * @since  1.0.0
     * @access public
     * @return string
     */
    public static function getUserEmail($user_id)
    {
        if (!empty($user_id)) {
            $user_object = get_user_by('id', $user_id);
            return $user_object->data->user_email;
        } else {
            return null;
        }
    }

    /**
     * Send an Email using the WordPress wp_mail function
     *
     * @param $email_body           The content of the email.
     * @param $email_heading_inside The Email heading used within the email when opened.
     * @param $heading_main         The Email heading displayed when the email is not opened yet.
     * @param $recipient            The Email address where the mail is to be sent.
     * @since  1.0.0
     * @access public
     * @return void
     */
    public static function sendEmail($email_body, $email_heading_inside, $heading_main, $recipient)
    {
        $mail_content = self::createEmailBody($email_heading_inside, $email_body);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        return wp_mail($recipient, $heading_main, $mail_content, $headers);
    }


    /**
     * Create the Email to be sent using the html template
     *
     * @param $email_heading A form of heading used within the email when opened.
     * @param $mail_content The content of the email.
     * @since  1.0.0
     * @access public
     * @return html
     */
    public static function createEmailBody($email_heading, $mail_content)
    {
        $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $output .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $output .= '<head>';
        $output .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $output .= '<title>State Case Tracker</title>';
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
        $output .= '<link href="https://fonts.googleapis.com/css?family=Cabin:400&display=swap" rel="stylesheet">';
        $output .= '</head>';
        $output .= '<body style="margin:0; padding: 0; font-family: '.'Cabin'.', sans-serif;">';
        $output .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #fff; min-height: 100vh">';
        $output .= '<tr>';
        $output .= '<td style="display: block;">';
        $output .= '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"';
        $output .= 'style="background: #fff; min-height: 85vh; padding: 40px 0; border-radius: 0 40px;';
        $output .= 'margin: 20px auto; border-collapse: collapse;">';
        $output .= '<tr>';
        $output .= '<td>';
        $output .= '<img src="https://res.cloudinary.com/ministry-of-trade-industry-and-investment/image/upload/v1587394399/mtii_logo_lmgsnf.jpg"';
        $output .= 'alt="mtii-logo" width="200px" style="margin: 20px auto; display: block;"';
        $output .= '/>';
        $output .= '<h2 style="margin: 0; padding-left: 0px; font-size: 18px;">'.$email_heading.'</h2>';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr>';
        $output .= '<td style="padding: 10px 0; font-size: 15px; font-family: '.'Cabin'.', sans-serif;">';
        $output .= $mail_content;
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr>';
        $output .= '<td style="padding: 10px 0; font-size: 15px; font-family: '.'Cabin'.', sans-serif;">';
        $output .= 'Warmest Regards';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr>';
        $output .= '<td style="display: block; margin-top:-22px; padding-left: 0px; font-size: 12px;">';
        $output .= '<em style="font-family: '.'Cabin'.', sans-serif;">Case Tracker Admin</em>';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"';
        $output .= 'style="display: block; background: #f9f9f9; padding: 10px 0; border-radius: 10px; width:100%;';
        $output .= 'margin: 20px auto; border-collapse: collapse;">';
        $output .= '<img src="https://res.cloudinary.com/ministry-of-trade-industry-and-investment/image/upload/v1587394399/mtii_logo_lmgsnf.jpg"';
        $output .= 'alt="mtii-logo" width="50px" style="margin: 10px 20px; display: block;"';
        $output .= '/>';
        $output .= '<tr style="display:block; width: 100%; margin: 10px 20px; margin-bottom: 0px">';
        $output .= '<td style="padding: 10px; font-size: 12px; color: #c9c9c9;">';
        $output .= '<hr style="display: block; width: 100%; border: 0; height: 2px; margin: 5px auto; background: #c9c9c9;" />';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr style="display:block; width: 100%; margin: 10px 20px; margin-bottom: 0px">';
        $output .= '<td style="padding: 10px; font-size: 12px; color: #c9c9c9;">';
        $output .= 'Case Tracker . | +23480192384584';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr style="display:block; width: 100%; margin: 10px 20px; margin-top: 0px">';
        $output .= '<td style="padding: 0 10px; font-size: 12px; color: #c9c9c9;">';
        $output .= 'state@statecasetravker.gov.ng';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '</body>';
        $output .= '</html>';

        return $output;
    }
}
