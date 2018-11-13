<?php
   /*
   Plugin Name: IPFS Bridge
   Plugin URI: https://www.jefflubbers.com/ipfs
   description: This plugin creates an IPFS version of your website.
   Version: 1.16
   Author: Jeffrey Lubbers
   Author URI: https://www.jefflubbers.com

   

    Copyright ©2018 Jeffrey J.W. Lubbers, LLC (Owners). All Rights Reserved. Permission to use, copy,
   modify, and distribute this software and its documentation for educational, research, and not-for-profit purposes,
   without fee and without a signed licensing agreement, is hereby granted,
   provided that the above copyright notice, this paragraph and the following two paragraphs appear in all copies,
   modifications, and distributions. Contact support@jefflubbers for commercial licensing opportunities.

    IN NO EVENT SHALL OWNERS BE LIABLE TO ANY PARTY FOR DIRECT, INDIRECT, SPECIAL, INCIDENTAL,
   OR CONSEQUENTIAL DAMAGES, INCLUDING LOST PROFITS, ARISING OUT OF THE USE OF THIS SOFTWARE AND ITS DOCUMENTATION,
   EVEN IF OWNERS HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

    OWNERS SPECIFICALLY DISCLAIMS ANY WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
   MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE SOFTWARE AND ACCOMPANYING DOCUMENTATION, IF ANY,
   PROVIDED HEREUNDER IS PROVIDED "AS IS". OWNERS HAS NO OBLIGATION TO PROVIDE MAINTENANCE, SUPPORT, UPDATES,
   ENHANCEMENTS, OR MODIFICATIONS.
   
   */


    $root = __DIR__;
//    echo $root;
//    echo "</br>";
//    echo "</br>";
    echo strpos($root, "/wp-content/", strpos($root, "/wp-content/"));
    $root = substr($root, 0, strpos($root, "/wp-content/"));
//    echo "</br>";
//    echo "</br>";
//    echo $root;
    if (substr($root, + 0) <> "/"){
		$root = $root."/";
	}
//    echo "</br>";
//    echo "</br>";
//    echo $root;
    require_once( $root . 'wp-load.php' );
    require(__DIR__. '/IPFS.php');
//   require(__DIR__."/ipfs-Verification.php");
	use Cloutier\PhpIpfsApi\IPFS;
	use Cloutier\PhpIpfsApi\IPFSFiles;
    //require(__DIR__."/wp-async-task.php");
    require (__DIR__."/options.php");
    require (__DIR__. "/widget.php");
//    require '../../../wp-load.php';
	// connect to ipfs daemon API server
    global $ipfs, $postHashSQL, $hashTable, $wpdb, $filesTable, $firstBuild, $server,$gatewayPort,$APIPort, $ipfs_db_version;
    $hashTable = $wpdb->prefix . "ipfs_Hashes";
    $filesTable = $wpdb->prefix . "ipfs_Files";

    $logTable = $wpdb->prefix.'ipfs_logs';
    $postHashSQL = "SELECT * from wp_posts LEFT JOIN wp_ipfs_Hashes ON wp_ipfs_Hashes.postID = wp_posts.ID;";
    $options = get_option( 'ipfs_settings' );
    $server = $options['ipfs_server'];
    $gatewayPort = $options['ipfs_gateway_port'];
    $APIPort = $options['ipfs_api_port'];
    $GateWayLink = $options{'ipfs_gateway'};
    $ipfs_db_version = get_option('ipfs_db_version');
	$ipfs = new IPFS($server, $gatewayPort, $APIPort ); // leaving out the arguments will default to these values

    add_action( 'saveAllPages', 'saveAllPages' );
    $filesAdded = get_option( "ipfs_files_added");

    $firstBuild = get_option("ipfs_first_build_complete");
    $pagesBuilt = get_option('ipfs_build_pages');
//    $loggingEnabled = get_option('ipfs_settings')['ipfs_logging'];





add_action( 'upgrader_process_complete', 'ipfs_update_plugin',10, 2);

