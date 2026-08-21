<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$subtask_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($subtask_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Subtask
|--------------------------------------------------------------------------
| Only the user who created the subtask can delete it.
| The user must also be assigned to the parent task.
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        s.id,
        s.task_id,
        s.title,
        s.created_by,

        t.title AS task_title

    FROM task_subtasks s

    INNER JOIN tasks t
        ON s.task_id = t.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE s.id = ?
      AND s.created_by = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $subtask_id,
    $user_id,
    $user_id
]);

$subtask = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subtask) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Delete Subtask
|--------------------------------------------------------------------------
*/

try {

    $conn->beginTransaction();


    /*
    |--------------------------------------------------------------------------
    | Activity
    |--------------------------------------------------------------------------
    */

    $activityDescription =
        'Deleted subtask: "' .
        $subtask['title'] .
        '".';

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
        $subtask['task_id'],
        $user_id,
        'subtask_deleted',
        $activityDescription
    ]);


    /*
    |--------------------------------------------------------------------------
    | Delete Subtask
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM task_subtasks
        WHERE id = ?
          AND created_by = ?
    ");

    $stmt->execute([
        $subtask_id,
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
        (int) $subtask['task_id'] .
        "&success=subtask_deleted"
    );

    exit;


} catch (Exception $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    header(
        "Location: view_task.php?id=" .
        (int) $subtask['task_id'] .
        "&error=subtask_delete_failed"
    );

    exit;
}