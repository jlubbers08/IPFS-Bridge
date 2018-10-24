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

        $pagesBuilt = get_option('ipfs_build_pages');

        if( isset( $_GET[ 'panel' ] ) ) {
            $active_tab = $_GET[ 'panel' ];

        }
        else{
            $active_tab = "main_settings";
        }
        
        echo "<div id='activeBanner'>Active</br>Current Pages Built: $pagesBuilt</br>Files Added: $filesAdded";

        echo '</div>';

        

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

        #MainContainer {

            max-width: 50%;
            margin-left: 50%;
            width: 100%;
            align-content: center;
            text-align: center;

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


function ipfs_main_Settings(){
    $ipfsStats = getRepoStats();
    $ipfsVersion = getVersion();
    $ipfsID = ipfsGet("id");

echo "<div id='mainSettings'>";
settings_fields( 'pluginPage' );
do_settings_sections( 'pluginPage' );
submit_button();


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
    $path = plugin_dir_url(__FILE__);
    //$path = plugin_dir_path
//    $fileConetnts = file_get_contents($path);
//    $fileConetnts = file_get_contents(__DIR__.'/uploader/indexmissingscriptsandCSS.html');
//    //ipfs_logEvent($fileConetnts,"Options.AddUploader");
//    echo $fileConetnts;

    ?>

<!--    <iframe class="ipfsFiles" src="--><?php //echo $path?><!--"></iframe>-->
<!--    --><?php //echo $path;?>

<!--    <link rel="stylesheet" href="--><?php //echo $path;?><!--uploader/assets/font-awesome.css" type="text/css"/>-->
<!--    <link rel="stylesheet" href="--><?php //echo $path;?><!--uploader/assets/theme.css" type="text/css"/>-->
<!--    <script src="--><?php //echo $path;?><!--uploader/assets/IpfsApi.js"></script>-->
<!--    <script src="--><?php //echo $path;?><!--uploader/assets/CustomJS.js"></script>-->
<!--    <script src="--><?php //echo $path;?><!--uploader/assets/jquery-3.js"></script>-->
<!--    <script src="--><?php //echo $path;?><!--uploader/assets/IPFSjs.js"></script>-->
<!--    <!-- Bootstrap styles -->-->
<!--    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->-->
<!--    <!-- Generic page styles -->-->
<!---->
<!--    <link rel="stylesheet" href="--><?php //echo $path;?><!--uploader/assets/bootstrap.css">-->
<!---->
<!--    <link href="--><?php //echo $path;?><!--uploader/assets/icon.css" rel="stylesheet">-->
<!--    <link rel="stylesheet" href="--><?php //echo $path;?><!--uploader/assets/style.css">-->
<!---->
<!--    <!-- blueimp Gallery styles -->-->
<!--    <link rel="stylesheet" href="--><?php //echo $path;?><!--uploader/assets/blueimp-gallery.css"/>-->
<!--    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->-->
<!--    <link rel="stylesheet" href=--><?php //echo $path;?><!--uploader/assets/jquery_002.css"/>-->
<!--    <link rel="stylesheet" href=--><?php //echo $path;?><!--uploader/assets/jquery_003.css"/>-->
<!---->
<!--    <!--<script src="assets/app.js"></script>-->-->
<!---->
<!--    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_009.js"></script>-->
<!--    <!-- The Templates plugin is included to render the upload/download listings -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/tmpl.js"></script>-->
<!--    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/load-image.js"></script>-->
<!--    <!-- The Canvas to Blob plugin is included for image resizing functionality -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/canvas-to-blob.js"></script>-->
<!--    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->-->
<!--    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->-->
<!--    <!-- blueimp Gallery script -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_003.js"></script>-->
<!--    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_010.js"></script>-->
<!--    <!-- The basic File Upload plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_007.js"></script>-->
<!--    <!-- The File Upload processing plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_004.js"></script>-->
<!--    <!-- The File Upload image preview & resize plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery.js"></script>-->
<!--    <!-- The File Upload audio preview plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_002.js"></script>-->
<!--    <!-- The File Upload video preview plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_011.js"></script>-->
<!--    <!-- The File Upload validation plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_006.js"></script>-->
<!--    <!-- The File Upload user interface plugin -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_008.js"></script>-->
<!--    <!-- The main application script -->-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/main.js"></script>-->
<!--    <style>-->
<!--        .collapse.in {-->
<!--            display: block;-->
<!--        }-->
<!--    </style>-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/bootstrap.js"></script>-->
<!---->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/bootstrap-confirmation.js"></script>-->
<!--    <link rel="stylesheet" href=--><?php //echo $path;?><!--uploader/assets/styles.css"/>-->
<!--    <script src=--><?php //echo $path;?><!--uploader/assets/jquery_005.js"></script>-->
<!--    <link rel="stylesheet" href=--><?php //echo $path;?><!--uploader/assets/jquery.css"/>-->

    <div id="displayBodyt" data-gr-c-s-loaded="true">

    <div class="container" id="MainContainer">
        <!--
        /*
         * jQuery File Upload Plugin Demo
         * https://github.com/blueimp/jQuery-File-Upload
         *
         * Copyright 2010, Sebastian Tschan
         * https://blueimp.net
         *
         * Licensed under the MIT license:
         * https://opensource.org/licenses/MIT
         */
        -->


        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <meta charset="utf-8">

        <meta name="description" content="File Upload widget with multiple file selection, drag&amp;drop support, progress bars, validation and preview images, audio and video for jQuery. Supports cross-domain, chunked and resumable file uploads and client-side image resizing. Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <script>

            function later(){
                console.log("A Function Needs to Changed.")
                alert("This Function needs to be assigned / changed");
            }


            var knownFolderHashes = [];

            var AllFiles = {};





            function saveToIpfs (reader, pcFile) {


                // ENTIRE GOOD CODE __________________________
                var Buffer = ipfs.Buffer;
                // var buffer = new ReadableStream(pcFile);
                var buffer = Buffer.from(reader.result);
                var uploadSize = buffer.length;
                var lcFileName = pcFile.name.replace(/ /g, "");

                var addFiles = [
                    {
                        path:  lcFileName,
                        content:buffer
                    }
                ];

                ipfs.add(addFiles, {wrapWithDirectory :true, progress: (prog) => {
                        // Log(JSON.stringify(prog), "saveToIpfs");
                        var ele = document.getElementById("Progress-" + lcFileName);
                        //ele.setAttribute("aria-valuenow", Math.round(prog / uploadSize * 100));
                        ele.style.width = Math.round(prog / uploadSize * 100) + "%";

                    } })
                    .then((response, err) => {
                        Log(JSON.stringify(response), "saveToIpfs");
                        Log(pcFile.name, "saveToIpfs");
                        var i;
                        for(i=0; i< response.length; i++){
                            lcFolderHash = response[i].hash;
                        }


                        Log(response[0].hash, "saveToIpfs");
                        addFileToDb(response, pcFile);
                        removeFile(pcFile.name);
                        if(knownFolderHashes.includes(lcFolderHash)){
                            updateIPFSFolderHashes(lcFolderHash, lcFileName, response[0].hash);
                        }
                        else{
                            knownFolderHashes.push(lcFolderHash);
                        }

                        // this.setState({added_file_hash: ipfsId})
                    }).catch((err) => {
                    var ele = document.getElementById("Progress-" + lcFileName);
                    //ele.setAttribute("aria-valuenow", Math.round(prog / uploadSize * 100));
                    ele.style.width = 0 + "%";
                    var errMsg = document.getElementById("badFile-" +lcFileName);
                    errMsg.style.display = "block";
                    console.error(err)
                })
                // ENTIRE GOOD CODE END ------------------------------------------------------

            }
            function removeFile(pcFileName){
                delete AllFiles[pcFileName];
                document.getElementById(pcFileName).remove();
            }


            function removePending(){
                for(var fileKey in AllFiles) {
                    // Log( AllFiles[fileKey]);

                    removeFile(AllFiles[fileKey].name);
                }
            }

            function bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0) return '0 Byte';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return (bytes / Math.pow(1024, i)).toFixed(0) + ' ' + sizes[i];
            };



            jQuery('#btnDelete').confirmation({
                rootSelector: 'btnDelete',
                buttons:[
                    {
                        class:'btn btn-success',
                        iconClass: 'material-icons',
                        iconContent: 'check',
                        label: 'Continue'
                    },{
                        class:'btn btn-danger',
                        iconClass: 'material-icons',
                        iconContent: 'close',
                        label: 'Nope'
                    }
                ],
                title: "Are you sure",
                content: "All Files will be removed from your local database but not from your ipfs node.",
                onConfirm:later
                // other options
            });





            (function($){
                $.fn.tzCheckbox = function(options){
                    console.log("GettingBoxes")
                    // Default On / Off labels:

                    options = $.extend({
                        labels : ['ON','OFF']
                    },options);

                    return this.each(function(){
                        var originalCheckBox = $(this),
                            labels = [];

                        // Checking for the data-on / data-off HTML5 data attributes:
                        if(originalCheckBox.data('on')){
                            labels[0] = originalCheckBox.data('on');
                            labels[1] = originalCheckBox.data('off');
                        }
                        else labels = options.labels;

                        // Creating the new checkbox markup:
                        var checkBox = $('<span>',{
                            className	: 'tzCheckBox '+(this.checked?'checked':''),
                            html:	'<span class="tzCBContent">'+labels[this.checked?0:1]+
                            '</span><span class="tzCBPart"></span>'
                        });

                        // Inserting the new checkbox, and hiding the original:
                        checkBox.insertAfter(originalCheckBox.hide());

                        checkBox.click(function(){
                            console.log("Checkking")
                            checkBox.toggleClass('checked');

                            var isChecked = checkBox.hasClass('checked');

                            // Synchronizing the original checkbox:
                            originalCheckBox.attr('checked',isChecked);
                            checkBox.find('.tzCBContent').html(labels[isChecked?0:1]);
                        });

                        // Listening for changes on the original and affecting the new one:
                        originalCheckBox.bind('change',function(){
                            checkBox.click();
                        });
                    });
                };
            })(jQuery);


        </script>

