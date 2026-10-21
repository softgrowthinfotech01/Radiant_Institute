<?php

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

function deleteImage($folder, $imageName){

    if(
        !empty($imageName) &&
        file_exists($folder.'/'.$imageName)
    ){

        unlink($folder.'/'.$imageName);
    }
}

/*
|--------------------------------------------------------------------------
| DELETE IMAGE RECORD FROM DATABASE
|--------------------------------------------------------------------------
*/

function deleteImageRecord($conn, $table, $id){

    $stmt = $conn->prepare("
        DELETE FROM {$table}
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);
}

?>