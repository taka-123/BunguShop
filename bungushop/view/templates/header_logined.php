<header>
    <div class="logo">
        <a href="<?php print(HOME_URL) ?>">
            <img src="<?php print(STRUCTURE_PATH .  "logo1.png"); ?>">
        </a>
    </div>
    <div class="welcome">
        ようこそ！
        <br>
        <a href="<?php print(USER_INFO_URL) ?>"><?php print entity_str(get_login_name()); ?>さん（マイページ）</a>
    </div>
    <!-- ゲスト用(未ログイン) -->
    <?php if (is_guest()) { ?>
        <div class="nav-item">
            <a href="<?php print(NEW_ACCOUNT_URL); ?>">サインアップ</a>
        </div>
        <div class="nav-item">
            <a href="<?php print(LOGIN_URL) ?>">ログイン</a>
        </div>
    <!-- ログイン済用 -->
    <?php } else { ?>
        <div class="in_cart">
            <a href="<?php print(CART_URL) ?>">
            <img src="<?php print(STRUCTURE_PATH .  "cart.png"); ?>">
            <?php print $total_amount; ?>
            </a>
        </div>
        <!-- ※管理者特別（「商品管理」） -->
        <?php if (is_admin()) { ?>
            <div class="nav-item">
                <a href="<?php print(ADMIN_ITEM_URL); ?>">商品管理</a>
            </div>
        <?php } ?>
        <div class="nav-item">
            <a href="<?php print(HISTORY_URL); ?>">購入履歴</a>
        </div>
        <div class="nav-item">
            <a href="<?php print(LOGOUT_URL) ?>">ログアウト</a>
        </div>
    <?php } ?>
</header>