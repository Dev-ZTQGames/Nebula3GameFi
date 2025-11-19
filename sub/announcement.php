<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="page-announcement">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title"><?php echo $lang['ANNOUNCEMENT']; ?></h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="announcement-section">
                <div class="announcement-head">
                    <h2 class="announcement-head__title"><?php echo $lang['Updates']; ?></h2>
                    <div class="btn-more"><a href="https://x.com/Nebula3_GameFi" target="_blank"><span><?php echo $lang['ViewMore']; ?></span></a></div>
                </div>
                <div class="announcement-list announcement-slide swiper-container">
                    <div class="swiper-wrapper">

					<?php
						$query_updates = mysqli_query($connect, "SELECT * FROM News WHERE m_type = '2' ORDER BY m_index DESC LIMIT 6");

						while ( $row = mysqli_fetch_array($query_updates)) {
							$info = $row;
							
							$timestamp = strtotime($info['m_date']);
							$time = date("d F Y", $timestamp);

							$datetime = date("Y-m-d", $timestamp);
					?>
                        <div class="announcement-item swiper-slide">
                            <a href="<?php echo $info['m_link']; ?>" target="_blank">
                                <figure class="lazyload">
                                    <img loading="lazy" data-unveil="<?php echo $info['m_img_url']; ?>" src="../assets/images/blank.gif" alt="" />
                                    <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                                </figure>
                                <div class="announcement-info">
                                    <h3 class="announcement-info__title"><?php echo $info['m_title']; ?></h3>
                                    <div class="announcement-info__desc"><?php echo strip_tags($info['m_description']); ?></div>
                                    <time datetime="<?php echo $datetime; ?>"><span><?php echo $time; ?></span></time>
                                </div>
                            </a>
                        </div><!-- .announcement-item -->
					<?php
						}
					?>

                    </div><!-- .swiper-wrapper -->
                </div><!-- .announcement-list -->
            </div><!-- .announcement-section -->
            <div class="announcement-section">
                <div class="announcement-head">
                    <h2 class="announcement-head__title"><?php echo $lang['Events']; ?></h2>
                    <div class="btn-more"><a href="https://x.com/Nebula3_GameFi" target="_blank"><span><?php echo $lang['ViewMore']; ?></span></a></div>
                </div>
                <div class="announcement-list announcement-slide swiper-container">
                    <div class="swiper-wrapper">

					<?php
						$query_events = mysqli_query($connect, "SELECT * FROM News WHERE m_type = '3' AND m_status != 4 ORDER BY m_index DESC LIMIT 6");

						while ( $row = mysqli_fetch_array($query_events)) {
							$info = $row;

					?>
                        <div class="announcement-item swiper-slide">
                            <a href="<?php echo $info['m_link']?>" target="_blank">
                                <figure class="lazyload">
                                    <img loading="lazy" data-unveil="<?php echo $info['m_img_url']; ?>" src="../assets/images/blank.gif" alt="" />
                                    <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                                </figure>
                                <div class="announcement-info">
                                    <h3 class="announcement-info__title"><?php echo $info['m_title']; ?></h3>
                                </div>
                            </a>
                        </div><!-- .announcement-item -->
					<?php
						}
					?>

                    </div><!-- .swiper-wrapper -->
                </div><!-- .announcement-list -->
            </div><!-- .announcement-section -->
            <div class="announcement-section">
                <div class="announcement-head">
                    <h2 class="announcement-head__title"><?php echo $lang['FAQ']; ?></h2>
                    <div class="btn-more"><a href="https://medium.com/nebula3-gamefi/faq/home" target="_blank"><span><?php echo $lang['ViewMore']; ?></span></a></div>
                </div>
                <div class="faq-list">
                    <div class="faq-item faq-item--creators">
                        <a href="https://medium.com/nebula3-gamefi/faq/home" target="_blank">
                            <h3 class="faq-item__title"><?php echo $lang['Creators']; ?></h3>
                            <p class="faq-item__view btn-line btn-external"><span><?php echo $lang['viewmore']; ?></span></p>
                        </a>
                    </div><!-- .faq-item -->
                    <div class="faq-item faq-item--payments">
                        <a href="https://medium.com/nebula3-gamefi/faq/home" target="_blank">
                            <h3 class="faq-item__title"><?php echo $lang['Payments']; ?></h3>
                            <p class="faq-item__view btn-line btn-external"><span><?php echo $lang['viewmore']; ?></span></p>
                        </a>
                    </div><!-- .faq-item -->
                    <div class="faq-item faq-item--accounts">
                        <a href="https://medium.com/nebula3-gamefi/faq/home" target="_blank">
                            <h3 class="faq-item__title"><?php echo $lang['Accounts']; ?></h3>
                            <p class="faq-item__view btn-line btn-external"><span><?php echo $lang['viewmore']; ?></span></p>
                        </a>
                    </div><!-- .faq-item -->
                    <div class="faq-item faq-item--technical">
                        <a href="https://medium.com/nebula3-gamefi/faq/home" target="_blank">
                            <h3 class="faq-item__title"><?php echo $lang['TechnicalIssues']; ?></h3>
                            <p class="faq-item__view btn-line btn-external"><span><?php echo $lang['viewmore']; ?></span></p>
                        </a>
                    </div><!-- .faq-item -->
                    <div class="faq-item faq-item--chat">
                        <a href="https://t.me/Nebula3_GameFi" target="_blank">
                            <h3 class="faq-item__title"><?php echo $lang['LIVECHAT']; ?></h3>
                            <p class="faq-item__desc"><?php echo $lang['OnNebula3Telegram']; ?></p>
                            <p class="faq-item__view btn-line btn-external"><span><?php echo $lang['viewmore']; ?></span></p>
                        </a>
                    </div><!-- .faq-item -->
                </div><!-- .faq-list -->
            </div><!-- .announcement-section -->
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>