<?php
// セッションスタート
session_start();
// セッション変数からログイン済みか確認
if (!isset($_SESSION['user_id'])) {
    // ログインしていない場合、ログインページへリダイレクト
    header('Location: login.php');
    exit;
}


$img_dir = './img/';

$user_id = $_SESSION['user_id'];
$top_user_name = $_SESSION['user_name'];

// 初期化
$data = [];
$sub_total_list = [];
$errors = [];
$sub_total = 0;
$total = 0;
$total_amount = 0;

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など

// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // 入力データがPOSTで送信された場合の処理開始Ⅰ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql_kind = get_post_data('sql_kind');
        $item_id = get_post_data('item_id');
        $amount = get_post_data('amount');
            
        // ①在庫数変更時の処理開始
        if ($sql_kind === 'amount_update') {
            
            // 「数量」について
            // 未入力の場合
            if ($amount === '') {
                $errors[] = '「数量」を入力してください'; 
            }
            // 半角数字以外を入力した場合
            elseif (preg_match($non_num, $amount)) {
                $errors[] = '「数量」は半角数字で入力してください';
            }
            // 複数桁の数字で頭が０の場合、
            elseif (preg_match($zero_start, $amount)) {
                $errors[] = '「数量」は複数桁の場合、頭は0以外の半角数字にしてください';
            }
            // 10,000個より多い場合
            elseif ($amount > 10000) {
                $errors[] = '「数量」は1万個以下にしてください';
            }
            
            // 上記エラーに１つも該当しない場合、
            if (count($errors) === 0) {
                try {
                    // 購入予定個数の更新
                    $sql = 'UPDATE bungu_carts 
                    SET amount = ?, update_datetime = now() 
                    WHERE user_id = ? AND item_id = ?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $amount, PDO::PARAM_INT);
                    $stmt->bindValue(2, $user_id, PDO::PARAM_STR);
                    $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo '数量を変更しました';
                } catch (PDOException $e) {
                    throw $e;
                }
            }
            // エラーが無かった場合の処理終了                
                
        }
        // ①在庫数変更時の処理終了
        
        // ②削除ボタン実行時の処理開始
        elseif ($sql_kind === 'delete') {
            try {
                // 購入予定商品の削除
                $sql = 'DELETE FROM bungu_carts 
                WHERE user_id =? AND item_id = ?';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
                $stmt->bindValue(2, $item_id, PDO::PARAM_STR);
                $stmt->execute();
                echo 'カートから削除しました';
            } catch (PDOException $e) {
                throw $e;
            }
        }
        // ②削除ボタン実行時の処理終了
        
    }
    // 入力データがPOSTで送信された場合の処理終了Ⅰ
    
    // データの取得開始
    try {
        // テーブルの結合参照(ステータス公開の商品のみ全て表示)
        $sql = 'SELECT bungu_item_master.item_id, name, price, item_img, user_id, amount 
        FROM bungu_item_master INNER JOIN bungu_carts 
        ON bungu_item_master.item_id = bungu_carts.item_id 
        AND amount > 0 AND user_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
            // 各購入予定数量を配列に格納
            $amount_list[] = $row['amount'];
        }
    } catch (PDOException $e) {
        throw $e;
    }
    // データの取得終了
    
    // 合計購入予定数量を計算
    if (isset($amount_list)) {
        foreach ($amount_list as $each_amount) {
            $total_amount += $each_amount;
        }
    }
    
    // 入力データがPOSTで送信された場合の処理開始Ⅱ
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
        
        // ③購入処理開始
        if ($sql_kind === 'purchase') {
            // DB接続
            try {
                // 購入商品毎に、SQL確認
                foreach($data as $value) {
                 
                    // 購入予定商品の現在在庫数を取得
                    $sql = 'SELECT name, stock, amount 
                    FROM bungu_carts INNER JOIN bungu_item_stock 
                    ON bungu_carts.item_id = bungu_item_stock.item_id
                    INNER JOIN bungu_item_master 
                    ON bungu_item_stock.item_id = bungu_item_master.item_id
                    AND user_id = ?
                    AND bungu_carts.item_id = ?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
                    $stmt->bindValue(2, $value['item_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        $name = $row['name'];
                        $stock = $row['stock'];
                        $amount = $row['amount'];
                        if ($amount > $stock) {
                            $errors[] = '「'.$name.'」の在庫が足りません。 残り:'.$stock.'個';
                        }
                    }
                }
                // 購入商品毎の更新処理終了
                
            } catch (PDOException $e) {
                throw $e;
            }
            // DB接続終了
            
            // 上記エラーに１つも該当しない場合、
            if (count($errors) === 0) {
                // 正しいページ遷移判断のためにセッション定義
                $_SESSION['permission'] = 'ok';
                // 購入完了ページへ移動
                header('Location: finish.php');
                exit;
            }
            // エラーが無い場合の処理終了
        }
        // ③購入処理終了

    }
    // 入力データがPOSTで送信された場合の処理終了Ⅱ

} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}
// DB接続終了
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ショッピングカート</title>
        <meta charset="UTF-8">
        <style>
            header {
                display: flex;
                height: 70px;
                border-bottom: 1px solid;
                background-color: rgb(16,45,40);
            }
            
            .logo {
                flex: 1;
                text-align: center;
                border-right: 1px solid black;
            }
            
            .welcome {
                flex: 4;
                color: white;
                text-align: center;
            }
            
            .in_cart {
                flex: 1;
                color: white;
                text-align: center;
                position: relative;
            }

            .log_out {
                flex: 1.1;
                text-align: center;
                border-left: 1px solid black;
                position: relative;
            }
            
            .logo img {
                height: 100%;
            }
            
            .welcome p {
                margin: 23px 0;
            }
            
            .welcome a {
                color: white;
            }
            
            .in_cart a {
                display: block;
                height: 100%;
                width: 100%;
                position: absolute;
                top: 0;
                left: 0;
                line-height: 10px;
                color: #f06704;
                font-weight: bold;
                text-decoration: none;
            }
            
            .in_cart img {
                height: 60%;
                padding: 14px 0;
            }
            
            .log_out a {
                display: block;
                height: 100%;
                width:100%;
                position: absolute;
                top: 0;
                left: 0;
                line-height: 70px;
                color: white;
                text-decoration: none;
            }
            
            
            h1 {
                padding: 20px 0;
                text-align: center;
            }
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            .empty {
                text-align: center;
            }
            
            caption {
                text-align: left;
                font-size: 1.2em;
                padding: 10px 0;
            }
            
            table {
                width: 90%;
                margin: 0 auto;
            }
            
            td {
                height: 100px;
                text-align: center;
                border-bottom: 1px solid blue;
                padding: 10px 0;
            }
            
            td img {
                height: 100%;
            }
            
            .amount_change {
                width: 45px;
                text-align: right;
            }
            
            .buy {
                display: flex;
                margin-bottom: 20px;
            }
            
            .total {
                flex: 1;
                text-align: right;
                font-size: 1.2rem;
            }
            
            .total span {
                color: red;
                font-weight: bold;
                font-size: 1.3rem;
            }
            
            .purchase {
                flex: 1;
                text-align: left;
                margin-top: 10px;
                margin-left: 40px;
            }
            
            input#purchase {
            	font-size: 100%;
            	font-weight: bold;
            	width: 140px;
                background-color: orange;
            	padding: 7px 0;
            	margin: 5px 0;
            	border-style:none;
            	border-radius: 5px;
        	}
        </style>
    </head>
    
    <body>
        <header>
            <a class="logo" href="./itemlist.php">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($top_user_name); ?>さん</a>！</p>
            </div>
            <div class="in_cart">
                <a href="./cart.php">
                    <img src="img/structure/cart.png">
                    <?php print $total_amount ?>
                </a>
            </div>
            <div class="log_out">
                <a href="./logout.php">ログアウト</a>
            </div>
        </header>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>ショッピングカート</h1>
        
    <!--$data配列が空だった場合、-->
    <?php if (empty($data)) { ?>
        
        <p class="empty">商品はありません</p>
        
    <!--$data配列が空でなければ、-->
    <?php } else { ?>
        
        <table>
            <tr>
                <th class="item_img"></th>
                <th></th>
                <th>値段</th>
                <th>数量</th>
                <th class="delete_form"></th>
            </tr>

            <!--商品の繰り返し表示開始-->
            <?php foreach ($data as $value) { ?>
            <tr>
                <!--商品画像-->
                <td>
                    <img src="<?php print entity_str($img_dir . $value['item_img']) ?>">
                </td>
                <!--商品名-->
                <td>
                    <?php print entity_str($value['name']); ?>
                </td>
                <!--値段-->
                <td>
                    <?php print number_format(entity_str($value['price'])); ?>円
                </td>
                <!--数量-->
                <form method="POST">
                    <td>
                        <input class="amount_change" type="text" name="amount" value="<?php print entity_str($value['amount']); ?>">個
                        <input type="hidden" name="sql_kind" value="amount_update">
                        <input type="hidden" name="item_id" value="<?php print entity_str($value['item_id']); ?>">
                        <input type="submit" value="変更">                        
                    </td>
                </form>
                <!--小計を計算＆配列に格納（表示はしない）-->
                <?php $sub_total_list[] = $value['price'] * $value['amount']; ?>
                <!--削除-->
                <form method="POST">
                    <td>
                        <input type="hidden" name="sql_kind" value="delete">
                        <input type="hidden" name="item_id" value="<?php print entity_str($value['item_id']); ?>">
                        <input type="submit" value="削除">                        
                    </td>
                </form>
            </tr>
            <?php } ?>
            <!--商品の繰り返し表示終了-->
            
            <!--合計金額-->
            <?php foreach ($sub_total_list as $sub_total) {
                $total += $sub_total; 
            } ?>
            <div class ="buy">
                <p class="total">合計金額: <span>¥ <?php print number_format($total); ?></span></p>
                <div class="purchase">
                    <form method="POST">
                        <input type="hidden" name="sql_kind" value="purchase">
                        <input type="submit" value="購入する" id="purchase">
                    </form>
                </div>
            </div>
            
        </table>
    
    <?php } ?>
    <!--$data配列が空でなかった場合の表示終了、-->
    
    </body>
</html>