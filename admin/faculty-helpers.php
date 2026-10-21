<?php

/*
|--------------------------------------------------------------------------
| FACULTY MODULE HELPERS
|--------------------------------------------------------------------------
| Shared by faculty.php, add-faculty.php, edit-faculty.php, delete-faculty.php
*/

/* Photos live in admin/uploads/faculty/ */
define('FACULTY_UPLOAD_DIR', __DIR__ . '/uploads/faculty/');

/* HTML-escape anything printed from the database or the request */
function esc($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/*
| Save an uploaded faculty photo.
| Returns the stored file name, or null if the file is missing/invalid.
*/
function faculty_save_photo($file)
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    // must really be an image, not just a renamed file
    if (@getimagesize($file['tmp_name']) === false) {
        return null;
    }

    if (!is_dir(FACULTY_UPLOAD_DIR)) {
        mkdir(FACULTY_UPLOAD_DIR, 0755, true);
    }

    $name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], FACULTY_UPLOAD_DIR . $name)) {
        return null;
    }

    return $name;
}

/* Remove a stored faculty photo from disk (no-op if it isn't there) */
function faculty_delete_photo($name)
{
    if (empty($name)) {
        return;
    }

    $path = FACULTY_UPLOAD_DIR . basename($name);

    if (is_file($path)) {
        unlink($path);
    }
}

?>
