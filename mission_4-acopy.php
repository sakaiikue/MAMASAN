<?php

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);


$sql="CREATE TABLE tbikue"
."("
."id int not null auto_increment primary key,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
."password TEXT"
.");";
$stmt = $pdo->query($sql);

?>