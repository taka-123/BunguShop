<?php
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_items($db, $is_open = false, $name = '', $genre_id = 0) {
    $sql = "
        SELECT
            bungu_item_master.item_id,
            name,
            bungu_item_master.genre_id,
            price,
            item_img,
            comment,
            status,
            stock,
            genre_name
        FROM
            bungu_item_master
        JOIN
            bungu_item_stock
        ON
            bungu_item_master.item_id = bungu_item_stock.item_id
        JOIN
            bungu_item_genre
        ON
            bungu_item_master.genre_id = bungu_item_genre.genre_id
        WHERE
            1 = 1
    ";
    if ($is_open === true) {
        $sql .= "
            AND status = 1
        ";
    }
    if ($name !== '') {
        $sql .= "
            AND name LIKE ?
        ";
        $params[] = "%" . $name . "%";
    }
    if ($genre_id !== 0) {
        $sql .= "
            AND bungu_item_master.genre_id = ?
        ";
        $params[] = $genre_id;
    }

    return fetch_all_query($db, $sql, $params);
}

function get_open_items($db, $name = '', $genre_id = 0) {
    return get_items($db, true, $name, $genre_id);
}

function get_genres($db) {
    $sql = "
        SELECT
            genre_id, genre_name
        FROM
            bungu_item_genre
    ";
    return fetch_all_query($db, $sql);
}

// ジャンルIDの配列を作成
function get_genre_ids($db) {
    $genres = get_genres($db);
    foreach ($genres as $genre) {
        $genre_ids[] = $genre['genre_id'];
    }
    return $genre_ids;
}

function get_genre_name($db, $genre_id) {
    $sql = "
        SELECT
            genre_name
        FROM
            bungu_item_genre
        WHERE
            genre_id = ?
    ";
    $params = array($genre_id);
    return fetchColumn_query($db, $sql, $params);
}

function regist_item($db, $name, $genre_id, $price, $item_img, $comment, $status, $stock) {
    $db->beginTransaction();
    try {
        $sql = "
            INSERT INTO bungu_item_master(
                name,
                genre_id,
                price,
                item_img,
                comment,
                status,
                create_datetime,
                update_datetime
            )
            VALUES(?, ?, ?, ?, ?, ?, now(), now());
        ";
        $params = array($name, $genre_id, $price, $item_img, $comment, $status);
        $statement = $db->prepare($sql);
        $statement->execute($params);
        
        $item_id = $db->lastInsertId();
        
        $sql = "
            INSERT INTO bungu_item_stock(
                item_id,
                stock,
                create_datetime,
                update_datetime
            )
            VALUES(?, ?, now(), now());
        ";
        $params = array($item_id, $stock);
        $statement = $db->prepare($sql);
        $statement->execute($params);
        
        $db->commit();
        return true;
        
    } catch (PDOException $e) {
        $db->rollback();
        return false;
    }
}

function update_item_stock($db, $item_id, $stock) {
    $sql = "
        UPDATE
            bungu_item_stock
        SET
            stock = ?,
            update_datetime = now()
        WHERE
            item_id = ?
        LIMIT 1
    ";
    $params = array($stock, $item_id);
    return execute_query($db, $sql, $params);
}

function update_item_status($db, $item_id, $status) {
    $sql = "
        UPDATE
            bungu_item_master
        SET
            status = ?,
            update_datetime = now()
        WHERE
            item_id = ?
        LIMIT 1
    ";
    $params = array($status, $item_id);
    return execute_query($db, $sql, $params);
}

function delete_item($db, $item_id) {
    $db->beginTransaction();
    try {
        $sql = "
            DELETE FROM
                bungu_item_master
            WHERE
                item_id = ?
            LIMIT 1
        ";
        $params = array($item_id);
        $statement = $db->prepare($sql);
        $statement->execute($params);
        
        $sql = "
            DELETE FROM
                bungu_item_stock
            WHERE
                item_id = ?
            LIMIT 1
        ";
        $params = array($item_id);
        $statement = $db->prepare($sql);
        $statement->execute($params);
        
        $db->commit();
        return true;
        
    } catch (PDOException $e) {
        $db->rollback();
        return false;
    }
}

