<?php
include __DIR__."/../db/meekrodb.2.3.class.php";

session_start();

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'webrtc';
DB::$encoding = 'utf8'; // defaults to latin1 if omitted

