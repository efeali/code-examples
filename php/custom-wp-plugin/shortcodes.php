<?php
function registrationForm()
{
  if (!isset($_GET['add'])) {
    $content = do_shortcode('[tagline_box backgroundcolor="" shadow="no" shadowopacity="0.1" border="1px" bordercolor="" highlightposition="bottom" content_alignment="left" link="" linktarget="_self" modal="" button_size="" button_shape="" button_type="" buttoncolor="" button="" title="To become a member please fill out the form below and pay your membership fee" description="" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""][/tagline_box]');

    include_once(ABSPATH . "wp-admin/includes/plugin.php");
    if (is_plugin_active('ABC-panel/ABC-panel.php')) {

      $content .= '
      <form action="'.plugins_url().'/ABC-panel/handler.php" method="post">
        <p>
          <input type="text" name="first_name" maxlength="32" placeholder="'.__('First Name (required)', 'Avada').'"
                 aria-required="true" required="required" class="input-name">
        </p>

        <p><input type="text" name="last_name" maxlength="32" placeholder="'.__('Last Name (required)', 'Avada').'"
                  aria-required="true" required="required" class="input-name"></p>

        <p><input type="text" name="email" maxlength="127" placeholder="'.__('Email (required)', 'Avada').'"
                  aria-required="true" class="input-name" required="required"></p>

        <p><input type="text" name="pass" placeholder="'.__('Pick a Password', 'Avada').'"
                  aria-required="false" class="input-name"></p>

        <p><input type="text" name="phone" placeholder="'.__('Phone (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><input type="text" name="address1" maxlength="100" placeholder="'.__('Address (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><input type="text" name="city" maxlength="40" placeholder="'.__('City (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><input type="text" name="state" maxlength="2" placeholder="'.__('State (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><input type="text" name="zip" maxlength="32" placeholder="'.__('Postal Code ex: V6G 1A1', 'Avada').'"
                  aria-required="false" class="input-name"></p>

        <p><input type="checkbox" name="optin" class="input-name" value="1"> Opt In?</p>
        <p><label for="memberType">Membership Type : </label> <select name="memberType">
            <option value="ordinary">Ordinary</option>
            <option value="student">Student</option>
            <option value="senior">Senior</option>
          </select></p>

        <p><label for="paymentType">Payment Type : </label> <select name="paymentType">
            <option value="once">via PayPal - Only 1 Year</option>
            <option value="subscribe">via PayPal - Annual Subscription</option>
            <option value="cheque">By Cheque</option>
          </select></p>

        <input type="submit" value="Register Now" class="button-large registerBtn">

      </form>';

      return $content;
    } else {
      echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="" icon="" box_shadow="yes" animation_type="slide" animation_direction="down" animation_speed="" class="" id=""]You have to install ABC-panel plugin first[/alert]');

    }
  } // end of if isset $_GET['add']
}

add_shortcode('ABC-registration-form',registrationForm);


function signupAds()
{
  if (!isset($_GET['signup'])) {

    include_once(ABSPATH . "wp-admin/includes/plugin.php");
    if (is_plugin_active('ABC-panel/ABC-panel.php')) {
      $content = '<br/><br/>
      <form action="'.plugins_url().'/ABC-panel/handler.php" method="post">
        <p>
          <label>First Name </label><input type="text" name="first_name" maxlength="32" placeholder="'.__('(required)', 'Avada').'"
                 aria-required="true" required="required" class="input-name">
        </p>

        <p><label>Last Name </label><input type="text" name="last_name" maxlength="32" placeholder="'.__('(required)', 'Avada').'"
                  aria-required="true" required="required" class="input-name"></p>

        <p><label>Email</label><input type="text" name="email" maxlength="127" placeholder="'.__('(required)', 'Avada').'"
                  aria-required="true" class="input-name" required="required"></p>

        <p><label>Contact Phone </label><input type="text" name="phone" placeholder="'.__('Ex. 604 123 4567(required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><label>Address </label><input type="text" name="address1" maxlength="100" placeholder="'.__('(required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><label>City </label><input type="text" name="city" maxlength="40" placeholder="'.__('Ex. Vancouver (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><label>State </label><input type="text" name="state" maxlength="2" placeholder="'.__('Ex. BC (required)', 'Avada').'"
                  aria-required="true" class="input-name"></p>

        <p><label>Postal Code </label><input type="text" name="zip" maxlength="32" placeholder="'.__('Ex: V6G 1A1', 'Avada').'" aria-required="false" class="input-name"></p>

        <p><label for="ads-type">Ads Type : </label> <select name="ads-type">
            <option value="gold"';
      if(isset($_GET['gold']))
        $content .= 'selected="selected"';
      $content .='>Gold Ads</option>
            <option value="silver"';
      if(isset($_GET['silver']))
        $content .= ' selected="selected"';
      $content .='>Silver Ads</option>
            <option value="bronze"';
      if(isset($_GET['bronze']))
        $content .=' selected="selected"';
      $content .='>Bronze Ads</option>
          </select></p>

        <input type="submit" value="Signup Now" class="button-large registerBtn">

      </form>';
      return $content;


    } else {
      echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="" icon="" box_shadow="yes" animation_type="slide" animation_direction="down" animation_speed="" class="" id=""]You have to install ABC-panel plugin first[/alert]');

    }
  } // end of if isset $_GET['add']
}

add_shortcode('ads-signup-form','signupAds');


function memberRegisterResult()
{
  if(isset($_GET['add']))
  {
    if($_GET['add']=="ok")
    {
      if(isset($_GET['cheque']))
      {
        echo do_shortcode('[alert type="success" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "You have successfully registered.<br/>Please mail your cheque for payment to us. Your membership will be activated once we receive your payment.<br/><br/>Thank you.", "Avada" ) . '[/alert]' );
      }
      else
      {
        echo do_shortcode('[alert type="success" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "You have successfully registered", "Avada" ) . '[/alert]' );
      }

    }
    else if($_GET['add']=="notcomplete")
    {
        echo do_shortcode('[alert type="notice" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "We have successfully created your account but payment was not completed! If this was unexpected please contact with us and we will check our records.<br/>Thank you", "Avada" ) . '[/alert]' );
    }
    else if($_GET['add']=="error")
    {
      if(isset($_GET['cheque']))
      {
        echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "You have been registered, but there was a problem with saving your payment records. Please contact us at info@ABC.org", "Avada" ) . '[/alert]' );
      }
      else
      {
        echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "Registration has failed. Please contact us at info@ABC.org", "Avada" ) . '[/alert]' );
      }

    }
    else if($_GET['add']=='exist')
    {
      echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "We already have you registered in our database.<br/><br/> If this is a mistake and you haven't registered before please contact us at info@ABC.org", "Avada" ) . '[/alert]' );
    }

  }

}

