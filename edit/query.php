<?php 

if (isset($_POST['querystring']) && $_POST['querystring'] != '') {
    require __DIR__ . '/../modules/dbConnection.php';
    // var_dump(postgreQuery($_POST['querystring']));
    var_dump($_POST);
    // header('Location: '.$_POST['redirect']);
}


?>