function ipfs_update_plugin( $upgrader_object, $options )
{
//    ipfs_logEvent("Update Called");
    $current_plugin_path_name = plugin_basename(__FILE__);
    global $ipfs_db_version, $wpdb, $logTable, $filesTable;
    $settings = get_option('ipfs_settings');
    if ($options['action'] == 'update' && $options['type'] == 'plugin') {
//        ipfs_logEvent("First If");
        foreach ($options['plugins'] as $each_plugin) {
//            ipfs_logEvent("Checking Plugin $each_plugin");
            if ($each_plugin == $current_plugin_path_name) {
//                ipfs_logEvent("Current Plugin Match");
                // .......................... YOUR CODES .............
                if (version_compare($ipfs_db_version, '1.6', '<')) {
//                    ipfs_logEvent("Version Less than 1.6");

                    if (!wp_next_scheduled('ipnsPublishingRoutine')) {
                        wp_schedule_event(time(), 'twicedaily', 'ipnsPublishingRoutine');
                    }

                    $settings['publish_ipns'] = false;
                    $settings['ipns_name'] = 'self';
                    $settings{'ipns_key_id'} = '';
                    $settings['ipns_publish_expiration'] = time();
                    update_option("ipfs_settings", $settings);
                    update_option("ipfs_db_version", '1.6');
                }
                if (version_compare($ipfs_db_version, '1.8', '<')) {
                    $charset_collate = $wpdb->get_charset_collate();
                    if ($wpdb->get_var("SHOW TABLES LIKE '$logTable'") != $logTable) {
                        $sql = "CREATE TABLE " . $logTable . "(
                          id bigint(20) NOT NULL AUTO_INCREMENT,
                          logMessage varchar(max)  NOT NULL,
                          logTime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                          codeLocation varchar(500) DEFAULT '' NOT NULL,
                          PRIMARY KEY  (id)
                        ) $charset_collate;";
                        require(ABSPATH . 'wp-admin/includes/upgrade.php');
                        $exe = dbDelta($sql);
                        echo "Value: ".$exe;
                        foreach($exe as $row){
                            echo "Result: " . $row."</br>";
                        }
                    }
                    update_option("ipfs_db_version", '1.7');
                }

                if (version_compare($ipfs_db_version, '1.8', '<')) {
                    if($wpdb->get_var("SHOW TABLES LIKE '$filesTable'") != $filesTable){
                        $sql = "CREATE TABLE " .$filesTable . "(
                  id bigint(20) NOT NULL AUTO_INCREMENT,
                  filename varchar(100)  NOT NULL,
                  hash varchar(100) NOT NULL,
                  
                  dateAdded timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";
                        ipfs_logEvent($sql);
                        require( ABSPATH . 'wp-admin/includes/upgrade.php' );
                        dbDelta( $sql );
                    }
                    update_option("ipfs_db_version", '1.8');
                }
                if (version_compare($ipfs_db_version, '1.9', '<')) {
                   add_option( "ipfs_files_added", 0 );


                    $settings['ipfs_gateway'] = "https://gateway.ipfs.io";

                    update_option("ipfs_settings", $settings);
                    update_option("ipfs_db_version", '1.9');
                }

            }
        }
    }
}
add_action('wp_ajax_ipfs_UpdateDB', "ipfs_UpdateDB");
    function ipfs_UpdateDB(){
        global  $hashTable, $wpdb, $filesTable, $ipfs_db_version, $logTable, $charset_collate;
        echo "Current DB Version: $ipfs_db_version </br>";


        if($wpdb->get_var("SHOW TABLES LIKE '$hashTable'") != $hashTable) {
            echo "Hash Table Not Found Adding hash Table. </br>";
            $sql = "CREATE TABLE " . $hashTable . "(
              id bigint(20) NOT NULL AUTO_INCREMENT,
              postID bigint(20)  NOT NULL,
              hash varchar(100) NOT NULL,
              url varchar(500) DEFAULT '' NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";
            echo $sql."</br>";

            require(ABSPATH . 'wp-admin/includes/upgrade.php');
            $exe = dbDelta($sql);
            echo "Value: ".$exe;
            foreach($exe as $row){
                echo "Result: " . $row."</br>";
            }

        }

//                    ipfs_logEvent("Version Less than 1.6");

            if (!wp_next_scheduled('ipnsPublishingRoutine')) {
                wp_schedule_event(time(), 'twicedaily', 'ipnsPublishingRoutine');
                $settings['publish_ipns'] = false;
                $settings['ipns_name'] = 'self';
                $settings{'ipns_key_id'} = '';
                $settings['ipns_publish_expiration'] = time();
                update_option("ipfs_settings", $settings);
                update_option("ipfs_db_version", '1.6');
            }
            else{
                echo "Hash Table Found</br>";
            }




            $charset_collate = $wpdb->get_charset_collate();
            if ($wpdb->get_var("SHOW TABLES LIKE '$logTable'") != $logTable) {
                echo "Log Table Not Found Adding log Table. </br>";
                $sql = "CREATE TABLE " . $logTable . "(
                          id bigint(20) NOT NULL AUTO_INCREMENT,
                          logMessage varchar(max)  NOT NULL,
                          logTime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                          codeLocation varchar(500) DEFAULT '' NOT NULL,
                          PRIMARY KEY  (id)
                          
                        ) $charset_collate;";
                echo $sql."</br>";
                require(ABSPATH . 'wp-admin/includes/upgrade.php');
                $exe = dbDelta($sql);
                echo "Value: ".$exe;
                foreach($exe as $row){
                    echo "Result: " . $row."</br>";
                }


            }
            else{
                echo "Log Table Found </br>";
            }
            update_option("ipfs_db_version", '1.7');



        if($wpdb->get_var("SHOW TABLES LIKE '$filesTable'") != $filesTable){
            echo "Files Table Not Found Adding Files Table. </br>";
            $sql = "CREATE TABLE " .$filesTable . "(
              id bigint(20) NOT NULL AUTO_INCREMENT,
              filename varchar(100)  NOT NULL,
              hash varchar(100) NOT NULL,
              
              dateAdded timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";
            echo $sql."</br>";
            ipfs_logEvent($sql);
            require( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $exe = dbDelta($sql);
            echo "Value: ".$exe;
            foreach($exe as $row){
                echo "Result: " . $row."</br>";
            }
            }

        else{
            echo "Files Table Found</br>";
        }
        if (version_compare($ipfs_db_version, '1.9', '<')) {
            add_option( "ipfs_files_added", 0 );


            update_option("ipfs_db_version", '1.9');
            echo "Updating to 1.9 </br>";
        }
        else{
            echo "Db Already Version 1.9 </br>";
        }
        update_option("ipfs_db_version", '1.9');
        echo "Current DB Version: 1.9";
        wp_die();
    }
