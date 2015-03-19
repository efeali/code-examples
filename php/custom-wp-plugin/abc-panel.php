<?php
/**
Plugin Name: abc Panel Plugin
Description: This plugin used for managing abc website, written by Ali Efe. It handles member registration and payment, advertisement registration and payment, listening paypal messages to keep track of transactions as well as storing important data related with all of these. Please DO NOT REMOVE OR DISABLE this plugin unless you are sure what you are doing. Contact Ali if you have any questions at efeali@gmail.com or info@aewebdevelopment.com
Author: Ali Efe
Author URI: http://aewebdevelopment.com
Author Email: info@aewebdevelopment.com
Version: 1.0
 */
require("abc-config.php");
require("abc-widgets.php");

class mydb
{
  public $dblink, $dbname, $dbhost, $dbuser, $dbpass, $error;
  public $results = array(), $resultsCount;

  public function __construct()
  {
    $this->dbhost = DBHOST;
    $this->dbname = DBNAME;
    $this->dbpass = DBPASS;
    $this->dbuser = DBUSER;

    $this->connect();

  }

  public function connect()
  {
    if ($this->dblink = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname)) {
      return true;
    } else {
      $this->error = "DB ERROR : " . mysqli_error($this->dblink);
      return false;
    }

  }

  public function query($sql)
  {
    # this function will return TRUE (number) , FALSE. If there is a resultset it will populate results property
    if ($result = mysqli_query($this->dblink, $sql)) {
      if (is_object($result)) {
        while ($row = mysqli_fetch_array($result)) {
          array_push($this->results, $row);
        }
      }
      return $result;
    } else {
      $this->error = "query error :" . mysqli_error($this->dblink);
      return false;
    }
  }

  public function sqlEscape($content)
  {
    return mysqli_real_escape_string($this->dblink, $content);
  }

}// end of class mydb



