<?php
function itemlist_url($page_id, $sort_key, $genre_id, $name) {
    $url = 'itemlist.php?page_id='. $page_id . '&sort_key=' . $sort_key . '&genre_id=' . $genre_id. '&name=' . $name;
    return $url;
}


// ページネーションの表示
function print_page_link($page_id, $sort_key, $genre_id, $name, $max_page) {
    // ページネーション
    print('
        <nav>
            <ul class="pagination p-0 m-0">
    ');
    // 「最初へ」ボタン
    if($page_id > 1){
        print('
            <li class="page-item">
                <a class="page-link" href=' . itemlist_url(1, $sort_key, $genre_id, $name) . '>
                    top
                </a>
            </li>
        ');
    } else {
        // 現在のページが1ページ目ならリンクを貼らない
        print('
            <li class="page-item">
                <a class="page-link disabled">
                    top	
                </a>
            </li>
        ');
    }
    // 「前へ」ボタン
    if($page_id > 1){
        print('
            <li class="page-item">
                <a class="page-link" href=' . itemlist_url($page_id-1, $sort_key, $genre_id, $name) . '>
                    &lt;
                </a>
            </li>
        ');
    } else {
        // 現在のページが1ページ目ならリンクを貼らない
        print('
            <li class="page-item">
                <a class="page-link disabled">
                    &lt;
                </a>
            </li>
        ');
    }
    // 「ページ番号」の繰り返し表示
    for ($i = 1; $i <= $max_page; $i++) {
        // 表示中のページ番号にはリンクを貼らないよう条件分け
        if ($i === $page_id) {
            print('
                <li class="page-item">
                    <span class="page-link active">'
                        .$i
                    .'</span>
                </li>
            ');
        } else {
            print('
                <li class="page-item">
                    <a class="page-link" href=' . itemlist_url($i, $sort_key, $genre_id, $name) . '>'
                        .$i
                    .'</a>
                </li>
            ');
        }
    }
    // 「次へ」ボタン
    if ($page_id < $max_page) {
        print('
            <li class="page-item">
            <a class="page-link" href=' . itemlist_url($page_id+1, $sort_key, $genre_id, $name) . '>
                    &gt;
                </a>
            </li>
        ');
    } else {
        // 現在のページが最終ページ目ならリンクを貼らない
        print('
            <li class="page-item">
                <a class="page-link disabled">
                    &gt;
                </a>
            </li>
        ');
    }
    // 「最後へ」ボタン
    if ($page_id < $max_page) {
        print('
            <li class="page-item">
            <a class="page-link" href=' . itemlist_url($max_page, $sort_key, $genre_id, $name) . '>
                    last
                </a>
            </li>
        ');
    } else {
        // 現在のページが最終ページ目ならリンクを貼らない
        print('
            <li class="page-item">
                <a class="page-link disabled">
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