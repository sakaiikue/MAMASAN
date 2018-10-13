<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: text/html; charset=UTF-8'); //いつでもUTF-8の設定にするためのコード



$edNumber="";
$edName="";
$edComment="";
$edPassword="";

if(!empty($_POST['edit_number']) ){
    $edit_id=$_POST['edit_number'];
    $edit_pass=$_POST['edit_password'];
    $sql = "SELECT * FROM tbikue where id=$edit_id";
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC); //一件しか存在しないカラム群を取得

    if($edit_pass=$row['password']){
        $edNumber=$row['id'];
        $edName=$row['name'];
        $edComment=$row['comment'];
        $edPassword=$row['password'];
                
            }else{
                echo "パスワードが正しくありません".'<br>';
            }  
                 
      }
    


?>
<html>
    <head>
    <meta charset="utf-8" />
       <title>mission4</title>
       </head>
       <body style="background-color:#FFFFE0;">
       <body>
                <form action="mission_4.php" method="POST">
                
                <input type="text" name="name" value="<?php echo $edName ?>" placeholder="名前" /> <br />
                <input type="text" name="comment" value="<?php echo $edComment ?>" placeholder="コメント" /><br /> <!-- inputとかく。mばつ-->
                <input type="hidden" name="edit_mienai" value="<?php echo $edNumber ?>"> <!-- name属性の中ではスペースはダメ。だいたいダメ(ファイル名とかも)。-->
                 <input type="text" name="password" value="<?php echo $edPassword ?>" placeholder="パスワード"required /> <br />
            <input type="submit" value="送信" /><br />
                <br />
                </form>
                
                <form action="mission_4.php" method="POST">                
                <input type="text" name="delete_number" value="" placeholder="削除対象番号" /><br />
                <input type="text" name="delete_password" value="" placeholder="パスワードを入力してください" /> <br />
                <input type="submit" value="削除" /><br />
                <br />
                <input type="text" name="edit_number" value="" placeholder="編集対象番号" /><br />
                <input type="text" name="edit_password" value="" placeholder="パスワードを入力してください" /> <br />
                <input type="submit" value="編集" /><br />

                </form>
       </body>
</html>

<?php

if(!empty($_POST['comment']) && !empty($_POST['name'])){
    if(empty($_POST['edit_mienai'])){                                               //追加モード

        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $date=date('Y/m/d H:i:s');
        $password=$_POST['password'];

        $sql = $pdo-> prepare("INSERT INTO tbikue (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);

        $sql -> execute();



    }else{
      // $_POST['edit mienai']が空ではない すなわち 編集モード
        $id = $_POST['edit_mienai'];
        $pass = $_POST['password'];
        $nm = $_POST['name'];
        $kome = $_POST['comment'];
        $dat = date('Y/m/d H:i:s');
        
        // $sql = "update tbikue set name=`$nm`, comment=`$kome`, date=`$dat`, password=`$pass` where id=$id and password=($pass)";
        // $result = $pdo -> query($sql);
        $stmt = $pdo->prepare('UPDATE tbikue SET name=:name, comment=:comment, password=:password, date=:date WHERE id=:id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);  //もうこの値として縛り付ける
        $stmt->bindParam(':name', $nm, PDO::PARAM_STR);  //executeされた時点で変数を評価する
        $stmt->bindParam(':comment', $kome, PDO::PARAM_STR);
        $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':date', $dat, PDO::PARAM_STR);
        $stmt->execute();
    
    }
    
}


//削除モード
if(!empty($_POST['delete_number']) ){
    $delete_id = $_POST['delete_number'];
    $delete_pass = $_POST['delete_password'];
    
    $sql = "SELECT * FROM tbikue where id=$delete_id";
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC); //一件しか存在しないカラム群を取得
    if($row===false){
        echo "該当する投稿がありません".'<br>';
    }
    if($delete_pass == $row['password']){
        $sql = "delete from tbikue  where id=$delete_id";
        $result = $pdo->query($sql);
    }else{
        echo "パスワードが正しくありません".'<br>';
    }
}

$sql = 'SELECT * FROM tbikue order by id';
$results = $pdo -> query($sql);
if($results===false){
    echo "まだ投稿がありません".'<br>';
}else{

$array = $results->fetchAll(PDO::FETCH_ASSOC);

foreach($array as $row){
    //$rowの中にはテーブルのカラム名が入る。
 echo $row['id'].',';
 echo $row['name'].',';
 echo $row['comment'].',';
 echo $row['date'].'<br>';
}
}