<!--        <script src="uploader/assets/jquery_005.js"></script>-->



        <div class="container">

            <h1 class=" text-center">IPFS File Uploader</h1>


            <br>
            <blockquote class="lead text-center">
                <p>The IPFS File Uploader saves the Files to your disk and adds then to IPFS upon completion of upload.</br>
                    when the Files have been successfully added to your IPFS node an IPFS link will  be generated.</br>
                    The files will then be deleted from the servers disk to save space.</p>
            </blockquote>
            <br>


            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="col-sm-12 text-center">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                    <i class="material-icons">attach_file</i>
                    <span>Select files...</span>
                            <!-- The file input field used as target for the file upload widget -->
                    <input id="FileInput" name="files[]" multiple="" type="file">
                </span>
                        <span class="btn btn-primary" onclick="later();">
                    <i class="material-icons">backup</i>
                    <span>Start upload</span>
                </span>
                        <!--<span class="btn btn-primary" onclick="later();">-->
                        <!--<i class="material-icons">backup</i>-->
                        <!--<span>Upload all to same Directory</span>-->
                        <!--</span>-->
                        <button type="reset" onclick="removePending();" class="btn btn-warning cancel">
                            <i class="material-icons">remove_circle</i>
                            <span>Cancel upload</span>
                        </button>
                        <button onclick="later();" id="btnDelete" class="btn btn-danger delete" type="button" data-original-title="" title="">
                            <i class="material-icons">delete</i>
                            <span>Delete Stored Files</span>
                        </button>
                        <!--onConfirm="deleteFiles();"-->
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped"><tbody id="DisplayFiles" class="files"></tbody></table>
            </form>
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Files Added</h3>
                </div>
                <div class="row panel-body">
                    <table class="table table-hover table-striped">
                        <!--<thead class="thead-dark">-->
                        <!--<tr>-->
                        <!--<th class="check"> </th>-->
                        <!--<th> </th>-->
                        <!--<th>Date Added</th>-->
                        <!--<th>File Name</th>-->
                        <!--<th>Hash Link</th>-->
                        <!--</tr>-->
                        <!--</thead>-->
                        <tbody id="files"> </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- The blueimp Gallery widget -->
