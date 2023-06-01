<?php 

if (
    isset($_POST['query-string']) &&
    isset($_POST['redirect-to']) &&
    $_POST['query-string'] != ''
    ) {

    require __DIR__ . '/../modules/dbConnection.php';
    $isSuccess = postgreQueryTCL(json_decode($_POST['query-string']));

}



?>

<form action="<?php echo $_POST['redirect-to'] ?>" id="indomie" method="post">
    <input type="hidden" value="<?= $isSuccess ?>" name="is-success" form="indomie">
</form>

<script type="text/javascript">
    document.getElementById('indomie').submit();
</script>