<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="page-login-intro">
    <div class="article-header">
        <div class="article-header__inner login-wrap">
            <h2 class="article-title"><?php echo $lang['Signup']; ?></h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="login-wrap">
            <ul class="login-list">
                <li><a href="./signup.php" class="login-item login-item--nebula"><i></i><span>Nebula3 Account</span></a></li>
                <li><a href="/extern/sso/login_google.php" class="login-item login-item--google"><i></i><span>Google Account</span></a></li>
                <li><a href="/extern/sso/login_apple.php" class="login-item login-item--apple"><i></i><span>Apple Account</span></a></li>
            </ul>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>