<!--        <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">-->
<!--            <div class="slides"></div>-->
<!--            <h3 class="title"></h3>-->
<!--            <a class="prev">‹</a>-->
<!--            <a class="next">›</a>-->
<!--            <a class="close">×</a>-->
<!--            <a class="play-pause"></a>-->
<!--            <ol class="indicator"></ol>-->
<!--        </div>-->

        <div id="cover" style="display: none; opacity: 0;">
            <div id="spinner" style="top: 591.5px; left: 579.5px; position: absolute;"></div>
            <div id="messageBox" style="top: 711.5px;">
                <div id="loadingMessage" style="display: block; left: 536.5px;"></div>
            </div>

        </div>


    </div>
    <script>
        function addToIpfs(){
            later();
        }
    </script>
    <script>

        document.getElementById("FileInput").onchange = function(){
            // console.log($(this)[0].files);
            var files = $(this)[0].files;
            // later();
            var displayTable = document.getElementById("files");
            for ( var i = 0, file; file = files[i]; i++){
                // console.log(file);
                var lcFileName = file.name.replace(/ /g, '');
                console.log(file);
                AllFiles[file.name] = file;
                var tr = document.createElement("tr");
                tr.className = "template-upload";
                tr.id = file.name;
                // tr.className = "template-upload fade";
                var tdWorkSpace = document.createElement("td");

                var tdPreviewSpan = document.createElement("span");
                tdPreviewSpan.className = "preview";
                tdWorkSpace.appendChild(tdPreviewSpan);
                tr.appendChild(tdWorkSpace);

                tdWorkSpace = document.createElement("td");
                var div = document.createElement("div");
                var tdInnerWork = document.createElement("p");
                tdInnerWork.className = "name";
                tdInnerWork.innerText = lcFileName;
                div.appendChild(tdInnerWork)
                tdInnerWork =document.createElement("br");
                div.appendChild(tdInnerWork)
                tdInnerWork = document.createElement("div");
                tdInnerWork.id = "badFile-" +lcFileName;
                tdInnerWork.style.display = "none";
                tdInnerWork.className = "alert alert-danger";
                var span = document.createElement("span");
                span.innerHTML = "<strong>Error!</strong>  Something bad happened in the upload. Please try again.";
                tdInnerWork.appendChild(span);
                div.appendChild(tdInnerWork)



                tdWorkSpace.appendChild(div);


                // tdInnerWork = document.createElement("Strong");
                // tdInnerWork.className = "error text-danger";
                // tdWorkSpace.appendChild(tdInnerWork);

                tr.appendChild(tdWorkSpace);
                tdWorkSpace = document.createElement("td");
                tdInnerWork = document.createElement("p");
                tdInnerWork.className = "size";
                tdInnerWork.innerHTML = bytesToSize(file.size);
                tdWorkSpace.appendChild(tdInnerWork);
                tdInnerWork = document.createElement("div");
                tdInnerWork.className = "progress progress-striped active";
                tdInnerWork.role = "progressbar";
                tdInnerWork.setAttribute("aria-valuemin", "0");
                tdInnerWork.setAttribute("aria-valuemax", "100");
                tdInnerWork.setAttribute("aria-valuenow", "0");
                // tdInnerWork.style.width = "100%";

                var innerProgress = document.createElement("div");
                innerProgress.className = "progress-bar progress-bar-success";
                innerProgress.style.width = "0%";
                innerProgress.id = "Progress-" + lcFileName;
                tdInnerWork.appendChild(innerProgress);
                //TODO Add INNER PROGRESSBAR SUCCESS

                tdWorkSpace.appendChild(tdInnerWork);
                tr.appendChild(tdWorkSpace);

                tdWorkSpace = document.createElement("td");
                tdWorkSpace.style.verticalAlign = "middle";
                tdWorkSpace.style.textAlign = "center";
                tdInnerWork = document.createElement("div");
                tdInnerWork.style.width = "80%";
                tdInnerWork.className = "btn btn-primary start glyphicon glyphicon-upload";
                tdInnerWork.setAttribute("onClick", "addToIpfs('" +file.name + "');");
                // tdInnerWork.disabled;
                tdInnerWork.innerHTML = "Start"
                tdWorkSpace.appendChild(tdInnerWork);
                tr.appendChild(tdWorkSpace);
                tdWorkSpace = document.createElement("td");
                tdWorkSpace.style.verticalAlign = "middle";
                tdWorkSpace.style.textAlign = "center";
                tdInnerWork = document.createElement("button");
                tdInnerWork.style.width = "80%";
                tdInnerWork.className = "btn btn-warning cancel glyphicon glyphicon-ban-cancel";
                tdInnerWork.setAttribute("onClick", "removeFile('" + file.name + "');");
                tdInnerWork.innerHTML = "Cancel"
                tdWorkSpace.appendChild(tdInnerWork);
                tr.appendChild(tdWorkSpace);




                displayTable.appendChild(tr);
            }
            document.getElementById('FileInput').value = '';
        };
    </script>


    </div>
















