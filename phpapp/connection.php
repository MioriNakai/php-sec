<?php
require_once('config.php');

// PDOクラスのインスタンス化/サーバーとデータベースのやり取り
function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
        // configから持ってきている引数、throwの説明とそこから受け取ったエラーの内容をexceptionで表している
    } catch (PDOException $e) {
        // PDOExceptionクラスがインスタンス化されたものが内容を$eに代入している
        echo $e->getMessage();
        exit();//処理を中断している
    }
}

function createTodoData($todoText)
// $postだったものが変数名を変えこの関数の引数となった。１番に説明 さっきのユーザーが入力した内容が入っている
{
    $dbh = connectPdo();
    // （）の中にインスタンス化したpdo関数が入っている
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)';
    // ここで todosにレコードの追加を行っている
    $stmt = $dbh->prepare($sql); 
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR); 
    $stmt->execute();
}

function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
    // pdostatmentクラスを返すvar_dumpでわかる、↳残っている行を取得するすべての行を含む配列を返すのが最後のやつ。$sqlの説明でこうしたらどうなるもセットで。データベースから削除日時がNULLのレコードを全取得する。
}

function updateTodoData($post)
{
    $dbh = connectPdo();//（）の中に上記のPODインスタンスを呼び出す。上記は関数宣言されただけなのでここに呼び出している。
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id'; 
    $stmt = $dbh->prepare($sql); 
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR); 
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT); 
    $stmt->execute(); //左のクラスからアローでめそっどを呼び出す。
}

function getTodoTextById($id)
{
    
    $dbh = connectPdo();
    $sql = 'SELECT content FROM todos WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data['content'];
    // 49行目でユーザーが入力した内容だけを返す
}

function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    $sql = 'UPDATE todos set deleted_at WHERE id = :id'; // ID に基づいて削除する SQL クエリ
    $stmt = $dbh->prepare($sql); // SQL ステートメントを準備
    $stmt->bindValue(':deleted_at', $now, PDO::PARAM_STR); // ID パラメータをバインド
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute(); 
}



