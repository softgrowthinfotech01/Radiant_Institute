<?php

include('../admin_check.php');
include('../conn.php');

if(isset($_GET['id']) && isset($_GET['document_id'])){

    $id = intval($_GET['id']);
    $document_id = intval($_GET['document_id']);

    /* FETCH FILE */
    $stmt = $conn->prepare("
        SELECT *
        FROM document_files
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if($file){

        /* FILE PATH */
        $filePath = "../uploads/documents/" . $file['file_name'];

        /* DELETE FILE FROM FOLDER */
        if(file_exists($filePath)){
            unlink($filePath);
        }

        /* DELETE FROM DATABASE */
        $deleteStmt = $conn->prepare("
            DELETE FROM document_files
            WHERE id=?
        ");

        $deleteStmt->execute([$id]);

        header("Location: view_document.php?id=$document_id&msg=File Deleted Successfully");
        exit;

    }else{

        header("Location: view_document.php?id=$document_id&msg=File Not Found");
        exit;
    }

}else{

    echo "Invalid Request";
}
?>