<?php

}

function IPFS_load_scripts_admin() {

    $root =  plugin_dir_url(__FILE__) . 'uploader/assets/';

// Register the script like this for a plugin:
    wp_register_style("Uploader1", $root.'font-awesome.css', false);
    wp_enqueue_style("Uploader1");
    wp_register_style("Uploader2", $root.'theme.css', false);
    wp_enqueue_style("Uploader2");
    wp_register_style("Uploader3", $root.'bootstrap.css', false);
    wp_enqueue_style("Uploader3");
    wp_register_style("Uploader4", $root.'blueimp-galler.css', false);
    wp_enqueue_style("Uploader4");
    wp_register_style("Uploader5", $root.'style.css', false);
    wp_enqueue_style("Uploader5");

    wp_register_style("Uploader6", $root.'jquery_002.css', false);
    wp_enqueue_style("Uploader6");
    wp_register_style("Uploader7", $root.'jquery_003.css', false);
    wp_enqueue_style("Uploader7");
    wp_register_style("Uploader8", $root.'icon.css', false);
    wp_enqueue_style("Uploader8");
    wp_register_style("Uploader9", $root.'styles.css', false);
    wp_enqueue_style("Uploader9");
    wp_register_style("Uploader19", $root.'jquery.css', false);
    wp_enqueue_style("Uploader19");

    wp_enqueue_script('custom-js', $root.'bootstrap.js', array(), false);
    wp_enqueue_script('custom-js1', $root.'bootstrap-confirmation.js', array(), false);
    wp_enqueue_script('custom-js2', $root.'canvas-to-blob.js', array(), false);
    wp_enqueue_script('custom-js3', $root.'CustomJS.js', array(), false);
    wp_enqueue_script('custom-js4', $root.'IpfsApi.js', array(), false);
    wp_enqueue_script('custom-js5', $root.'IPFSjs.js', array(), false);
//    wp_enqueue_script('custom-js6', $root.'jquery.js', array(), false);
//    wp_enqueue_script('custom-js7', $root.'jquery_002.js', array(), false);
    wp_enqueue_script('custom-js8', $root.'jquery_003.js', array(), false);
//    wp_enqueue_script('custom-js9', $root.'jquery_004.js', array(), false);
//    wp_enqueue_script('custom-js10', $root.'jquery_005.js', array(), false);
//    wp_enqueue_script('custom-js11', $root.'jquery_006.js', array(), false);
    wp_enqueue_script('custom-js12', $root.'jquery_007.js', array(), false);
    wp_enqueue_script('custom-js13', $root.'jquery_008.js', array(), false);
    wp_enqueue_script('custom-js14', $root.'jquery_009.js', array(), false);
    wp_enqueue_script('custom-js15', $root.'jquery_010.js', array(), false);
//    wp_enqueue_script('custom-js16', $root.'jquery_011.js', array(), false);
    wp_enqueue_script('custom-js17', $root.'load-image.js', array(), false);
    wp_enqueue_script('custom-js18', $root.'main.js', array(), false);
    wp_enqueue_script('custom-js19', $root.'tmpl.js', array(), false);





    wp_enqueue_media();

    // Your custom js file
    wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
    wp_enqueue_script( 'media-lib-uploader-js' );




}
add_action( 'admin_enqueue_scripts', 'IPFS_load_scripts_admin' );

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
