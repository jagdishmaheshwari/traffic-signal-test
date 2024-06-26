<?php
session_start();
// sleep(2);
if (isset($_POST)) {
    $seq = $_POST['signal'] ?? [];
    sort($seq);
    array_values($seq);
    $expArr = [1, 2, 3, 4];
    $Response = [];
    if ($_POST['YellowTime'] > 0 && $_POST['GreenTime'] > 0 && isset($_POST['GreenTime']) && isset($_POST['YellowTime']) && isset($_POST['signal']) && $_POST['GreenTime'] >= $_POST['YellowTime'] && $expArr == $seq) {
        if(isset($_POST['flag']) && $_POST['flag'] == 'stop'){
            session_destroy();
            exit();
        }
        if (isset($_POST['flag']) && $_POST['flag'] == 'start') {
            $sequence = $_POST['signal'];
            asort($sequence);
            $_SESSION['sequence'] = $sequence;
            $_SESSION['open'] = 1;
            $_SESSION['GreenTime'] = $_POST['GreenTime'];
            $_SESSION['YellowTime'] = $_POST['YellowTime'];


            $Response['open'] = array_search(1, $_SESSION['sequence']);
            $Response['next'] = array_search(2, $_SESSION['sequence']);
            $Response['GreenTime'] = $_SESSION['GreenTime'];
            $Response['YellowTime'] = $_SESSION['YellowTime'];
            $_SESSION['open']++;
        }else{
            $_SESSION['open'] > 4 ? $_SESSION['open'] = 1 : $_SESSION['open']; 

            $sequence =  $_SESSION['sequence'];


            $Response['open'] =  array_search($_SESSION['open'], $sequence);
            $Response['next'] =  array_search(($_SESSION['open'] + 1 > 4 ? 1 : $_SESSION['open'] + 1), $sequence);
            $Response['GreenTime'] =  $_SESSION['GreenTime'];
            $Response['YellowTime'] =  $_SESSION['YellowTime'];
            
            $_SESSION['open']++;
        }
        echo json_encode($Response);exit();
    }else{
        echo json_encode(['message' => "Invalid Data Passed!"]);
        session_destroy();
    }
}else{
    http_response_code(400);
    echo json_encode(['message' => "Invalid request!"]);
}
function prd($a)
{
    echo "<pre>";
    print_r($a);
    echo "</pre>";
    die;
}
