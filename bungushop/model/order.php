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

function get_all_orders($db) {
    return get_user_orders($db, false);
}

function get_user_orders($db, $user_id = false) {
    $params = [];
    $sql = "
        SELECT
            bungu_orders.order_id,
            bungu_orders.user_id,
            bungu_orders.create_datetime,
            sum(bungu_order_details.purchase_price * bungu_order_details.quantity) AS total_price
        FROM
            bungu_orders
        JOIN
            bungu_order_details
        ON
            bungu_orders.order_id = bungu_order_details.order_id        
    ";
    if ($user_id !== false) {
        $sql .= "WHERE bungu_orders.user_id = ?";
        $params[] = $user_id;
    }
    $sql .= "
        GROUP BY
            bungu_orders.order_id
        ORDER BY
            bungu_orders.create_datetime DESC
    ";
    return fetch_all_query($db, $sql, $params);
}

function get_all_order_ids($db) {
    return get_user_order_ids($db, false);
}

function get_user_order_ids($db, $user_id = false) {
    $orders = get_user_orders($db, $user_id);
    foreach ($orders as $order) {
        $order_ids[] = $order['order_id'];
    }
    return $order_ids;
}

function get_order($db, $order_id) {
    $sql = "
        SELECT
            bungu_orders.order_id,
            bungu_orders.create_datetime,
            sum(bungu_order_details.purchase_price * bungu_order_details.quantity) AS total_price
        FROM
            bungu_orders
        JOIN
            bungu_order_details
        ON
            bungu_orders.order_id = bungu_order_details.order_id
            WHERE bungu_orders.order_id = ?
        GROUP BY
            bungu_orders.order_id
    ";
    $params = array($order_id);
    return fetch_query($db, $sql, $params);
}

function get_order_details($db, $order_id){
    $sql = "
        SELECT
            bungu_item_master.name,
            bungu_item_master.item_img,
            bungu_order_details.purchase_price,
            bungu_order_details.quantity,
            bungu_order_details.purchase_price * bungu_order_details.quantity AS sub_total
        FROM
            bungu_order_details
        JOIN
            bungu_item_master
        ON
            bungu_order_details.item_id = bungu_item_master.item_id
        WHERE
            bungu_order_details.order_id = ?
    ";
    $params = array($order_id);
    return fetch_all_query($db, $sql, $params);
}