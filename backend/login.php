<?php
include '../db/db.php';

$username = @trim($_POST['username']);
$password = @trim($_POST['password']);

if (!$username || !$password) {
	$data = [
		'status' => 'error',
		'message' => 'Lütfen boş alan bırakmayınız.'
	];
	die(json_encode($data));
}

if (strlen($username) < 4 || strlen($password) < 4) {
	$data = [
		'status' => 'error',
		'message' => 'Kullanıcı adı ve şifre en az 4 karakterden oluşmalı.'
	];
	die(json_encode($data));
}

$user = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND password = %s LIMIT 1", $username, $password);

if (!$user) {
	$user = DB::queryFirstRow("SELECT * FROM users WHERE username = %s LIMIT 1", $username);
	if ($user) {
		$data = [
			'status' => 'error',
			'message' => 'Böyle bir kullanıcı zaten var. Şifreyi yanlış girdiniz.'
		];
		die(json_encode($data));
	}

	$user = DB::insert('users', [
		'username' => $username,
		'password' => $password
	]);

	$user = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND password = %s LIMIT 1", $username, $password);
}

$_SESSION['user'] = $user;

$data = [
		'status' => 'success',
		'message' => 'Giriş yapıldı.'
	];
die(json_encode($data));