//update_option("ipfs_db_version", '1.5');
   function ipfs_activate() {
       ipfs_logEvent("Plugin Activated.");
       add_option( "ipfs_build_pages", 0 );
       add_option( "ipfs_files_added", 0 );
       add_option( "ipfs_first_build_complete", false );

       ipfs_install();
    // Activation code here...
	}
	register_activation_hook( __FILE__, 'ipfs_activate' );

//   ipfs_logEvent("GetType: ". gettype("test"));

    function ipfs_logEvent($message, $codeLocation='Not Defined'){
        global $logTable, $wpdb;
        $settings = get_option('ipfs_settings');
        if (array_key_exists('ipfs_logging',$settings)){
            $loggingEnabled  = $settings['ipfs_logging'];
        }
        else{
            $loggingEnabled = false;
        }
        if(!$loggingEnabled){
            return;
        }
        if(gettype($message) != 'string'){
            return;
        }
        $data = array(
            "logMessage"=>$message,
            "codeLocation"=> $codeLocation
        );
        $wpdb->insert($logTable, $data);
    }


require "plugin-update-checker/plugin-update-checker.php";
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'http://www.jefflubbers.com/ipfs-plugin.json',
    __FILE__, //Full path to the main plugin file or functions.php.
    'ipfs-bridge'
);
    function getIPFSHashByPostId($post_id){
        global $hashTable, $wpdb;

        $sql = "SELECT *  from ". $hashTable ." WHERE postId = ". $post_id.  " ORDER BY id desc LIMIT 1;";
//        ipfs_logEvent($sql);
        $row =  $wpdb->get_results( $sql);
//        ipfs_logEvent("results: " . count($row));
        if(count($row) != 0 ){
            ipfs_logEvent($row[0]->hash);
            return $row[0]->hash;
        }
        else{
            return null;
        }
    }
	function getIFPSHashbyURL($URL){
        global $hashTable, $wpdb;

        $sql = "SELECT *  from ". $hashTable ." WHERE url = '". $URL.  "' ORDER BY id desc LIMIT 1;";
//        ipfs_logEvent($sql);
        $row =  $wpdb->get_results( $sql);
//        ipfs_logEvent("results: " . count($row));
        if(count($row) != 0 ){
            ipfs_logEvent("Getting Hash: " . $row[0]->hash);
            return $row[0]->hash;
        }
        else{
            return null;
        }
    }
    function ipfs_install () {
        global $wpdb, $hashTable, $filesTable, $logTable;
        ipfs_logEvent("Creating IPFS tables.");
        ipfs_logEvent($filesTable);

        $charset_collate = $wpdb->get_charset_collate();
        if($wpdb->get_var("SHOW TABLES LIKE '$hashTable'") != $hashTable){
            $sql = "CREATE TABLE " .$hashTable . "(
              id bigint(20) NOT NULL AUTO_INCREMENT,
              postID bigint(20)  NOT NULL,
              hash varchar(100) NOT NULL,
              url varchar(500) DEFAULT '' NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );


            if ($wpdb->get_var("SHOW TABLES LIKE '$logTable'") != $logTable) {
                $sql = "CREATE TABLE " . $logTable . "(
                          id bigint(20) NOT NULL AUTO_INCREMENT,
                          logMessage varchar(max)  NOT NULL,
                          logTime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                          codeLocation varchar(500) DEFAULT '' NOT NULL,
                          PRIMARY KEY  (id)
                        ) $charset_collate;";
                require(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
            update_option("ipfs_db_version", '1.7');

                if($wpdb->get_var("SHOW TABLES LIKE '$filesTable'") != $filesTable){
                    $sql = "CREATE TABLE " .$filesTable . "(
                  id bigint(20) NOT NULL AUTO_INCREMENT,
                  filename varchar(100)  NOT NULL,
                  hash varchar(100) NOT NULL,
                  
                  dateAdded timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";
                    ipfs_logEvent($sql);
                    require( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta( $sql );
                }
                update_option("ipfs_db_version", '1.8');



        }



        ipfs_logEvent("Table Create Complete \r\nAdding Option");
        add_option( "ipfs_db_version", "1.8" );
//        add_option( "ipfs_server", "localhost" );
//        add_option( "ipfs_gateway_port", "8080" );
//        add_option( "ipfs_api_port", "5001" );
        $options = array(
            'ipfs_server'=>'localhost',
            'ipfs_gateway_port'=>'8080',
            'ipfs_api_port'=>5001,
            'ipfs_display_link'=>'',
            'ipfs_logging'=>false

        );

        $options['publish_ipns'] = false;
        $options['ipns_name'] = 'self';
        $options{'ipns_key_id'} = '';
        $options['ipns_publish_expiration'] = time();

        add_option("ipfs_settings", $options);

        ipfs_logEvent("OptionAdded");
    }
//    register_activation_hook( __FILE__, 'ipfs_install' );

function randomstring($len)
{
    $string = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for($i=0;$i<$len;$i++)
        $string.=substr($chars,rand(0,strlen($chars)),1);
    return $string;
}
	function saveAllPages(){
        global $wpdb;

        $sql = "SELECT ID from $wpdb->posts ORDER BY ID DESC" ;
//        ipfs_logEvent($sql);
        $rows =  $wpdb->get_results( $sql);
        ipfs_logEvent("results: " . count($rows));
        if(count($rows) != 0 ){
            foreach($rows as $post){
                on_Save($post->ID);
            }

            foreach($rows as $post){
                on_Save($post->ID);
            }

            foreach($rows as $post){
                on_Save($post->ID);
            }

            foreach($rows as $post){
                on_Save($post->ID);
            }

            foreach($rows as $post){
                on_Save($post->ID);
            }

            foreach($rows as $post){
                on_Save($post->ID);
            }

        }
        else{
            return null;
        }

    }




    function savePostToDisk($post_id){
        global $ipfs, $firstBuild, $server,$gatewayPort,$APIPort, $GateWayLink;

        // If this is just a revision-- dont build -- revisions are not yet published?
        if ( wp_is_post_revision( $post_id ) ) {
            ipfs_logEvent("Revision");
            return;
        }

        $BuildCount = get_option("ipfs_build_pages");

        $post_url = $permLink = get_permalink( $post_id );
        $cachePath = __DIR__."/cache/$post_id/";
        system("rm -rf ".escapeshellarg($cachePath));
        ipfs_logEvent("Cache Path: $cachePath");
        if(!file_exists($cachePath)){
            ipfs_logEvent("CachePath Exsists");
            mkdir($cachePath."files/", 0775,true);
        }

        ipfs_logEvent("POST STATUS: " . get_post_status( $post_id ). " Post Type: ".get_post_type($post_id));
        $post_status = get_post_status($post_id);
        $post_type = get_post_type($post_id);
        if ($post_status == 'auto-draft' || $post_type == 'nav_menu_item' ){
            return;
        }
        ipfs_logEvent("Saving Page to Disk: " . $post_url. " Post ID: " . $post_id." Perma link: " . $permLink);
        $pageContent = file_get_contents($post_url);
        //ipfs_logEvent($pageContent);
        $siteURL = get_site_url();
//        ipfs_logEvent("Site URL: " . $siteURL);
        $urls = array();

        $currentPos = 0;
        $continue = true;
        $startChars = array("'http", '"http', " http");
        $endChars = array(" ", "'", '"');

        $startPos = strlen($pageContent);
        foreach($startChars as $char) {
            $x = strpos($pageContent, $char, $currentPos);
//            ipfs_logEvent("START POS FOUND: $x");
            if ($x < $startPos) {
                $startPos = $x;
            }
        }

        while ($continue){

            $endPos = strlen($pageContent);
            foreach ($endChars as $char){
                $x = strpos($pageContent, $char, $startPos+2);
//                ipfs_logEvent("END POS FOUND: $x");
                if($x < $endPos){
                    $endPos = $x;
                }
            }
//            ipfs_logEvent("End Pos is: $endPos");
            if($endPos > $startPos){
                $foundURL = substr($pageContent,$startPos, $endPos-$startPos+1);
                if(!in_array($foundURL,$urls)){
                    array_push($urls,$foundURL);
                }

                ipfs_logEvent("FOUND URL: $foundURL");


                $currentPos = $endPos - 1;
                $startPos = strlen($pageContent);
                foreach($startChars as $char) {
                    $x = strpos($pageContent, $char, $currentPos);
//                    ipfs_logEvent("START POS FOUND: $x");
                    if(!($x===false)){
                        if ($x < $startPos) {
                            $startPos = $x;
                        }
                    }


                }
//                ipfs_logEvent("Start Pos is: $startPos");
            }
            else{
                $continue = false;
            }


        }
        ipfs_logEvent(count($urls) . " Urls Found");


        $includedURLS = array();
        foreach ($urls as &$url){

            $afterSite = substr($url,strlen($siteURL)+2);
            $urlInclude = array("js","php", "jpg","php", "jpeg", "xml","css");
            $remove = true;

            foreach($urlInclude as $include){
                if(stristr($url,$include)){
                    $remove = false;
                }
            }

            if(!stristr($url,$siteURL) && $remove  ){ // REMOVE VALUES FROM ARRAY

                if (($key = array_search($url, $urls)) !== false) {
                    ipfs_logEvent("Removing Url: " . $url);
                    unset($urls[$key]);
                }
            }

        }

        ipfs_logEvent(count($urls) . " Urls Found");
        $hashes = array();


        $includeCheck = array("js","php", "jpg", "png", "jpeg", "xml","css");
        $files = array();
        foreach($urls as $url){

            $first = substr($url,0,1);
            $last = substr($url,-1);
            $subURL = substr($url, 1, strlen($url)-2);
            $subContent =  file_get_contents(str_replace("\/","/",$subURL));
            $key  = array_search($url, $urls);

            $extension = "";
            foreach ( $includeCheck as $check){
                if (stristr($url,".".$check)){
                    $extension = ".$check";
                }
            }
            file_put_contents($cachePath.$key.$extension,$subContent);
            array_push($files,$cachePath.$key.$extension);
        }

        foreach($urls as $url){


            $first = substr($url,0,1);
            $last = substr($url,-1);
            $subURL = substr($url, 1, strlen($url)-2);
            $subContent =  file_get_contents($subURL);
            $subHash = getIFPSHashbyURL($subURL);

            $extension = "";
            foreach ( $includeCheck as $check){
                if (stristr($url,".".$check)){
                    $extension = ".$check";
                }
            }
            if($subURL == $permLink){
                $replaceMentURL = $first. "javascript:window.location.reload(true)". $last;

            }elseif($subHash != null){
                $replaceMentURL = $first. "$GateWayLink/ipfs/" . $subHash . $last;
            }
            elseif(stristr($url,"/ipfs/Qm")){
                $replaceMentURL = $subURL;
            }
            else{
                $key = array_search($url, $urls);
                $replaceMentURL = $first. "./" . $key.$extension . $last ;
//                $pageContent = str_replace($url, $first. "/files/" . $key . $last , $pageContent );
            }
            $pageContent = str_replace($url, $replaceMentURL , $pageContent );
            ipfs_logEvent("Replacing URL: $url , $replaceMentURL");
            }

            file_put_contents($cachePath."index.html", $pageContent);
            array_unshift($files,$cachePath."index.html");

        ipfs_logEvent("count of FIles: ".count($files));
        if (!count($files) == 0){
            ipfs_logEvent(json_encode($files));
            $ipfs = new IPFSFiles($server,$gatewayPort,$APIPort);

            $result = $ipfs->addWithWrap(array_splice($files,0,-1));

            $hash = $result['FolderHash'];

            ipfs_logEvent("Hash: " . $hash );
            updatePostHashTable($post_id, $permLink, $hash);
            $settings = get_option('ipfs_settings');
            if (array_key_exists('publish_ipns',$settings) && $siteURL == $permLink){
                $key = $settings['ipns_name'];
                $resposne = ipfsGet("name/publish?arg=$hash&resolve=true&lifetime=24h&key=$key");
                ipfs_logEvent(implode(" ", $resposne));
            }


            ipfs_logEvent("Finished Save");

            if (!$firstBuild){
                update_option('ipfs_build_pages',$BuildCount+1);
            }
        }

        system("rm -rf ".escapeshellarg($cachePath));
        }





function ipfs_sechedule_Save($postId){
    wp_schedule_single_event( time()-60 , 'ipfs_saveSinglePost', array($postId) );

}
add_action('ipfs_saveSinglePost','savePostToDisk');

add_action( 'save_post', 'ipfs_sechedule_Save' );




function updatePostHashTable($postid, $postPermLink, $hash){
    global $hashTable, $wpdb;
    ipfs_logEvent("updating Hash table.");
//	    $insertSQL = "INSERT INTO " . $hashTable . " (postID, url, hash) VALUES(" .
//                        $postid . " , " . $postPermLink . " , " . $hash .")";
    $data = array(
        "postid" => $postid,
        "url" => $postPermLink,
        "hash" => $hash
    );

    $wpdb->insert($hashTable, $data);

}


function setPublishingKey(){
    $options = get_option( 'ipfs_settings' );
    ipfs_logEvent("Setting Publishing Name");
    $keyName = $_POST['keyName'];
    $keyId = $_POST['keyId'];
    ipfs_logEvent("Name:ID, $keyName:$keyId");
    $settings = $options;
    $settings['ipns_name'] = $keyName;
    $settings['ipns_key_id']= $keyId;


    update_option("ipfs_settings", $settings);
    echo "Publish Site to: $keyName set";

}
add_action( 'wp_ajax_setPublishingKey', 'setPublishingKey' );
function CreateNewKey(){
    $keyName = $_POST['keyName'];
    $response = ipfsget("key/gen?arg=$keyName&type=ed25519");
//    echo implode(" ",$response);
    echo "Key $keyName Created With Id: ".$response['Id'];
}

add_action( 'wp_ajax_CreateNewKey', 'CreateNewKey' );


add_action('ipnsPublishingRoutine', 'ipnsPublishingRoutine');

function ipnsPublishingRoutine() {
    global $options;
    ipfs_logEvent("Checking if should Publish to IPNS");
//    ipfs_logEvent(serialize($options));
    if (array_key_exists('publish_ipns',$options)){
        ipfs_logEvent("Should Publish");
        $siteURL = get_site_url() . "/";
        ipfs_logEvent($siteURL);
        $hash = getIFPSHashbyURL($siteURL);
        ipfs_logEvent("Hash: $hash");
        if($hash !== null ){
            ipfs_LogEvent("Hash not Null");
            $key = $options['ipns_name'];
            $resposne = ipfsGet("name/publish?arg=$hash&resolve=true&lifetime=24h&key=$key");
            ipfs_logEvent(implode(" ", $resposne));
            $x = $options;
            ipfs_logEvent("Current Time: ".date('Y-m-d h:i:s A', strtotime(date("D M d, Y G:i"))));
            $time = date('Y-m-d h:i:s A', strtotime(date("D M d, Y G:i"))+86000);
            ipfs_logEvent("Time: ". $time);
            $x['ipns_publish_expiration'] = $time;
            update_option("ipfs_settings", $x);
        }

    }
}
add_action('wp_ajax_runIPFSCommand', "runIPFSCommand");
add_action('wp_ajax_exportLogsasCSV', "exportLogsasCSV");
function runIPFSCommand() {
    global $options;
    $command = $_POST['ipfsCommand'];
    ipfs_logEvent("Running Command: $command");
    $response = ipfsGet($command);
    ipfs_logEvent(json_encode($response));

    echo json_encode($response);
    wp_die();

//    echo $response;
}
//ipnsPublishingRoutine();


function exportLogsasCSV(){

    global $wpdb;

// Grab any post values you sent with your submit function


// Build your query
    $sql = "";
    $results = $wpdb->get_results("Select logTime, logMessage, codeLocation from wp_tech_ipfs_logs ORDER BY logTime DESC;");


// Process report request
    if (! $results) {
        $Error = $wpdb->print_error();
        die("The following error was found: $Error");
    } else {
// Prepare our csv download

// Set header row values
        $csv_fields=array();
        $csv_fields[] = 'Field Name 1';
        $csv_fields[] = 'Field Name 2';
        $output_filename = 'IPFS_BRIGDE_LOGS_'.date(time()).'.csv';
        $output_handle = @fopen( 'php://output', 'w' );

        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: text/csv' );
        header( 'Content-Disposition: attachment; filename=' . $output_filename );
        header( 'Expires: 0' );
        header( 'Pragma: public' );

// Insert header row
       // fputcsv( $output_handle, $csv_fields );

        $first = true;
        // Parse results to csv format
        foreach ($results as $row) {

            // Add table headers
            if($first){
                $titles = array();
                foreach($row as $key=>$val){
                    $titles[] = $key;
                }
                fputcsv($output_handle, $titles);
                $first = false;
            }

            $leadArray = (array) $row; // Cast the Object to an array
            // Add row to file
            fputcsv( $output_handle, $leadArray );
        }

// Close output file stream
        fclose( $output_handle );

        wp_die();
    }
}



add_action('wp_ajax_IPFSUploadFIles', "IPFSUploadFIles");
function IPFSUploadFIles() {
    global $options, $server,$gatewayPort,$APIPort, $filesAdded;
    $path = $_POST['filePath'];
    $id = $_POST['id'];
    ipfs_logEvent("Attempting to Upload File: ".$id);

    $ipfs = new IPFSFiles($server,$gatewayPort,$APIPort);
    $hash = $ipfs->addFile($path);
    echo $hash;
    $newFiles = $filesAdded +1;
    update_option('ipfs_files_added',$newFiles);

    wp_die();

}


function get_file_url( $file = __FILE__ ) {
    $file_path = str_replace( "\\", "/", str_replace( str_replace( "/", "\\", WP_CONTENT_DIR ), "", $file ) );
    if ( $file_path )
        return content_url( $file_path );
    return false;
}
add_action('wp_ajax_ipfs_curlTest', "ipfs_curlTest");
function ipfs_curlTest(){
    global $options, $server,$gatewayPort,$APIPort, $filesAdded;

    $data = array( 'arg' => 'QmaaqrHyAQm7gALkRW8DcfGX3u8q9rWKnxEMmf7m9z515w', 'encoding' => 'json' );
    $URL = "http://".$server.":$APIPort/api/v0/object/get";
//    echo count($data);

    if (count($data) > 0){
        $lbFirst = true;
        foreach($data as $key => $value){
            if($lbFirst){
                $URL = $URL."?";
            }
            $URL = $URL.$key."=".$value."&";
        }
    }
//    echo $URL;

    $response = wp_remote_post($URL, array( 'data' => $data ) );
    $response = json_encode($response);
    echo $response;
    wp_die();

}
