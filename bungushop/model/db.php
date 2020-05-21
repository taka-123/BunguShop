<?php
function get_db_connect(){
    try {
        // データベースに接続
        $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        exit('接続できませんでした。理由：'.$e->getMessage() );
    }
    return $dbh;
}

function fetch_query($db, $sql, $params = array()){
    try{
        $statement = $db->prepare($sql);
        $statement->execute($params);
        return $statement->fetch();
    } catch(PDOException $e){
        $errors[] = 'データ取得に失敗しました。';
    }
    return false;
}

function fetch_all_query($db, $sql, $params = array()){
    try{
        $statement = $db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    } catch(PDOException $e){
        $errors[] ='データ取得に失敗しました。';
    }
    return false;
}

function fetchColumn_query($db, $sql, $params = array()){
    try{
        $statement = $db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchColumn();
    } catch(PDOException $e){
        $errors[] ='データ取得に失敗しました。';
    }
    return false;
  }  

function execute_query($db, $sql, $params = array()){
    try{
        $statement = $db->prepare($sql);
        return $statement->execute($params);
    } catch(PDOException $e){
        $errors[] = '更新に失敗しました。';
    }
    return false;
}