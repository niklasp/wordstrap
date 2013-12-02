<?php

add_action(
    'init',
    array ( Wordstrap_Contact::get_instance(), 'init' )
);

class Wordstrap_Contact
{
  protected static $instance = NULL;
    public function __construct() {}

    public static function get_instance()
    {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }

    public function init() {
      add_shortcode('contact', array( $this, 'wordstrap_contact_form'));  
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_contact_scripts' ) );
        add_action( 'wp_ajax_handle_ajax_post', array( $this, 'handle_ajax_post' ) );
        add_action( 'wp_ajax_nopriv_handle_ajax_post', array( $this, 'handle_ajax_post' ) );
    }

    public function enqueue_contact_scripts() 
    {
        wp_enqueue_script( 
             'ajax-contact-form' , get_template_directory_uri() . '/inc/securemail.js', array( 'jquery' )
        );
        # Here we send PHP values to JS
        wp_localize_script( 
             'ajax-contact-form' 
            , 'wp_ajax' 
            , array( 
                 'ajaxurl'      => admin_url( 'admin-ajax.php' ) 
                , 'ajaxnonce'   => wp_create_nonce( 'ajax_form_post_validation' ) 
                , 'loading'    => 'http://i.stack.imgur.com/drgpu.gif'
            ) 
        );      
    }

    private function get_user_ip() {
      // function to get the IP address of the user
      if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
      }
      elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
      }
      else {
        return $_SERVER["REMOTE_ADDR"];
      }
  }
  

  private function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function handle_ajax_post() {
    
    check_ajax_referer( 'ajax_form_post_validation', 'security' );
      
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $error = false;

    parse_str($_POST['data'], $data);

    foreach ($data as $field => $value) {
      if (get_magic_quotes_gpc()) {
        $value = test_input($value);
      }
      $form_data[$field] = $value;
    }

      // if ( ! ( $form_data['email'] ) ) {
      //     wp_send_json_error( array( 'error' => __( 'Please enter a proper email.', 'wordstrap' ) ) );
      //     $error = true;
      // }
 
    if ($error == false) {
      $email = get_bloginfo('admin_email');
      $email_subject = $form_data['subject'];
      $email_message = $form_data['message'] . "\n\nIP: " . Wordstrap_Contact::get_instance()->get_user_ip();
      $headers  = "From: ".$form_data['name']." <".$form_data['email'].">\n";
      $headers .= "Content-Type: text/plain; charset=UTF-8\n";
      $headers .= "Content-Transfer-Encoding: 8bit\n";
      mail($email, $email_subject, $email_message,$headers) or die('Error sending Mail'); //This method sends the mail.
      
      if ($form_data['copymail'] === "yes") 
      {
        mail($form_data['email'], $email_subject, $email_message,$headers) or die('Error sending Mail'); 
      }
      
      $sent = true;
    }
    wp_send_json_success();
    }
  }

  public function wordstrap_contact_form($atts) {

  $result = "";
  $sent = false;
  $info = "";

  extract(shortcode_atts(array(
    "title" => '',
    "email" => get_bloginfo('admin_email'),
    "subject" => '',
    "label_name" => 'Your Name',
    "label_email" => 'Your E-mail Address',
    "label_subject" => 'Subject',
    "label_copy" => '',
    "label_message" => 'Your Message',
    "label_cancel" => 'Cancel',
    "label_submit" => 'Submit',
    "error_empty" => 'Please fill in all the required fields.',
    "error_noemail" => 'Please enter a valid e-mail address.',
    "success" => 'Thanks for your e-mail! We\'ll get back to you as soon as we can.'
  ), $atts));

  $email_form = '<div id="resultmessage" data-error-empty="'. $error_empty .'" 
    data-error-nomail="'. $error_noemail .'" data-success="'. $success .'"></div>
        <form class="bs-example form-horizontal" id="contactform" action="'. get_template_directory_uri() . '/inc/securemail.php' . '" method="post">
                <fieldset>';
                if ($title != '') $email_form .= '<legend>' . $title . '</legend>';
                $email_form .=
                  '<div class="form-group">
                    <label for="inputName" class="col-lg-2 control-label">'. $label_name .'</label>
                    <div class="col-lg-10">
                      <input class="form-control" id="inputName" name="name" placeholder="'. $label_name .'" minlength="3" required="" type="text">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSubject" class="col-lg-2 control-label">'. $label_subject .'</label>
                    <div class="col-lg-10">
                      <input class="form-control" id="inputSubject" name="subject" placeholder="'. $label_subject .'" minlength="3" required="" type="text">
                    </div>
                  </div>                  
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">'. $label_email .'</label>
                    <div class="col-lg-10">
                      <input class="form-control" id="inputEmail" name="email" placeholder="'. $label_email .'" required="" type="email">';
                      if ($label_copy != '') $email_form .= 
                      '<div class="checkbox">
                        <label>
                          <input name="copymail" value="yes" type="checkbox"> '. $label_copy .'
                        </label>
                      </div>';
                      $email_form .= 
                    '</div>
                  </div>
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">'. $label_message .'</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="5" id="textArea" name="message" required=""></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                      <button class="btn btn-default">'. $label_cancel .'</button> 
                      <button type="submit" id="submit" class="btn btn-success">'. $label_submit .'</button> 
                    </div>
                  </div>
                </fieldset>
              </form>';
  
    return $email_form;
  } 

}

?>