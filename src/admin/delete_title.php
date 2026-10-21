<?php

include('../admin_check.php');
include('../conn.php');

/* CHECK ID */
if(!isset($_GET['id'])){
    die("Invalid Request");
}

$id = (int)$_GET['id'];

/* DELETE */
$stmt = $conn->prepare("
    DELETE FROM document_titles
    WHERE id=?
");

$stmt->execute([$id]);

header("Location: list_title.php?msg=Title Deleted");
exit;

?>