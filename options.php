<?php
use Cloutier\PhpIpfsApi\IPFSFiles;
add_action( 'admin_menu', 'ipfs_add_admin_menu' );
add_action( 'admin_init', 'ipfs_settings_init' );
//require ("IPFS.php");
global $server,$gatewayPort,$APIPort,$GateWayLink;
function ipfs_add_admin_menu(  ) {

    add_menu_page( 'IPFS Bridge', 'IPFS Bridge', 'manage_options', 'ipfs_generator', 'ipfs_options_page' );
//    add_options_page( 'IPFS Generator', 'IPFS Generator', 'manage_options', 'ipfs_generator', 'ipfs_options_page' );
//
}


function ipfs_settings_init(  ) {

    register_setting( 'pluginPage', 'ipfs_settings' );

    add_settings_section(
        'ipfs_pluginPage_section',
        __( 'Adjust your IPFS Host settings here.', 'wordpress' ),
        'ipfs_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'ipfs_server',
        __( 'Hostname / IP', 'wordpress' ),
        'ipfs_text_field_0_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );

    add_settings_field(
        'ipfs_gateway_port',
        __( 'Gateway Port', 'wordpress' ),
        'ipfs_text_field_1_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );

    add_settings_field(
        'ipfs_api_port',
        __( 'API Port', 'wordpress' ),
        'ipfs_text_field_2_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipfs_display_link',
        __( 'Display Link', 'wordpress' ),
        'ipfs_text_field_3_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );

    add_settings_field(
        'ipfs_checkbox_field_0',
        __( 'Enable Logging', 'wordpress' ),
        'ipfs_checkbox_field_0_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipfs_checkbox_field_1_render',
        __( 'Publish to IPNS', 'wordpress' ),
        'ipfs_checkbox_field_1_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipfs_gateway',
        __( 'IPFS Gateway Root:</br>ex: https://gateway.ipfs.io', 'wordpress' ),
        'ipfs_text_field_gateway_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipns_publish_expiration',
        __( 'IPNS Expiration (GMT): ', 'wordpress' ),
        'ipfs_text_field_ipns_publish_expiration_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipns_key_id',
        __( 'IPNS Key Id: ', 'wordpress' ),
        'ipfs_text_field_ipns_key_id_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
    add_settings_field(
        'ipns_name',
        __( 'IPNS Key Name: ', 'wordpress' ),
        'ipfs_text_field_ipns_name_render',
        'pluginPage',
        'ipfs_pluginPage_section'
    );
}
//$settings['ipns_name'] = 'self';
//$settings{'ipns_key_id'} = '';
//$settings['ipns_publish_expiration'] = time();

function ipfs_text_field_0_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ipfs_logEvent($options);
    ?>
    <input type='text' name='ipfs_settings[ipfs_server]' value='<?php echo $options['ipfs_server']; ?>'>
    <?php

}


function ipfs_text_field_1_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' name='ipfs_settings[ipfs_gateway_port]' value='<?php echo $options['ipfs_gateway_port']; ?>'>
    <?php

}


function ipfs_text_field_2_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' name='ipfs_settings[ipfs_api_port]' value='<?php echo $options['ipfs_api_port']; ?>'>
    <?php

}

function ipfs_text_field_gateway_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' name='ipfs_settings[ipfs_gateway]' value='<?php echo $options['ipfs_gateway']; ?>'>
    <?php

}

function ipfs_text_field_ipns_name_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' readonly name='ipfs_settings[ipns_name]' value='<?php echo $options['ipns_name']; ?>'>
    <?php

}

function ipfs_text_field_ipns_key_id_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' readonly name='ipfs_settings[ipns_key_id]' value='<?php echo $options['ipns_key_id']; ?>'>
    <?php

}

function ipfs_text_field_ipns_publish_expiration_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='text' readonly name='ipfs_settings[ipns_publish_expiration]' value='<?php echo $options['ipns_publish_expiration']; ?>'>
    <?php

}



///$settings['ipns_name'] = 'self';
//$settings{'ipns_key_id'} = '';
//$settings['ipns_publish_expiration'] = time();

function ipfs_text_field_3_render(  ) {

    $options = get_option( 'ipfs_settings' );

    ?>
    <input type='text' name='ipfs_settings[ipfs_display_link]' value='<?php echo $options['ipfs_display_link']; ?>'>
    <?php

}
function ipfs_checkbox_field_0_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='checkbox' name='ipfs_settings[ipfs_logging]' <?php checked( $options['ipfs_logging'], 1 ); ?> value='1'>
    <?php

}
function ipfs_checkbox_field_1_render(  ) {

    $options = get_option( 'ipfs_settings' );
    ?>
    <input type='checkbox' name='ipfs_settings[publish_ipns]' <?php checked( $options['publish_ipns'], 1 ); ?> value='1'>
    <?php

}

