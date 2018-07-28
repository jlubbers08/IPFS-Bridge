<?php
require ("../function.php");
require("../../../../wp-load.php");
require_once('../IPFS.php');
global $wpdb, $filesTable, $filesAdded, $trial;
use Cloutier\PhpIpfsApi\IPFSFiles as API;


function addFileToDB($fileName, $hash){
    global $filesTable, $wpdb;
    $return = array();


    $data = array(

        "filename" => $fileName,
        "hash" => $hash
    );

    $wpdb->insert($filesTable, $data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    if($trial && $filesAdded > 99){
        $return['action'] = "alert('Unable to add more files, you have exceeded you limit in the trial version.');";
        $return['success'] = false;
        echo json_encode($return);
        die();

    }

    $path = $_POST['filePath'];
    $id = $_POST['id'];
    $fileName = $_POST['fileName'];
    ipfs_logEvent("Attempting to Upload File: ".$id." At Path: $path");
//    echo "Called.";

    $ipfs = new API($server,$gatewayPort,$APIPort);
    $hash = $ipfs->addFile($path);
//    echo $hash;
    $file = array(
        'hash'=>$hash,
        'name'=> $fileName,
        'dateAdded'=> date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )    //gmdate('Y-m-d h:i:s')
);
    $returnFile = json_encode($file);
    $return = array();
    $return['success'] = false;
    if($hash == ''){
//        addIPFSFileToTable
        $return['action'] = "alert('No Hash was returned for file: ".$fileName."');";
    }else{
        if(unlink($path)){
            addFileToDB($fileName,$hash);
            $return['success'] = true;
            $return['fileHash'] = $hash;

            $return['action'] = "$('#file-$id').remove();parent.addIPFSFileToTable(".$returnFile.");";

        }
        else{
            $return['action'] = "$('#file-$id').remove();parent.addIPFSFileToTable(".$returnFile.");alert('Failed to Delete File: $fileName \r\nExtra space is being taken up on disk!!";
        }
        $newFiles = $filesAdded + 1;
        update_option('ipfs_files_added',$newFiles);
    }
    echo json_encode($return);
    return;

}