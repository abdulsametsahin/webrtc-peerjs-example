<?php
include '../db/db.php';

$username = @trim($_POST['username']);

$user = DB::queryFirstRow("SELECT * FROM users WHERE username = %s", $username);

echo $user['peer_id'];