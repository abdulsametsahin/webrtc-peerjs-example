<?php
include '../db/db.php';

$username = @trim($_POST['username']);
$peer_id = @trim($_POST['peer_id']);

$update = DB::update("users", ['peer_id' => $peer_id], "username = '$username'");

var_dump($update);