function ipfs_settings_section_callback(  ) {

    echo __( 'Host settings', 'wordpress' );}
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
//    ipfs_logEvent("Power: ". $pow);
    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));
    $return = $bytes/(1000**$pow);
    return round($return, $precision) . ' ' . $units[$pow];
}
function getRepoStats(){
    global $server,$gatewayPort,$APIPort;
    $apiUrl = "http://$server:$APIPort/api/v0/repo/stat?human=true";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 240);
    $output = curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $code_category = substr($response_code, 0, 1);
    if ($code_category == '5' OR $code_category == '4') {
        $data = @json_decode($output, true);
        if (!$data AND json_last_error() != JSON_ERROR_NONE) {
//            throw new Exception("IPFS returned response code $response_code: ".substr($output, 0, 200), $response_code);
        }
        if (is_array($data)) {
            if (isset($data['Code']) AND isset($data['Message'])) {
//                throw new Exception("IPFS Error {$data['Code']}: {$data['Message']}", $response_code);
            }
        }
    }
//    ipfs_logEvent("Output: " . serialize($output));
    $json = json_decode($output,true);
//    ipfs_logEvent($json['RepoSize']);
//    ipfs_logEvent("REPO SIZE: ".formatBytes($json['RepoSize']));
    // handle empty response
    if ($output === false) {
        //throw new Exception("IPFS Error: No Response", 1);
    }
    curl_close($ch);
    return $json;
}

function getVersion(){
    global $server,$gatewayPort,$APIPort;
    $apiUrl = "http://$server:$APIPort/api/v0/version";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 240);
    $output = curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $code_category = substr($response_code, 0, 1);
    if ($code_category == '5' OR $code_category == '4') {
        $data = @json_decode($output, true);
        if (!$data AND json_last_error() != JSON_ERROR_NONE) {
            //throw new Exception("IPFS returned response code $response_code: ".substr($output, 0, 200), $response_code);
        }
        if (is_array($data)) {
            if (isset($data['Code']) AND isset($data['Message'])) {
                //throw new Exception("IPFS Error {$data['Code']}: {$data['Message']}", $response_code);
            }
        }
    }
//    ipfs_logEvent("Output: " . serialize($output));
    $json = json_decode($output,true);

    // handle empty response
    if ($output === false) {
        //throw new Exception("IPFS Error: No Response", 1);
    }
    curl_close($ch);
    return $json;
}
function ipfsGet($command){
    global $server,$gatewayPort,$APIPort;
    $apiUrl = "http://$server:$APIPort/api/v0/$command";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 240);
    $output = curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $code_category = substr($response_code, 0, 1);
    if ($code_category == '5' OR $code_category == '4') {
        $data = @json_decode($output, true);
        if (!$data AND json_last_error() != JSON_ERROR_NONE) {
           // throw new Exception("IPFS returned response code $response_code: ".substr($output, 0, 200), $response_code);
        }
        if (is_array($data)) {
            if (isset($data['Code']) AND isset($data['Message'])) {
               // throw new Exception("IPFS Error {$data['Code']}: {$data['Message']}", $response_code);
            }
        }
    }
    require_once ("function.php");
//    ipfs_logEvent("Output: " . serialize($output));
    $json = json_decode($output,true);

    // handle empty response
    if ($output === false) {
      //  throw new Exception("IPFS Error: No Response", 1);
    }
    curl_close($ch);
    return $json;
}



