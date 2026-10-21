<?php

include('../admin_check.php');
include('../conn.php');

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    try{

        /* FETCH FILES */

        $fileStmt = $conn->prepare("
            SELECT *
            FROM document_files
            WHERE document_id=?
        ");

        $fileStmt->execute([$id]);

        $files = $fileStmt->fetchAll(PDO::FETCH_ASSOC);

        /* DELETE FILES FROM FOLDER */

        foreach($files as $file){

            $path = "../uploads/documents/".$file['file_name'];

            if(file_exists($path)){

                unlink($path);

            }
        }

        /* DELETE FILE RECORDS */

        $deleteFiles = $conn->prepare("
            DELETE FROM document_files
            WHERE document_id=?
        ");

        $deleteFiles->execute([$id]);

        /* DELETE DOCUMENT */

        $deleteDoc = $conn->prepare("
            DELETE FROM documents
            WHERE id=?
        ");

        $deleteDoc->execute([$id]);

        header("Location:list_documents.php?msg=Document Deleted");
        exit;

    }catch(PDOException $e){

        echo $e->getMessage();
    }

}else{

    echo "Invalid Request";
}
?>