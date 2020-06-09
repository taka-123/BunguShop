<?php

// ページネーションの表示
function print_page_link($now_page, $sort_key, $max_page) {
    // ページネーション
    print('
        <nav>
            <ul class="pagination">
    ');
    // 「最初へ」ボタン
    if($now_page > 1){
        print('
            <li class="page-item">
                <a class="page-link" href="itemlist.php?page_id=1&sort_key='.$sort_key.'">
                    top
                </a>
            </li>
        ');
    } else {
        // 現在のページが1ページ目ならリンクを貼らない
        print('
            <li class="page-item disabled">
                <a class="page-link">
                    top	
                </a>
            </li>
        ');
    }
    // 「前へ」ボタン
    if($now_page > 1){
        print('
            <li class="page-item">
                <a class="page-link" href="itemlist.php?page_id='.($now_page - 1).'&sort_key='.$sort_key.'">
                    &lt;
                </a>
            </li>
        ');
    } else {
        // 現在のページが1ページ目ならリンクを貼らない
        print('
            <li class="page-item disabled">
                <a class="page-link">
                    &lt;
                </a>
            </li>
        ');
    }
    // 「ページ番号」の繰り返し表示
    for ($i = 1; $i <= $max_page; $i++) {
        // 表示中のページ番号にはリンクを貼らないよう条件分け
        if ($i === $now_page) {
            print('
                <li class="page-item active">
                    <span class="page-link">'
                        .$i
                    .'</span>
                </li>
            ');
        } else {
            print('
                <li class="page-item">
                    <a class="page-link" href="itemlist.php?page_id='.$i.'&sort_key='.$sort_key.'">'
                        .$i
                    .'</a>
                </li>
            ');
        }
    }
    // 「次へ」ボタン
    if ($now_page < $max_page) {
        print('
            <li class="page-item">
                <a class="page-link" href="itemlist.php?page_id='.($now_page + 1).'&sort_key='.$sort_key.'">
                    &gt;
                </a>
            </li>
        ');
    } else {
        // 現在のページが最終ページ目ならリンクを貼らない
        print('
            <li class="page-item disabled">
                <a class="page-link">
                    &gt;
                </a>
            </li>
        ');
    }
    // 「最後へ」ボタン
    if ($now_page < $max_page) {
        print('
            <li class="page-item">
                <a class="page-link" href="itemlist.php?page_id='.$max_page.'&sort_key='.$sort_key.'">
                    last
                </a>
            </li>
        ');
    } else {
        // 現在のページが最終ページ目ならリンクを貼らない
        print('
            <li class="page-item disabled">
                <a class="page-link">
                    last
                </a>
            </li>
        ');
    }
    print('
        </ul>
            </nav>
    ');
}