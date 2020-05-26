<?php
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
    $sql = "
        SELECT
            bungu_carts.cart_id,
            bungu_carts.user_id,
            bungu_carts.item_id,
            bungu_item_master.name,
            bungu_item_master.genre_id,
            bungu_item_genre.genre_name,
            bungu_item_master.price,
            bungu_item_master.item_img,
            bungu_item_master.comment,
            bungu_item_master.status,
            bungu_item_stock.stock,
            bungu_carts.amount
        FROM
            bungu_carts
        JOIN
            bungu_item_master
        ON
            bungu_carts.item_id = bungu_item_master.item_id
        JOIN
            bungu_item_genre
        ON
            bungu_item_master.genre_id = bungu_item_genre.genre_id
        JOIN
            bungu_item_stock
        ON
            bungu_item_master.item_id = bungu_item_stock.item_id       
        WHERE
            bungu_carts.user_id = ?
    ";
    $params = array($user_id);
    return fetch_all_query($db, $sql, $params);
}

function get_total_amount($db, $user_id) {
    $sql = "
        SELECT 
            sum(amount) 
        FROM 
            bungu_carts 
        WHERE 
            user_id = ?
        GROUP BY 
            user_id
    ";
    $params = array($user_id);
    return fetchColumn_query($db, $sql, $params);
}

function select_cart_amount($db, $user_id, $item_id) {
    $sql = "
        SELECT
            amount
        FROM
            bungu_carts
        WHERE
            user_id = ?
        AND 
            item_id = ?
    ";
    $params = array($user_id, $item_id);
    return fetchColumn_query($db, $sql, $params);
}

function get_cart_amount($db, $user_id, $item_id) {
    if (select_cart_amount($db, $user_id, $item_id) === false) {
        $amount = 0;
    } else {
        $amount = select_cart_amount($db, $user_id, $item_id);
    }
    return $amount;
}

function update_cart_amount($db, $amount, $user_id, $item_id) {
    $sql = "
        UPDATE
            bungu_carts
        SET
            amount = ?,
            update_datetime = now()
        WHERE
            user_id = ?
        AND 
            item_id = ?
    ";
    $params = array($amount, $user_id, $item_id);
    return execute_query($db, $sql, $params);
}

function insert_cart($db, $user_id, $item_id, $amount) {
    $sql = "
        INSERT INTO
            bungu_carts (
                user_id,
                item_id,
                amount,
                create_datetime,
                update_datetime
            ) 
        VALUE (?, ?, ?, now(), now())
    ";
    $params = array($user_id, $item_id, $amount);
    return execute_query($db, $sql, $params);
}

function delete_cart($db, $cart_id) {
    $sql = "
        DELETE FROM 
            bungu_carts 
        WHERE 
            cart_id = ?
    ";
    $params = array($cart_id);
    return execute_query($db, $sql, $params);
}