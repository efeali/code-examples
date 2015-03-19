<?php

function tcs_plugin_setup_menu()
{
    add_menu_page('ABC Panel', 'ABC Panel', 'administrator', 'ABC-panel', 'ABC_home', "", 59);
    add_submenu_page('ABC-panel', 'ABC Settings', 'ABC Settings', 'administrator','ABC-settings', 'ABC_settings_page');
    add_submenu_page('ABC-panel',"Ads List","See Ads List","administrator",'ads-list','show_ads_list');

}


function ABC_home()
{

}

function ABC_settings_page()
{
    if (!current_user_can('administrator')) {
        wp_die(__('You don not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['gold-code'], $_POST['silver-code'], $_POST['bronze-code'], $_POST['mailchimp-api'], $_POST['mailchimp-campaign'], $_POST['paypal-token'], $_POST['adPaymentSuccess'])) {
        $gcode = $_POST['gold-code'];
        $scode = $_POST['silver-code'];
        $bcode = $_POST['bronze-code'];
        $mailchimpApi = esc_sql($_POST['mailchimp-api']);
        $mailchimpCampaign = esc_sql($_POST['mailchimp-campaign']);
        $paypalToken = esc_sql($_POST['paypal-token']);
        $paypalSandbox = $_POST['paypalSandbox'];
        $adPaymentSuccess = $_POST['adPaymentSuccess'];

        $gcode = stripslashes($gcode);
        $gcode = preg_replace('/<form.+>/', '', $gcode);
        $gcode = preg_replace('/<input type="image".+>/', '', $gcode);
        $gcode = preg_replace('/<img.+>/', '', $gcode);
        $gcode = preg_replace('/<\/form>/', '', $gcode);

        $scode = stripslashes($scode);
        $scode = preg_replace('/<form.+>/', '', $scode);
        $scode = preg_replace('/<input type="image".+>/', '', $scode);
        $scode = preg_replace('/<img.+>/', '', $scode);
        $scode = preg_replace('/<\/form>/', '', $scode);

        $bcode = stripslashes($bcode);
        $bcode = preg_replace('/<form.+>/', '', $bcode);
        $bcode = preg_replace('/<input type="image".+>/', '', $bcode);
        $bcode = preg_replace('/<img.+>/', '', $bcode);
        $bcode = preg_replace('/<\/form>/', '', $bcode);


        update_option('gold-ad-paypal-code', trim($gcode));
        update_option('silver-ad-paypal-code', trim($scode));
        update_option('bronze-ad-paypal-code', trim($bcode));
        update_option('mailchimp-api', $mailchimpApi);
        update_option('mailchimp-campaign', $mailchimpCampaign);
        update_option('paypal-token', $paypalToken);
        update_option('paypalSandbox', $paypalSandbox);
        update_option('adPaymentSuccessPage', $adPaymentSuccess)

        ?>
        <div class="updated"><p><strong><?php _e('Settings saved'); ?></strong></p></div>
    <?php

    }

    $gcode = get_option('gold-ad-paypal-code');
    $scode = get_option('silver-ad-paypal-code');
    $bcode = get_option('bronze-ad-paypal-code');
    $mailchimpApi = get_option('mailchimp-api');
    $mailchimpCampaign = get_option('mailchimp-campaign');
    $paypalToken = get_option('paypal-token');
    $paypalSandbox = get_option('paypalSandbox');
    $adPaymentSuccess = get_option('adPaymentSuccessPage');
    ?>
    <div class="wrap">
        <h1><?php _e('ABC settings'); ?></h1>

        <p><strong><?php _e("Please don't change anything unless you exactly know what are you doing!"); ?></strong></p>

        <form action="" method="post">
            <p><label>Mailchimp API code:</label></p>

            <p><input type="text" placeholder="Mailchimp API key" name="mailchimp-api" class="input-text"
                      style="width: 300px" value="<?php _e($mailchimpApi); ?>"></p>

            <p><label>Mailchimp campaign id:</label></p>

            <p><input type="text" placeholder="Mailchimp campaign id" name="mailchimp-campaign" class="input-text"
                      style="width: 300px" value="<?php _e($mailchimpCampaign); ?>"></p>

            <p><label>Paypal Account (Real/Sandbox) : </label></p>

            <p><input type="radio" <?php if ($paypalSandbox == true) echo 'checked' ?> name="paypalSandbox"
                      value="true"> Sandbox</p>

            <p><input type="radio" <?php if ($paypalSandbox == false) echo 'checked' ?> name="paypalSandbox"
                      value="false"> Real</p>

            <p><label>Paypal Token:</label></p>

            <p><input type="text" placeholder="Paypal token" name="paypal-token" class="input-text" style="width: 300px"
                      value="<?php _e($paypalToken); ?>"></p>

            <p><label>Gold ads paypal code :</label></p>

            <p><textarea name="gold-code" rows="5" cols="40" placeholder="Button code for gold ads payment"
                         class="textarea-comment"
                         style="max-width: 738px"><?php echo stripslashes($gcode); ?></textarea></p>

            <p><label>Silver ads paypal code :</label></p>

            <p><textarea name="silver-code" rows="5" cols="40" placeholder="Button code for silver ads payment"
                         class="textarea-comment"
                         style="max-width: 738px"><?php echo stripslashes($scode); ?></textarea></p>

            <p><label>Bronze ads paypal code :</label></p>

            <p><textarea name="bronze-code" rows="5" cols="40" placeholder="Button code for bronze ads payment"
                         class="textarea-comment"
                         style="max-width: 738px"><?php echo stripslashes($bcode); ?></textarea></p>

            <p><label>Advertisement Payment Success Page :</label></p>

            <p><input type="text" name="adPaymentSuccess" placeholder="Page name after last / in URL" class="input-text"
                      style="width: 300px" value="<?php _e($adPaymentSuccess); ?>"></p>

            <p class="submit"><input type="submit" value="<?php esc_attr_e('Save Changes'); ?>" class="button-primary">
            </p>
        </form>
    </div>
<?php
}

add_action('admin_menu', 'ABC_plugin_setup_menu');
#######################

function show_ads_list()
{
    if (!current_user_can('administrator')) {
        wp_die(__('You don not have sufficient permissions to access this page.'));
    }

    if(isset($_GET['detail']))
    {
        showAd(intval($_GET['detail']));
    }
    else
    {

        global $wpdb;

        $current_url = $_SERVER['SCRIPT_NAME'];
        $page = $_GET['page'];
        $baselink = $current_url."?page=".$page;
        $slink = $baselink."&filter=status";
        $plink = $baselink."&filter=payment";
        $nlink = $baselink."&filter=name";
        $emlink = $baselink."&filter=email";
        $alink = $baselink."&filter=adtype";
        $sdlink = $baselink."&filter=startdate";

        if(!isset($_GET['filter']))
        {
            $sql ="SELECT * FROM ABC_ads ORDER BY start_date DESC";
            $slink .="&order=asc";
            $plink .="&order=asc";
            $sdlink .="&order=asc";
        }
        elseif($_GET['filter']=="payment")
        {
            $sql = "SELECT * FROM ABC_ads ORDER BY payment_status";
            if(isset($_GET['order']))
            {
                switch($_GET['order'])
                {
                    case "asc": $sql .= " ASC"; $plink .="&order=desc"; break;
                    case "desc": $sql .= " DESC"; $plink .= "&order=asc"; break;
                }
            }
        }
        elseif($_GET['filter']=="status")
        {
            $sql = "SELECT * FROM ABC_ads ORDER BY exp_date";
            if(isset($_GET['order']))
            {
                switch($_GET['order'])
                {
                    case "asc": $sql .= " ASC"; $slink .="&order=desc"; break;
                    case "desc": $sql .= " DESC"; $slink .= "&order=asc"; break;
                }
            }
        }
        elseif($_GET['filter']=="adtype")
        {
            $sql = "SELECT * FROM ABC_ads ORDER BY ad_type";
            if(isset($_GET['order']))
            {
                switch($_GET['order'])
                {
                    case "asc": $sql .= " ASC"; $alink .="&order=desc"; break;
                    case "desc": $sql .= " DESC"; $alink .= "&order=asc"; break;
                }
            }
        }
        elseif($_GET['filter']=="startdate")
        {
            $sql = "SELECT * FROM ABC_ads ORDER BY start_date";
            if(isset($_GET['order']))
            {
                switch($_GET['order'])
                {
                    case "asc": $sql .= " ASC"; $sdlink .="&order=desc"; break;
                    case "desc": $sql .= " DESC"; $sdlink .= "&order=asc"; break;
                }
            }
        }



        $results = $wpdb->get_results($sql);
        ?>


        <div class="wrap">
        <h1><?php _e('List of Advertisements'); ?></h1>

        <p><strong><?php _e("Please don't change anything unless you exactly know what are you doing!"); ?></strong></p>


        <table class="adsTable">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th><a href="<?php echo $alink; ?>">Ad type</a></th>
                <th><a href="<?php echo $plink; ?>">Payment Status</a></th>
                <th><a href="<?php echo $slink; ?>">Status</a></th>
                <th><a href="<?php echo $sdlink; ?>">Start Date</a></th>
                <th>Gross Amount</th>
                <th>Net Amount</th>
            </tr>
            <?php
            foreach($results as $adObj)
            {
                $content ='<tr onclick="document.location=\''.$baselink.'&detail='.$adObj->ad_id.'\'">
            <td>'.$adObj->first_name.' '.$adObj->last_name.'</td>
            <td>'.$adObj->email.'</td>
            <td>'.$adObj->ad_type.'</td>
            <td>'.$adObj->payment_status.'</td>';

                $today = time();
                if($adObj->exp_date == NULL)
                    $content .= "<td class='pendingAd'>Pending Payment";
                elseif(strtotime($adObj->start_date) < $today && $today < strtotime($adObj->exp_date))
                    $content .="<td class='currentAd'>Currently On";
                elseif($today > strtotime($adObj->exp_date))
                    $content .="<td class='expiredAd'>Expired";
                else
                    $content .="Scheduled to future";
                $content .='</td><td>'.$adObj->start_date.'</td><td>'.$adObj->gross.'</td><td>'.$adObj->net.'</td></tr>';
                echo ($content);
            }

            ?>

        </table>
        </div>
        <?php
    }



}

function showAd($id)
{
    global $wpdb;

    $sql = "SELECT * FROM ABC_ads WHERE ad_id = ".$id;
    $result = $wpdb->get_results($sql);

    if(!empty($result))
    {
        ?>
        <div class="wrap adDetails">
        <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="button">&lt;- Back to list</a></p>

            <ul>
                <li><label>Advertisement ID </label>
                    <input type="text" disabled value="<?php echo $result[0]->ad_id; ?>"></li>
                <li><label>First Name </label>
                    <input type="text" disabled value="<?php echo $result[0]->first_name; ?>"></li>
                <li><label>Last Name </label>
                    <input type="text" disabled value="<?php echo $result[0]->last_name; ?>"></li>
                <li><label>Advertisement Type </label>
                    <input type="text" disabled value="<?php echo $result[0]->ad_type; ?>"></li>
                <li><label>Advertisement Date </label>
                    <ul>
                        <li><strong>From :</strong><input type="text" disabled value="<?php echo $result[0]->start_date; ?>"></li>
                        <li><strong>To :</strong> <input type="text" disabled value="<?php echo $result[0]->exp_date; ?>"></li>
                    </ul>
                </li>
                <li> <label>Payment </label>
                    <table>
                        <tr><td>Gross amount</td><td><input type="text" disabled value="<?php echo $result[0]->gross; ?>"></td></tr>
                        <tr><td>Paypal fee</td><td><input type="text" disabled value="<?php echo $result[0]->paypal_fee; ?>"></td></tr>
                        <tr><td>Net amount</td><td><input type="text" disabled value="<?php echo $result[0]->net; ?>"></td></tr>
                    </table></li>
                <li><label>Paypal Transaction ID </label>
                    <input type="text" disabled value="<?php echo $result[0]->transaction_id; ?>"></li>
                <li><label>Paypal Subscriber ID </label>
                    <input type="text" disabled value="<?php echo $result[0]->subscriber_id; ?>"></li>
                <li><label>Payment Status </label>
                    <input type="text" disabled value="<?php echo $result[0]->payment_status; ?>"></li>
                <li><label>Email </label>
                    <input type="text" disabled value="<?php echo $result[0]->email; ?>"></li>
                <li><label>Phone </label>
                    <input type="text" disabled value="<?php echo $result[0]->phone; ?>"></li>
                <li><label>Address </label>
                    <textarea cols="40" rows="5" disabled><?php echo $result[0]->address; ?></textarea></li>
                <li><label>City </label>
                    <input type="text" disabled value="<?php echo $result[0]->city; ?>"></li>
                <li><label>State </label>
                    <input type="text" disabled value="<?php echo $result[0]->state; ?>"></li>
                <li><label>Postal Code </label>
                    <input type="text" disabled value="<?php echo $result[0]->zip; ?>"></li>
            </ul>
        </div>
        <?php
    }
}

#######################

function register_my_settings()
{
    register_setting('ABC_options_group', 'gold-ad-paypal-code');
    register_setting('ABC_options_group', 'silver-ad-paypal-code');
    register_setting('ABC_options_group', 'bronze-ad-paypal-code');
    register_setting('ABC-options-group', 'mailchimp-api');
    register_setting('ABC-options-group', 'mailchimp-campaign');
    register_setting('ABC-options-group', 'paypal-token');
    register_setting('ABC-options-group', 'paypalSandbox');
    register_setting('ABC-options-group', 'adPaymentSuccessPage');
}

add_action('admin_init', 'register_my_settings');


########################

function ABC_ajaxTool()
{

}

?>