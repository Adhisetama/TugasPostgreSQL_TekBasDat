<?php 

if (
    isset($_POST['query-string']) &&
    isset($_POST['redirect-to']) &&
    $_POST['query-string'] != ''
    ) {

    require __DIR__ . '/../modules/dbConnection.php';
    var_dump(postgreQueryTCL(json_decode($_POST['query-string'])));
    // header('Location: '.$_POST['redirect']);

}


?>