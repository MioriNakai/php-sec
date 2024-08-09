<?php
require_once('functions.php');//fu..を読み込んでいる（実行している）それぞれのファイルの上にあるのでどんどん実行される、ユーザーの入力した情報を保存する事が定義された関数である。
// var_dump($_POST);  コンソールといって検証画面内にある、中身を見るやつ
// exit; 
savePostedData($_POST);

header('Location: ./index.php');//画面とファイルが移動している。
//connectPDOでPDOをインスタンス化している。




//$_post（createの引数であることも伝える。）にユーザーが入力フォームに入力したデータが入る。ユーザーが入力したkey（content）に紐づくvalueをcreateDataの引数に渡している。ここで処理は行われていない。
//2行目で処理しているファイルを呼び出している.リクワイアワンスで一度だけ呼び出している