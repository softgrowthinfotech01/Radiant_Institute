<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$comment_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($comment_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Comment
|--------------------------------------------------------------------------
| User can delete only their own comment.
| User must also be assigned to the task.
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        c.id,
        c.task_id,
        c.user_id,
        c.comment,

        t.title AS task_title

    FROM task_comments c

    INNER JOIN tasks t
        ON c.task_id = t.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE c.id = ?
      AND c.user_id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $comment_id,
    $user_id,
    $user_id
]);

$commentData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commentData) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Delete Comment
|--------------------------------------------------------------------------
*/

try {

    $conn->beginTransaction();


    /*
    |--------------------------------------------------------------------------
    | Activity
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO task_activities
        (
            task_id,
            user_id,
            activity_type,
            description
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $commentData['task_id'],
        $user_id,
        'comment_deleted',
        'Deleted a task comment.'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Delete Comment
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM task_comments

        WHERE id = ?
          AND user_id = ?
    ");

    $stmt->execute([
        $comment_id,
        $user_id
    ]);


    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    header(
        "Location: view_task.php?id=" .
        (int) $commentData['task_id'] .
        "&success=comment_deleted"
    );

    exit;


} catch (Exception $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    header(
        "Location: view_task.php?id=" .
        (int) $commentData['task_id'] .
        "&error=comment_delete_failed"
    );

    exit;
}