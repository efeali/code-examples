<?php
/**
 * Plugin Name: User tracker
 * Description: This plugin keep track of users, stores info such as user ip, date/time when user logged in, and if user is online
 * Author Name: Ali
 * Version: 0.1
 */


function createTable()
{
    global $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS `usertracker` (`userID` bigint(20) NOT NULL, `ip` varchar(15) NOT NULL, `date` datetime NOT NULL, `online` boolean NOT NULL DEFAULT 0)";

    $answer = $wpdb->get_results($sql); // Generic, multiple row results can be pulled from the database with get_results. The function returns the entire query result as an array.

    if(!empty($answer))
    {
        echo "<script>alert('Table created');</script>";
    }
    else
    {
        echo "<script>alert('Something went wrong');</script>";
    }
}

register_activation_hook(__FILE__,'createTable'); // when we install this plugin to wordpress it will call createTable function


function dropTable()
{
    global $wpdb;

    $sql ="DROP TABLE `usertracker`";

    $answer = $wpdb->get_results($sql);
    if(!empty($answer))
    {
        echo "Table deleted";
    }
}

register_uninstall_hook(__FILE__, 'dropTable'); // when we uninstall this plugin wordpress will call dropTable function

function addTrack($user_login, $user)
{
    global $wpdb;
    $id = $user->ID;
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO usertracker (`userID`, `ip`,`date`,`online`) VALUES ( ".$id." , '".$ip."', now(), 1)";

    $wpdb->get_results($sql);
}

add_action('wp_login','addTrack',10,2); // if any user logs in wordpress will call addTrack function


######

function removeTrack()
{
    global $wpdb;
    $user = wp_get_current_user();
    $id = $user->ID;

    $sql = "DELETE FROM usertracker WHERE userID = ".$id;
    $wpdb->get_results($sql);

}

add_action('wp_logout','removeTrack');


function showOnlineUsers()
{
    global $wpdb;

    $sql = "SELECT * FROM usertracker WHERE online = 1"; // search for online users in our table
    $results = $wpdb->get_results($sql); // returned is array with rows as objects

    ?>
    <div>

    <h3>Online users</h3>
        <p>
        <?php
        foreach($results as $user) // looping through array get one by one each row object (contains user)
        {
           $userObj = get_user_by('id',$user->userID); // from table we only got userID, to show user's name we need to use wordpress function get_user_by, this function returns user object
            echo $userObj->data->display_name."<br/>";
        }
        ?>
        </p>
    </div>
    <?php
}
