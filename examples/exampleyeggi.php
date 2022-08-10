<?php
    require_once(dirname(__FILE__) . '/wp-config.php');
    $wp->init();
    $wp->parse_request();
    $wp->query_posts();
    $wp->register_globals();

    $conn = new mysqli("localhost", "yeggi", "yeggi", "mysql");

    if ($conn->connect_error) {
    die("ERROR: Unable to connect: " . $conn->connect_error);
    } 
    debug_to_console('Connected to the database');
    header('Content-Type: application/json');

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    $aResult = array();

    debug_to_console("HI");

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'correct':
            //    if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) ) {
            //        $aResult['error'] = 'Error in arguments!';
            //    } 
                $sql = "UPDATE GroupA SET Correct= (Correct + 1) where Word=" + $_POST['arguments'];
                if ($conn->query($sql) === TRUE) {
                    $aResult['result'] = 'increased correct';
                } else {
                    $aResult['error'] = "Error updating record: " . $conn->error;
                }
                break;

            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }

        
    debug_to_console($aResult);

?>