<?php 
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'db.php';

function regist_order($db, $carts, $user_id) {
    $db->beginTransaction();
    try {
        foreach ($carts as $cart) {
            // ①購入後の在庫数の削減
            update_item_stock($db, $cart['item_id'], $cart['stock'] - $cart['amount']);

            // ②カートデータ削除
            delete_cart($db, $cart['cart_id']);
        }


        // ③購入履歴登録
        $sql = "
            INSERT INTO bungu_orders(
                user_id,
                create_datetime
            )
            VALUES(?, now());
        ";
        $params = array($user_id);
        $statement = $db->prepare($sql);
        $statement->execute($params);
        
        // 明細と紐付けのため、order_id取得
        $order_id = $db->lastInsertId();
        
        // ④購入明細登録
        foreach ($carts as $cart) {
            $sql = "
                INSERT INTO bungu_order_details(
                    order_id,
                    item_id,
                    purchase_price,
                    quantity,
                    create_datetime
                )
                VALUES(?, ?, ?, ?, now());
            ";
            $params = array($order_id, $cart['item_id'], $cart['price'], $cart['amount']);
            $statement = $db->prepare($sql);
            $statement->execute($params);
        }

        $db->commit();
        return true;
        
    } catch (PDOException $e) {
        $db->rollback();
        return false;
    }
}