function createTables()
{

  $myObj = new mydb();
  $sql = "SELECT MAX(mm_no) AS membermax FROM memberm";
  if($myObj->query($sql))
    $memberNextID = ($myObj->results[0]['membermax'])+1;
  else
    $memberNextID = 1;

  $sql = "SELECT MAX(mp_no) AS paymentmax FROM memberp";
  if($myObj->query($sql))
    $paymentNextID = ($myObj->results[0]['paymentmax'])+1;
  else
    $paymentNextID = 1;

  global $wpdb;

  $sqlMember = "CREATE TABLE IF NOT EXISTS `memberm` (
  `mm_no` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial Number',
  `mm_email` varchar(50) NOT NULL COMMENT 'email mandatory',
  `mm_upass` varchar(20) NOT NULL DEFAULT 'abc123' COMMENT '50',
  `mm_fname` varchar(50) NOT NULL DEFAULT '''''' COMMENT 'First Name',
  `mm_lname` varchar(50) NOT NULL DEFAULT '''''' COMMENT 'Last Name',
  `mm_phone` varchar(20) DEFAULT NULL,
  `mm_address1` varchar(200) DEFAULT NULL COMMENT 'address1',
  `mm_address2` varchar(200) DEFAULT NULL COMMENT 'address2',
  `mm_city` varchar(200) DEFAULT '\"Vancouver\"' COMMENT 'city',
  `mm_state` varchar(50) DEFAULT NULL COMMENT 'state',
  `mm_zip` varchar(10) DEFAULT NULL COMMENT 'zip',
  `mm_regdate` date NOT NULL COMMENT 'membership date',
  `mm_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'status',
  `mm_level` tinyint(1) DEFAULT '0' COMMENT '1 for admins only',
  `mm_refered` varchar(50) DEFAULT '\"None\"' COMMENT 'who referred',
  `mm_type` enum('Single','Family','Student','Senior','YK','Other') NOT NULL DEFAULT 'Single' COMMENT 'REG for regular YK for managment',
  `mm_optin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'has he/she opted in',
  `mm_optindate` date DEFAULT NULL COMMENT 'when opted in',
  `mm_dob` date DEFAULT NULL,
  `mm_crdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mm_shw_email` tinyint(1) DEFAULT '0',
  `mm_rep` varchar(20) DEFAULT NULL,
  `mm_notes` text,
  `mm_optin_ip` varchar(20) DEFAULT NULL,
  `mm_confirmed` tinyint(1) DEFAULT '0',
  `mm_tmp` varchar(50) DEFAULT NULL,
  `mm_muhtar` tinyint(1) DEFAULT '0',
  `mm_bilet` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`mm_no`),
  KEY `idx_fname` (`mm_fname`),
  KEY `idx_lname` (`mm_lname`),
  KEY `idx_email` (`mm_email`),
  KEY `idx_city` (`mm_city`),
  KEY `idx_refered` (`mm_refered`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 PACK_KEYS=0 ROW_FORMAT=COMPACT AUTO_INCREMENT=".$memberNextID.";";

  $sqlPayment = "CREATE TABLE IF NOT EXISTS `memberp` (
  `mp_no` int(11) NOT NULL AUTO_INCREMENT,
  `mm_no` int(11) NOT NULL,
  `mp_paytype` enum('PayPal','Cash','Other','Cheque') NOT NULL,
  `mp_date` date NOT NULL,
  `mp_exp` varchar(150) DEFAULT NULL,
  `mp_credit` float(9,3) NOT NULL DEFAULT '0.000',
  `mp_debit` float(9,3) NOT NULL DEFAULT '0.000',
  `mp_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mp_notes` varchar(200) DEFAULT NULL,
  `current_year` int(4) DEFAULT NULL,
  `payc` float(9,3) DEFAULT NULL COMMENT 'amount falls in to current year',
  `next_year` int(4) DEFAULT NULL,
  `payn` float(9,3) DEFAULT NULL COMMENT 'amount left for next year',
  PRIMARY KEY (`mp_no`),
  KEY `mm_no` (`mm_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=".$paymentNextID.";";

  $sqlPayment_2 = "ALTER TABLE `memberp`
ADD CONSTRAINT `memberp_ibfk_1` FOREIGN KEY (`mm_no`) REFERENCES `memberm` (`mm_no`) ON UPDATE CASCADE;";

  $sqlAds = "CREATE TABLE `abc_ads` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `ad_type` enum('gold','silver','bronze') NOT NULL,
  `transaction_id` varchar(30) DEFAULT NULL,
  `subscriber_id` varchar(30) DEFAULT NULL,
  `payment_status` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `exp_date` date DEFAULT NULL,
  `gross` float(9,3) DEFAULT NULL,
  `paypal_fee` float(9,3) DEFAULT NULL,
  `net` float(9,3) DEFAULT NULL,
  `current_year` int(4) DEFAULT NULL,
  `payc` float(9,3) DEFAULT NULL COMMENT 'amount falls in to current year',
  `next_year` int(4) DEFAULT NULL,
  `payn` float(9,3) DEFAULT NULL COMMENT 'amount left for next year',
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

  $wpdb->get_results($sqlMember);
  $wpdb->get_results($sqlPayment);
  $wpdb->get_results($sqlPayment_2);
  $wpdb->get_results($sqlAds);


}

register_activation_hook(__FILE__, 'createTables');


###### DROP TABLE

function dropTables()
{
  global $wpdb;
  $wpdb->get_results("DROP TABLE memberm");
  $wpdb->get_results("DROP TABLE memberp");
  $wpdb->get_results("DROP TABLE abc_ads");
  /* ideally we should delete these options but to prevent any accidents I commented them out
   *
   * delete_option('gold-ad-paypal-code');
  delete_option('silver-ad-paypal-code');
  delete_option('bronze-ad-paypal-code');
  delete_option('mailchimp-api');
  delete_option('mailchimp-campaign');
  delete_option('paypal-token');*/

}
register_uninstall_hook(__FILE__, 'dropTables');

##########


#########
####  MY PLUGIN'S ADMIN PAGE
#########
require("plugin-admin.php");

#########
####  END OF MY PLUGIN'S ADMIN PAGE
#########



########
####  MY SHORTCODES
########

require("shortcodes.php");

########
####  END OF MY SHORTCODES
########


function sallananNazarlik()
{
  $content ='<script type="text/javascript">
    setInterval(function(){
        var nazarlik = jQuery("#nazarlik");
        nazarlik.removeClass();
        nazarlik.addClass("swing").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
                      jQuery(this).removeClass();
                    });

    },15000);
    window.onload = function(){
        var nazarlik = jQuery("#nazarlik");
      nazarlik.addClass("swing").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
                    jQuery(this).removeClass();
                  });
    }

  </script>';
  echo $content;
}
add_action('wp_footer','sallananNazarlik');

// Register style sheet.
add_action( 'admin_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
  wp_register_style( 'abc-style', plugins_url( 'abc-panel/css/abc-style.css' ) );
  wp_enqueue_style( 'abc-style' );
}

?>