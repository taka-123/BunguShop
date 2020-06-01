<header>
    <a class="logo" href="<?php print(HOME_URL) ?>">
        <img src="<?php print(LOGO_PATH .  "logo1.png"); ?>">
    </a>
    <div class="welcome">
        <p>ようこそ、<a href="<?php print(USER_INFO_URL) ?>"><?php print entity_str($login_name); ?>さん</a>！</p>
    </div>
    <div class="in_cart">
        <a href="<?php print(CART_URL) ?>">
        <img src="<?php print(LOGO_PATH .  "cart.png"); ?>">
        <?php print $total_amount; ?>
    </a>
    </div>
    <?php if($login_name === USER_NAME_ADMIN) { ?>
        <div class="nav-item">
            <a href="<?php print(ADMIN_ITEM_URL); ?>">商品管理</a>
        </div>
    <?php } ?>
    <div class="nav-item">
        <a href="<?php print(NEW_ACCOUNT_URL); ?>">サインアップ</a>
    </div>
    <div class="nav-item">
        <a href="<?php print(LOGOUT_URL) ?>">ログアウト</a>
    </div>
</header>