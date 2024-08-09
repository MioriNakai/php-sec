<?php
require_once('connection.php');
session_start();

function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// SESSIONにtokenを格納する
function setToken()
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

// SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetError()
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}


function getTodoList()
{
    return getAllRecords();
}

function getSelectedTodo($id)
{
    return getTodoTextById($id); 
}

function savePostedData($post)
{
    checkToken($post['token']);
    validate($post);
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':
            createTodoData($post['content']);
            break;
        case '/edit.php':
            updateTodoData($post);
            // ここでconnectionにいく
            break;
            case '/index.php': 
              deleteTodoData($post['id']); 
              break;
        default:
            break;
    }
}
// switch文の処理

function validate($post)
{
    if (isset($post['content']) && $post['content'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

function getRefererPath()
{
  // var_dump('<pre>');
  // var_dump(parse_url($_SERVER['HTTP_REFERER']));
  // var_dump('</pre>');
  //  exit;
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    return $urlArray['path'];
    // parse_urlでURLを分解している。HTTP_REFERERは遷移する前のURLを取得。URL内のPATH部分を取得　edit.phpのとこ。スーパーグロバル。
}

// $postの中に連想配列としてユーザーが入力した内容が入ってると意味している