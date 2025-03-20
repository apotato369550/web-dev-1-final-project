<?php

$authorId = $_POST["author_id"];
$requestId = $_POST["request_id"];
$requestStatus = $_POST["request_status"];

if (!isset($_POST["edit-request-status"])) {
    header("Location: ../admin/manage-requests.php?error=nosubmit");
    exit();
}