add_shortcode('memberRegisterResult','memberRegisterResult');



function adPaymentResult()
{
  if(isset($_GET['signup']))
  {
    if($_GET['signup']=="ok")
    {
      echo do_shortcode('[alert type="success" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "Payment Successful!", "Avada" ) . '[/alert]' );
    }
    else if($_GET['signup']=="fail")
    {
      echo do_shortcode('[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="bounce" animation_direction="down" animation_speed="0.1" class="" id=""]' . __( "Payment has failed. Please contact us at info@ABC.org", "Avada" ) . '[/alert]' );
    }
  }

}
add_shortcode('adPaymentResult','adPaymentResult');



########## SOCIAL ICONS ALI VERSION

function ABCSocial()
{
    echo do_shortcode('[social_links icons_boxed="" icons_boxed_radius="" icon_colors="#333333" box_colors="#dfe3ee" tooltip_placement="" rss="" facebook="https://www.facebook.com/ABCVancouver" twitter="" dribbble="" google="" linkedin="" blogger="" tumblr="" reddit="" yahoo="" deviantart="" vimeo="" youtube="" pinterest="" digg="" flickr="" forrst="" myspace="" skype="" paypal="" dropbox="" soundcloud="" vk="" email="community@ABC.org" show_custom="" class="" id="ABC-social-icons"]');
}

add_shortcode('ABCSocial','ABCSocial');


########## WPML LANGUAGE SELECTOR MY VERSION

function language_selector_flags(){
    $languages = icl_get_languages('skip_missing=0');
    if(!empty($languages)){
        echo '<div id="flags_language_selector"><ul>';
        foreach($languages as $l){
            echo '<li>';
            if(!$l['active']) echo  '<a href="'.$l['url'].'">';
            echo '<img src="'.$l['country_flag_url'].'" height="14" alt="'.$l['language_code'].'" width="21" /> '.$l['native_name'];
            if(!$l['active']) echo '</a>';

            echo '</li>';
        }
        echo '</ul></div>';
    }
}
add_shortcode('languageSelector','language_selector_flags');

?>