function ipfs_options_page(  ) {
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$siteName = $protocol . $_SERVER['HTTP_HOST']
    ?>
    <form action='options.php' method='post'>

        <h2>IPFS Bridge Settings</h2>

        <?php
        global $filesAdded, $ipfs_db_version;
        $trial = get_option('ipfs_trial');
        $pagesBuilt = get_option('ipfs_build_pages');
//        echo "Trial: ".(!$trial)?"true":"false";
        if($trial && $pagesBuilt > 1000){
            echo "<div id='toManyPages'>You are no longer building new pages purchase a license to reactivate IPFS page building.</div>";
        }
        if($trial && $filesAdded > 1000){
            echo "<div id='toManyPages'>You can no longer add files to your IPFS node purchase a license to add more files.</div>";
        }
        if( isset( $_GET[ 'panel' ] ) ) {
            $active_tab = $_GET[ 'panel' ];
//            ipfs_logEvent("Active Tab = $active_tab");
        } // end if
        else{
            $active_tab = "main_settings";
        }
        if($trial){
            echo "<div id='trialBanner'>The Trial Version restricts to 1000 Pages and 100 files.</br>Current Pages Built: $pagesBuilt</br>Files Added: $filesAdded</div>";

            ?>
            <table id="license">
                <tr><th scope="row">Key: </th><td><input type="text" id="licenseKey" value=""></td></tr>
                <tr><th scope="row">Email: </th><td><input type="text" id="email" value=""></td></tr>
                <tr><td colspan="2"><button class="button button-primary" id="activateIPFS" onClick=" Activate(); return false;">Activate IPFS Plugin</button></td></tr>

            </table>

        <?php


        }
        else{
            echo "<div id='activeBanner'>Active</br>Current Pages Built: $pagesBuilt</br>Files Added: $filesAdded";

            echo '</div>';

        }

        echo '<div id="activeBanner">Database Version: '.$ipfs_db_version.'</div>';


        echo "<h2 class=\"nav-tab-wrapper\">
    <a href=\"?page=ipfs_generator&panel=main_settings\" class=\"nav-tab\">Main Settings</a>
    <a href=\"?page=ipfs_generator&panel=Peers\" class=\"nav-tab\">Peers</a>
    <a href=\"?page=ipfs_generator&panel=ipfs_FileManager\" class=\"nav-tab\">IPFS Files</a>
    <a href=\"?page=ipfs_generator&panel=KeyManager\" class=\"nav-tab\">Key Manager</a>
    <a href=\"?page=ipfs_generator&panel=ipfs_config\" class=\"nav-tab\">IPFS Config File</a>
    <a href=\"?page=ipfs_generator&panel=ipfs_logs\" class=\"nav-tab\">View Logs</a>
    <a href=\"?page=ipfs_generator&panel=ipfs_Database\" class=\"nav-tab\">Tools</a>";
        if(get_site_url() == 'https://www.jefflubbers.com'){
            echo '<a href="?page=ipfs_generator&panel=ipfs_DevArea" class="nav-tab">Dev</a>';
        }

        echo "</h2>";

        if($active_tab == 'main_settings'){
            ipfs_main_settings();
        }
        elseif($active_tab == 'ipfs_config'){
            ipfs_config();
        }
        elseif($active_tab== 'Peers'){
            ipfs_peers();
        }elseif($active_tab== 'KeyManager'){
            ipfs_KeyManager();
        }
        elseif($active_tab == 'ipfs_logs'){
            ipfs_logManager();
        }
        elseif($active_tab == 'ipfs_FileManager'){
            ipfs_FileManager();
        }
        elseif($active_tab == 'ipfs_Database'){
            ipfs_Database();
        }
        elseif($active_tab == 'ipfs_DevArea'){
            ipfs_DevArea();
        }




        ?>
    <style>
        #ipfs_UpdateDB{
            /*float:right;*/
        }
        .warning{
            color:red;
        }
        #mainSettings{
            width: 20%;
            float:left;
        }
        #ipfsStats{
            margin-left:50px;
            width: 20%;
            float:left;
        }
        #col3{
            width:40%;
            margin-left:5%;
            float:left;
        }
        #configEditor{margin-left:50px;
            width: 20%;
            float:left;
        }
        #toManyPages{
            background: #ff0002;
            color: #ffffff;
            font-size: 16pt;
            padding: 10px;
            line-height:21pt;
            margin-right: 20px;
        }
        #trialBanner {
            background: #0500ff;
            padding: 10px;
            color: #ffffff;
            font-size: 16pt;
            line-height:21pt;
            margin-right: 20px;
        }

        #activeBanner{
            background: #1e881a;
            padding: 10px;
            color: #ffffff;
            font-size: 16pt;
            line-height:21pt;
            margin-right: 20px;
        }
        .logMessage{
            width:85%;
        }
        .logDate{
            width:130px;
        }
    </style>
    <script>
        var setPublishingKey = function(KeyName,keyId){
            var data = {
                "action":"setPublishingKey",
                "keyName": KeyName,
                "keyId":keyId
            };
            jQuery.post(ajaxurl,data,function(response){
                response = response.substring(0, response.length - 1);
               alert(response);
               return false;
            });
            return false;
        }
        var createNewKey = function(){
            var newName= document.getElementById('newKeyName').value;
            newName = newName.replace(" ", "_")
            var data = {
                'action':"CreateNewKey",
                'keyName':newName
            }
            jQuery.post(ajaxurl,data,function(response){
                response = response.substring(0, response.length - 1);
                alert(response);
                return false;
            });
            return false;
        }

        var Activate = function () {

            var data = {
                'action': 'ActivateIPFS',
                id: document.getElementById('licenseKey').value,
                email: document.getElementById('email').value

            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                var str = response;
                str = str.substring(0, str.length - 1);
                    alert(str);
                    if(str.includes("License Key Valid")){
                        location.reload();
                    }
                    return false;

                }

            )
            return false;
        };
    </script>
    <script type="text/javascript" >
        var my_action_javascript = function($) {

            var data = {
                'action': 'my_action',

            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                //alert('Got this from the server: ' + response);
            });
        };
        var updateConfigFile = function($) {
            var configData = document.getElementById("configData").value;
            // configData = JSON.parse(configData);
            var data = {
                'action': 'updateIPFSConfigFile',
                'configData': configData

            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                alert(response);
            });

        };

        var runIPFSCommand = function($) {
            var configData = document.getElementById("ipfsCommand").value;
            // configData = JSON.parse(configData);
            var data = {
                'action': 'runIPFSCommand',
                'ipfsCommand': configData

            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                // alert(response);
                //console.log(response)
                var json = JSON.parse(response);
                var x = JSON.stringify(json, " ", 2)
                document.getElementById('ipfsCommandResponse').innerHTML = x;
            });

        };

        function VerifyDb() {

            var data = {
                'action': 'ipfs_UpdateDB'


            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                // alert(response);
                //console.log(response)

                document.getElementById('responseArea').innerHTML = response;
                return false;
            });
            return false;
        };
        var exportLogsasCSV = function(){
            //exportLogsasCSV
            var data = {
                'action': 'exportLogsasCSV',
                            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                var entries = csvToArray(response);
                console.log(entries);
                var today = new Date();
                var lcFileName = "IPFS_BRIDGE_LOGS_" + today.toLocaleString().replace(", ","_").replace(" ","_");
                exportToCsv(lcFileName+".csv",entries);

                // link.click(); // This will download the data file named "my_data.csv".
                return false;
                // alert(response);
                // response = response.substring(0, response.length - 1);
                // var json = JSON.parse(response);
                // var x = JSON.stringify(json, " ", 2)
                // document.getElementById('ipfsCommandResponse').innerHTML = x;
            });
        }


        //callCURL


        function csvToArray(csvString){

            // The array we're going to build
            var csvArray   = [];
            // Break it into rows to start
            var csvRows    = csvString.split(/\n/);
            // Take off the first line to get the headers, then split that into an array
            var csvHeaders = csvRows.shift().split(',');
            csvArray.push(csvHeaders);
            // Loop through remaining rows
            for(var rowIndex = 0; rowIndex < csvRows.length; ++rowIndex){
                var csvParseString = csvRows[rowIndex];

                csvParseString = csvParseString.substr(0,csvParseString.length-1);
                csvParseString = csvParseString.substr(1);
                // console.log(csvParseString);
                var rowArray  = csvParseString.split('","');
                // console.log(rowArray);
                // var rowArray  = csvRows[rowIndex].split('";"');
                csvArray.push(rowArray);
                // // Create a new row object to store our data.
                // var rowObject = csvArray[rowIndex] = {};
                //
                // // Then iterate through the remaining properties and use the headers as keys
                // for(var propIndex = 0; propIndex < rowArray.length; ++propIndex){
                //     // Grab the value from the row array we're looping through...
                //     var propValue =   rowArray[propIndex].replace(/^"|"$/g,'');
                //     // ...also grab the relevant header (the RegExp in both of these removes quotes)
                //     var propLabel = csvHeaders[propIndex].replace(/^"|"$/g,'');;
                //
                //     rowObject[propLabel] = propValue;
                // }
            }

            return csvArray;
        }
        function exportToCsv(filename, rows) {
            var processRow = function (row) {
                var finalVal = '';
                for (var j = 0; j < row.length; j++) {
                    var innerValue = row[j] === null ? '' : row[j].toString();
                    if (row[j] instanceof Date) {
                        innerValue = row[j].toLocaleString();
                    };
                    var result = innerValue.replace(/"/g, '""');
                    if (result.search(/("|,|\n)/g) >= 0)
                        result = '"' + result + '"';
                    if (j > 0)
                        finalVal += ',';
                    finalVal += result;
                }
                return finalVal + '\n';
            };

            var csvFile = '';
            for (var i = 0; i < rows.length; i++) {
                csvFile += processRow(rows[i]);
            }

            var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
            if (navigator.msSaveBlob) { // IE 10+
                navigator.msSaveBlob(blob, filename);
            } else {
                var link = document.createElement("a");
                if (link.download !== undefined) { // feature detection
                    // Browsers that support HTML5 download attribute
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        }
    </script>
    <?php
    function my_action_javascript() { ?>
        <script type="text/javascript" >
            var my_action_javascript = function($) {

                var data = {
                    'action': 'my_action',
                    'whatever': 1234
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

                jQuery.post(ajaxurl, data, function(response) {
                    alert('Got this from the server: ' + response)

                });
            });

            $('#ipfs_UpdateDB').submit(function(event) {
                event.preventDefault();
                // ...
            }
        </script> <?php
    }

}

add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_updateIPFSConfigFile', 'updateIPFSConfigFile' );
function updateIPFSConfigFile(){
    global $server,$gatewayPort,$APIPort;
    ipfs_logEvent("Updating Config File.");
    $tempfile = __DIR__."/ipfsCONFIG";
    $ipfsConfigData = $_POST['configData'];
//    ipfs_logEvent("DATE +== ".$_POST['configData']);
    $ipfsConfigData = str_replace('\"', '"',$ipfsConfigData);
//    foreach($_POST['configData'] as $key=> $value){
//        ipfs_logEvent("Array:$key:$value" );
//        if(gettype($value) == 'array'){
//            foreach ($value as $subKey=>$subValue);
//            ipfs_logEvent("Array: $subKey:$subValue");
//        }
//    }
//    file_put_contents($tempfile,serialize($_POST['configData']));
    file_put_contents($tempfile,$ipfsConfigData);
    echo "IPFS Configuration Updated";
    ipfs_logEvent("File Saved");
    $ipfs = new IPFSFiles($server, $gatewayPort, $APIPort);

    $ipfs->updateConfig($tempfile);

    wp_die();

}
//update_option('ipfs_trial',true);

function ipfs_main_Settings(){
    $ipfsStats = getRepoStats();
    $ipfsVersion = getVersion();
    $ipfsID = ipfsGet("id");

echo "<div id='mainSettings'>";
settings_fields( 'pluginPage' );
do_settings_sections( 'pluginPage' );
submit_button();
//update_option('ipfs_trial',true);

?>
<!--        <p class="submit">-->
<button  name="reload" id="reload" class="button button-primary" onclick="my_action_javascript();" value="Trigger Build of IPFS Site" >Trigger Build of IPFS Site</button>
<!--        </p>-->
</form>
</div>
<div id="ipfsStats">
    <h2>IPFS Information</h2>
    <?php

    echo "<p>Id: ". $ipfsID['ID']."</p>";
    echo "<h3>Addresses</h3><ul>";
    foreach($ipfsID["Addresses"] as $address){
        echo "<li>Address: ".$address."</li>";
    }
    echo "</ul>";
    echo "<p>Protocol Version: ". $ipfsID['ProtocolVersion']."</p>";
    echo "<p>Version: ". $ipfsVersion['Version']."</p>";
    echo "<p>Commit: ". $ipfsVersion['Commit']."</p>";
    echo "<p>System: ". $ipfsVersion['System']."</p>";
    echo "<p>Go Language Version: ". $ipfsVersion['Golang']."</p>";
    echo "<p>Required Repo Version: ". $ipfsVersion['Repo']."</p>";


    echo "<h2>Repo Stats</h2>";
    echo "<p>Repo Size: ". formatBytes($ipfsStats['RepoSize'])."</p>";
    echo "<p>Number of Objects: ". $ipfsStats['NumObjects']."</p>";
    echo "<p>Repo Path: ". $ipfsStats['RepoPath']."</p>";
    echo "<p>Repo Version: ". $ipfsStats['Version']."</p>";
    echo "<p>Repo Max Storage: ".formatBytes($ipfsStats['StorageMax'])."</p>";


    ?>
</div>
    <div id="col3">
        <table style="width:100%;">
            <tr>
                <th style="float:left">
                    Ex: swarm/peers   <button id="runIPFSCommand" class='button button-primary'  onclick="runIPFSCommand(); return false;">Run IPFS Command</button>
                </th>
            </tr>
            <tr>
                <th style="float:left">IPFS API Command:
                    <input type="text" style="width:500px" id="ipfsCommand"></th>
            </tr>
            <tr>
                <td colspan="2">
                    <pre id="ipfsCommandResponse"></pre>
                </td>
            </tr>
<!--            <tr>-->
<!--                <td>-->
<!--                    --><?php
//                        echo '<button id="ipfs_UpdateDB"  class=\'button button-primary\' onclick="ipfs_UpdateDB(); return false;">Verify DB</button>';
//                    ?>
<!--                </td>-->
<!--            </tr>-->
        </table>

    </div>

<?php
}
function ipfs_config(){
    $ipfsConfig = ipfsGet("config/show");
    ipfs_logEvent("Date == " . str_replace("\/","/",json_encode($ipfsConfig, JSON_PRETTY_PRINT)));
    ?>
    <div id="configEditor">
        <?php
        echo "<h3>Config File   -   <font class='warning'>Edit at your won Risk!!!</font></h3>";
        $Config = str_replace("\/","/",json_encode($ipfsConfig, JSON_PRETTY_PRINT));
        echo "<textarea id='configData'  cols='200' rows='30' >$Config</textarea>";
        ?>
        <button   class="button button-primary" onclick="updateConfigFile(); return false;" value="Update Config File" >Update Config File</button>

    </div>
<?php
}
function ipfs_peers(){
    $peers = ipfsGet("swarm/peers");
    ipfs_logEvent(json_encode($peers, JSON_PRETTY_PRINT));
    echo "<h3>".count($peers['Peers'])." - Peers</h3>";
    echo "<ul>";
    foreach ($peers['Peers'] as $peer){
        echo "<li>".str_replace("\/","/", $peer["Addr"])."/";
        if (!stristr($peer['Addr'],"p2p-circuit")){
            echo "/".$peer["Peer"];
        }
        echo "</li>";
    }
    echo "</ul>";

}
function ipfs_KeyManager(){
    $options = get_option( 'ipfs_settings' );
    $keys = ipfsGet("key/list");
    echo "<h3>Keys</h3>";
    echo "<table>";
    foreach ($keys['Keys'] as $key){
        echo "<tr>";
        $Name = $key['Name'];
        if(!($Name == $options['ipns_name'])){
            echo "<th><button style='margin-right:10px;' class='button button-primary' onClick='setPublishingKey("."\"".$Name.'","'.$key['Id'].'"'."); return false;'>Set to Active Key</button></th>";
        }
        else{
            echo "<th></th>";
        }

        echo "<td><p>Name: ".$key['Name']."<br>Id: ".$key['Id']."</p></td></tr>";
    }
    echo "</table>";

    ?>
        <table>
            <tr>
                <th>New Key Name: </th> <td><input type="text" id="newKeyName"></td>

            </tr>
            <tr><td colspan="2"><button class='button button-primary' onClick="createNewKey(); return false;">Create New Key</button></td></tr>
        </table>


<?php
}
function my_action() {
    global $wpdb; // this is how you get access to the database

    //ipfs_logEvent(time());
    wp_schedule_single_event( time()-60 , 'saveAllPages' );
    wp_die(); // this is required to terminate immediately and return a proper response
}


function ipfs_logManager(){
    global $wpdb, $logTable;
    $logs = $wpdb->get_results("Select logTime, logMessage, codeLocation from $logTable ORDER BY ID DESC, logTime DESC LIMIT 500;", ARRAY_A );
    ?>
    <h3>IPFS Bridge Logs</h3>
    <button class='button button-primary'  onClick="exportLogsasCSV(); return false;">Export Logs as CSV</button>

    <table>
        <tr>
            <th>DateLogged</th>
            <th>Message</th>
            <th>Code Location</th>
        </tr>
<?php
    foreach($logs as $log){
        echo "<tr>";
        echo "<td class='logDate'>".$log['logTime']."</td>";
        echo "<td class='logMessage'>".$log['logMessage']."</td>";
        echo "<td>".$log['codeLocation']."</td>";
        echo "</tr>";
    }
    ?></table>
    <?php
}
function ipfs_FileManager(){
    global $filesTable, $wpdb, $GateWayLink;
    $Files = $wpdb->get_results("Select * from $filesTable ORDER BY dateAdded DESC;", ARRAY_A );

    ?><div class="ipfsFiles">
<!--    <div style="height: 30px"><button class='button button-primary' >Upload Pending Files</button></div>-->
    <table id="nodeFiles" class="fileFrame">
        <tr id="fileHeaderRow"><th>Date Added</th><th>File Name</th><th>IPFS Hash</th></tr>
        <tbody id="fileList">
<!--    <iframe src="www.jefflubbers.com" class="ipfsFiles fileFrame"></iframe>-->
    <?php

    foreach ($Files as $file){
        $link = "$GateWayLink/ipfs/".$file['hash'];
        echo'<tr class="fileRow">';
        echo'<td style="text-align:right;">'.$file["dateAdded"].'</td><th>'.$file["filename"].'</th><td><a target="_blank" href="'.$link.'">/ipfs/'.$file["hash"].'</a></td></tr>';
    }



    ?></tbody></table></div>
    
    <?php
    addUploader();
   ?>
    <style>

        .ipfsFiles{
            width: 50%;
            height:900px;
            min-height:600px;
            float:left;
            overflow-y:scroll;
        }
        .fileFrame{
            width: 100%;
            min-height:600px;
            max-height:600px;
        }
        tbody#fileList{
            overflow-y:scroll;

            /*overflow-y: auto*/
        }
        .fileRow{
            height:30px;
            border-bottom:1px solid #333333;
        }

        tr.fileRow:nth-child(even) {background: #CCC}
        tr.fileRow:nth-child(odd) {background: #FFF}
    </style>
    <script>
        var addIPFSFileToTable = function(file){
            console.log("Add Called")
            console.log(file);
            //jsonFile = JSON.parse(file);
            var html = '<tr class="fileRow">';
            var link = "https://gateway.ipfs.io/ipfs/" + file.hash;
            html = html + '<td style="text-align:right;">' + file.dateAdded + '</td><th>' + file.name + '</th><td><a target="_blank" href="' + link + '">/ipfs/' + file.hash + '</a></td></tr></tr>';
            $('#fileList').prepend(html);
        }

    </script>
    <?php
}

function addUploader(){
    $path = plugin_dir_url(__FILE__).'uploader/index.html';
//    $fileConetnts = file_get_contents($path);
//    $fileConetnts = file_get_contents(__DIR__.'/uploader/indexmissingscriptsandCSS.html');
//    //ipfs_logEvent($fileConetnts,"Options.AddUploader");
//    echo $fileConetnts;

    ?>

    <iframe class="ipfsFiles" src="<?php echo $path?>"></iframe>

<?php

}

function IPFS_load_scripts_admin() {




// Register the script like this for a plugin:
    wp_register_script( 'external-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js' );
    wp_register_script( 'external-script1', 'https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js' );
    wp_register_script( 'external-script2', 'https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js' );
    wp_register_script( 'external-script3', 'https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js' );
    wp_register_script( 'external-script4', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
    wp_register_script( 'external-script5', 'https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js' );


    wp_register_script( 'custom-script', plugins_url( '/uploader/js/vendor/jquery.ui.widget.js', __FILE__ ) );

    wp_register_script( 'custom-script1', plugins_url( '/uploader/js/jquery.fileupload-process.js', __FILE__ ) );
    wp_register_script( 'custom-script2', plugins_url( '/uploader/js/jquery.fileupload.js', __FILE__ ) );
    wp_register_script( 'custom-script3', plugins_url( '/uploader/js/jquery.fileupload-audio.js', __FILE__ ) );
    wp_register_script( 'custom-script4', plugins_url( '/uploader/js/jquery.iframe-transport.js', __FILE__ ) );
    wp_register_script( 'custom-script5', plugins_url( '/uploader/js/jquery.fileupload-validate.js', __FILE__ ) );
    wp_register_script( 'custom-script6', plugins_url( '/uploader/js/jquery.fileupload-video.js', __FILE__ ) );
    wp_register_script( 'custom-script7', plugins_url( '/uploader/js/jquery.fileupload-ui.js ', __FILE__ ) );
    wp_register_script( 'custom-script8', plugins_url( '/uploader/js/jquery.fileupload-image.js', __FILE__ ) );
    wp_register_script( 'custom-script9', plugins_url( '/uploader/js/main.js', __FILE__ ) );
    wp_register_script( 'custom-script910', plugins_url( '/uploader/js/cors/jquery.xdr-transport.js', __FILE__ ) );

    wp_register_style( 'custom-style', plugins_url( '/uploader/css/jquery.fileupload.css', __FILE__ ), array(), '20180501', 'all' );
    wp_register_style( 'custom-style1', plugins_url( '/uploader/css/jquery.fileupload-ui.css', __FILE__ ), array(), '20180501', 'all' );
    wp_register_style( 'custom-style2', plugins_url( '/uploader/css/style.css', __FILE__ ), array(), '20180501', 'all' );
    wp_register_style( 'custom-style3', 'https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css');



    wp_enqueue_style( 'custom-style2' );
    wp_enqueue_style( 'custom-style3' );
    wp_enqueue_style( 'custom-style' );
    wp_enqueue_style( 'custom-style1' );

    wp_register_script('mediaelement', plugins_url('wp-mediaelement.min.js', __FILE__), array('jquery'), '4.8.2', true);
    wp_enqueue_script('mediaelement');

    wp_enqueue_media();

}
add_action( 'admin_enqueue_scripts', 'IPFS_load_scripts_admin' );

function addUploaderxx( $name, $width, $height ){

        // Set variables
        $options = get_option( 'RssFeedIcon_settings' );
        $default_image = plugins_url('img/no-image.png', __FILE__);

        if ( !empty( $options[$name] ) ) {
            $image_attributes = wp_get_attachment_image_src( $options[$name], array( $width, $height ) );
            $src = $image_attributes[0];
            $value = $options[$name];
        } else {
            $src = $default_image;
            $value = '';
        }

        $text = __( 'Upload' );

        // Print HTML field
        echo '
        <div class="upload">
            <img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />
            <div>
                <input type="hidden" name="RssFeedIcon_settings[' . $name . ']" id="RssFeedIcon_settings[' . $name . ']" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">' . $text . '</button>
                <button type="submit" class="remove_image_button button">&times;</button>
            </div>
        </div>
    ';


}



function ipfs_Database(){
    global $wpdb, $hashTable, $logTable, $filesTable;
    $lbHash = "Missing";
    $lbFiles = 'Missing';
    $lbLogs = 'Missing';

    if($wpdb->get_var("SHOW TABLES LIKE '$hashTable'") == $hashTable) {
        $lbHash = "Exhists";
    }
    if($wpdb->get_var("SHOW TABLES LIKE '$logTable'") == $logTable) {
        $lbLogs = "Exhists";
    }
    if($wpdb->get_var("SHOW TABLES LIKE '$filesTable'") == $filesTable) {
        $lbFiles = "Exhists";
    }

    ?>
    <div>
        <table>
            <tr>
                <th>Files Table: </th>
                <td><?php echo $lbFiles?></td>
            </tr>
            <tr>
                <th>Page Hashes Table: </th>
                <td><?php echo $lbHash?></td>
            </tr>
            <tr>
                <th>Logs Table: </th>
                <td><?php echo $lbLogs?></td>
            </tr>

        </table>

    </div>

<?php

    echo '<button id="ipfs_UpdateDB"  class=\'button button-primary\' onclick="VerifyDb();  return false; ">Verify DB</button>';
    ?>
    <div id="responseArea"></div>

    <?php
}
function ipfs_DevArea(){
    global $wpdb, $hashTable, $logTable, $filesTable;
    $lbHash = "Missing";
    $lbFiles = 'Missing';
    $lbLogs = 'Missing';

    if($wpdb->get_var("SHOW TABLES LIKE '$hashTable'") == $hashTable) {
        $lbHash = "Exhists";
    }
    if($wpdb->get_var("SHOW TABLES LIKE '$logTable'") == $logTable) {
        $lbLogs = "Exhists";
    }
    if($wpdb->get_var("SHOW TABLES LIKE '$filesTable'") == $filesTable) {
        $lbFiles = "Exhists";
    }

    ?>
    <div>
        <table>
            <tr>
                <th>Files Table: </th>
                <td><?php echo $lbFiles?></td>
            </tr>
            <tr>
                <th>Page Hashes Table: </th>
                <td><?php echo $lbHash?></td>
            </tr>
            <tr>
                <th>Logs Table: </th>
                <td><?php echo $lbLogs?></td>
            </tr>

        </table>

    </div>

    <script>
        var goLastResponse;
        function callCURL() {

            var data = {
                'action': 'ipfs_curlTest'
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                // alert(response);
                console.log(response);
                var loResponse = JSON.parse(response);

                try{
                    loResponse['body'] = JSON.parse(loResponse['body']);
                }
                catch(err){
                    console.log("Couldn't Parse Body!");
                }
                var lcResponse = JSON.stringify(loResponse)
                console.log(loResponse);
                goLastResponse = loResponse;
                document.getElementById('responseArea').innerHTML = lcResponse;
                return false;
            });
            return false;
        };
    </script>

    <?php

    echo '<button id="ipfs_UpdateDB"  class=\'button button-primary\' onclick="callCURL();  return false; ">Test Curl</button>';
    ?>
    <div id="responseArea"></div>

    <?php


}