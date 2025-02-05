<?php
// created by Aaron C. 1/20/2025
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include 'SQLOp.php';
   $modeSet = $_GET['action'];
   $pcSetUpObj = new pcSetUp();


   // used to decode JSON objects in cyberScript.js.
   // the modeSet variable is used to decided which switch expression to fall into.
   // this file is associated with class pcSetUp in the SQLOp.php file.
   try{
   switch($modeSet){
    case 'view':
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        $pcSetUpObj -> connect();
        $result = $pcSetUpObj ->view_table();
        if (empty($result)) {
            echo json_encode(['message' => 'No data available']);
        } else {
            echo json_encode($result);
        }
        $pcSetUpObj ->DB_close();
        break;
    case 'add':
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        $input = json_decode(file_get_contents('php://input'), true);
        $pcSetUpObj -> connect();
        $pcSetupObj->add_row(
            $input['pc_id'],
            $input['mobo_id'],
            $input['gpu_id'],
            $input['ram_id'],
            $input['psu_id'],
            $input['monitor_id'],
            $input['acc_id'],
            $input['kb_id'],
            $input['mouse_id'],
            $input['tableLocation'],
            $input['PCcondition']
        );
        $pcSetUpObj ->DB_close();
    case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $updateArray = [];
            foreach($input as $inputKey => $value){
                if(!empty($$value) && !$inputKey['pc_id']){
                    $updateArray[$inputKey] = $value; 
                }
            }
            $pcSetUpObj -> connect();
            $pcSetup->update_row($updateArray, $input['pc_id']);
            $pcSetUpObj ->DB_close();
            echo json_encode(['message' => 'Row updated successfully']);
            break;
    case 'delete':
            $input = json_decode(file_get_contents('php://input'), true);
            $pcSetUpObj -> connect();
            $pcSetupObj ->delete_row($input['pc_id']);
            $pcSetUpObj ->DB_close();
            echo json_encode(['message' => 'Row deleted successfully']);
            break;
    default:
            echo json_encode(['message' => 'Invalid action']);
   }}
  catch (Exception $e) { header('Content-Type: application/json'); echo json_encode(['error' => $e->getMessage()]); }
?>