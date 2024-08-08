<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="home">
<?php /*
    <div class="main-visual">
        <div class="wrap">
            <div class="triple-slider">
                <div class="swiper-container main-visual__center main-visual__container">
                    <div class="swiper-wrapper main-visual__wrapper">
					<?php
						$query_banner = mysqli_query($connect, "SELECT * FROM MainBanner WHERE m_type = '1'");

						while( $row = mysqli_fetch_array($query_banner) ) {
							$info = $row;
							
							$link = $info['m_link'] ? $info['m_link'] : "javascript:void(0);";
					?>
                        <div class="swiper-slide main-visual__slide">
                            <a href="<?php echo $link; ?>">
                                <figure><img src="<?php echo $info['m_banner_info']?>" alt="" /></figure>
                            </a>
                        </div>
					<?php
						}
					?>

                    </div><!-- .swiper-wrapper -->
                </div><!-- .main-visual__container -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div><!-- .main-visual -->
*/?>
	<div class="main-visual">
        <div class="wrap">
            <div class="main-visual__contents">
                <div class="swiper-container main-visual__txt">
                    <div class="swiper-wrapper main-visual__txt__inner">
					<?php
						$query_banner = mysqli_query($connect, "SELECT * FROM MainBanner WHERE m_type = '1'");

						while( $row = mysqli_fetch_array($query_banner) ) {
							$info = $row;
							
							$link = $info['m_link'] ? $info['m_link'] : "javascript:void(0);";
					?>

						<div class="swiper-slide main-visual__txt__item">
                            <h2 class="main-visual__title"><?php echo $info['m_title']; ?></h2>
                            <h3 class="main-visual__sub-title"><?php echo $info['m_sub_title']; ?></h3>
                            <p class="main-visual__desc"><?php echo $info['m_description']; ?></p>
                            <div class="btn-more"><a href="<?php echo $link; ?>"><span>VIEW MORE</span></a></div>
                        </div><!-- .main-visual__txt__item -->
					<?php
						}
					?>
                        
                    </div><!-- .main-visual__txt__inner -->
                    <?php /*<div class="swiper-control main-visual__control">
                        <div class="swiper-navigation">
                            <button class="swiper-button-prev"><span class="sr-only">PREV</span></button>
                            <button class="swiper-button-next"><span class="sr-only">NEXT</span></button>
                        </div><!-- .swiper-navigation -->
                        <div class="swiper-pagination"></div>
                        <div class="swiper-play-state play">
                            <button class="swiper-play-state__btn swiper-state--play"><span class="sr-only">play</span></button>
                            <button class="swiper-play-state__btn swiper-state--pause"><span class="sr-only">pause</span></button>
                        </div><!-- .swiper-play-state -->
                    </div><!-- .main-visual__control --> */ ?>
                </div><!-- .main-visual__txt -->
                <div class="triple-slider">
                    <div class="swiper-container main-visual__center triple-slider__contents">
                        <div class="swiper-wrapper main-visual__wrapper">
						<?php
							mysqli_data_seek($query_banner, 0);

							while( $row = mysqli_fetch_array($query_banner) ) {
								$info = $row;
						?>
                            <div class="swiper-slide main-visual__slide">
                                <a href="javascript:void(0);">
                                    <figure><img src="<?php echo $info['m_banner_info']?>" alt="" /></figure>
                                </a>
                            </div>
						<?php
							}
						?>

                        </div><!-- .swiper-wrapper -->
                    </div><!-- .main-visual__container -->
                </div>
            </div><!-- .main-visual__contents -->
        </div><!-- .wrap -->
    </div><!-- .main-visual -->

    <div class="main-section main-video">
        <div class="wrap">
            <div class="main-video__inner">
                <div class="main-video__box slideshow-autoplay swiper-container">
                    <div class="swiper-wrapper">
					<?php
						$query_banner_video = mysqli_query($connect, "SELECT * FROM MainBanner WHERE m_type = '2' ORDER BY m_index DESC LIMIT 3");

						while( $row = mysqli_fetch_array($query_banner_video) ) {
							$info = $row;
					?>
                        <div class="swiper-slide embed-video embed-video--youtube">
                            <?php /* data-id="★YouTube video ID" */?>
                            <div class="video-container" data-id="<?php echo $info['m_banner_info']?>"><div class="video-iframe"></div></div>
                        </div>
					<?php
						}
					?>
                    </div><!-- .swiper-wrapper -->
                </div><!-- .main-video__box -->
                <div class="main-video__pagination swiper-container">
                    <div class="swiper-wrapper">
					<?php
						// reset pointer
						 mysqli_data_seek($query_banner_video, 0);

						while( $row = mysqli_fetch_array($query_banner_video) ) {
							$info = $row;
					?>
                        <div class="swiper-slide">
                            <button type="button">
                                <figure class="lazyload">
                                    <?php /* 유튜브 썸네일 이미지 주소 가져오기 : https://img.youtube.com/vi/{★YouTube video ID}/hqdefault.jpg */?>
                                    <img loading="lazy" data-unveil="https://img.youtube.com/vi/<?php echo $info['m_banner_info']; ?>/hqdefault.jpg" src="../assets/images/blank.gif" alt="" />
                                    <noscript><img loading="lazy" src="https://img.youtube.com/vi/<?php echo $info['m_banner_info']; ?>/hqdefault.jpg" alt="" /></noscript>
                                </figure>
                                <p class="main-video__title"><?php echo $info['m_title']?></p>
                                <i class="video-icon"></i>
                            </button>
                        </div>
					<?php
						}
					?>

                    </div><!-- .swiper-wrapper -->
                </div><!-- .main-video__pagination -->
            </div>
        </div><!-- .wrap -->
    </div><!-- .main-video -->

    <div class="main-section main-games">
        <div class="wrap">
            <div class="main-section__head">
                <h2 class="main-section__title">GAMES</h2>
                <div class="btn-more"><a href="/sub/games.php"><span>VIEW MORE</span></a></div>
            </div>
            <div class="main-games__inner swiper-container">
                <div class="main-games__list swiper-wrapper">
				<?php
				$query_games = mysqli_query($connect, "SELECT * FROM Games WHERE m_game_code != '' ORDER BY m_game_sort ASC LIMIT 5");
				$info_games = array();
				

				for ($i = 0; $i < 3 && $row_games = mysqli_fetch_array($query_games); $i++) {
					$info_games = $row_games;
				

				if ($info_games["m_landing_url"] != "") {
					$game_url = str_replace('HOST',$HOST,$info_games["m_landing_url"]);
				} else {
					$game_url = "javascript:void(0);";
				}

				?>
                    <div class="main-games__item swiper-slide">
                        <a href="<?php echo $game_url; ?>">
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="<?php echo $info_games['m_img_url'];?>" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                            </figure>
                            <h3 class="main-games__title"><?php echo $info_games['m_name_'.$lang_code]?></h3>
                        </a>
                    </div>
				<?php
				}
				?>

                </div>
                <div class="main-games-big__list">
				<?php
					for ($i = 0; $i < 2 && $row_games = mysqli_fetch_array($query_games); $i++) {
						$info_games = $row_games;

						if ($info_games["m_landing_url"] != "") {
							$game_url = str_replace('HOST',$HOST,$info_games["m_landing_url"]);
						} else {
							$game_url = "javascript:void(0);";
						}
				?>
                    <div class="main-games-big__item">
                        <a href="<?php echo $game_url; ?>">
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="<?php echo $info_games['m_img_url'];?>" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/746x420/333/fff" alt="" /></noscript>
                            </figure>
                        </a>
                    </div>
				<?php
					}
				?>

                </div>
            </div>
        </div><!-- .wrap -->
    </div><!-- .main-games -->

    <div class="main-section main-nfts">
        <div class="wrap">
            <div class="main-section__head">
                <h2 class="main-section__title">NFTS</h2>
                <div class="btn-more"><a href="/sub/nfts.php"><span>VIEW MORE</span></a></div>
            </div>
            <div class="main-nfts__list swiper-container">
                <div class="swiper-wrapper">
				<?php
					$query_nft = mysqli_query($connect, "SELECT * FROM AccountsNFTData ORDER BY m_index DESC LIMIT 5");

					while ( $row = mysqli_fetch_array($query_nft) ) {
						$info_nft = $row;

						$query_games = mysqli_query($connect, "SELECT m_title FROM Games WHERE m_game_code = '".$info_nft['m_game_code']."'");
						$info_games = mysqli_fetch_array($query_games);

						if ($info_nft['m_token_id'] >= 0 && $info_nft['m_token_id'] <= 5554) {
						    $rarity = "Basic";
						} else if ($info_nft['m_token_id'] >= 5555 && $info_nft['m_token_id'] <= 7776) {
						    $rarity = "Rare";
						} else if ($info_nft['m_token_id'] >= 7777 && $info_nft['m_token_id'] <= 8887) {
						    $rarity = "Epic";
						} else if ($info_nft['m_token_id'] >= 8888 && $info_nft['m_token_id'] <= 9443) {
						    $rarity = "Unique";
						} else if ($info_nft['m_token_id'] >= 9444 && $info_nft['m_token_id'] <= 9554) {
						    $rarity = "Legend";
						}

				?>
                    <div class="main-nfts__item nft-item swiper-slide">
                        <figure class="lazyload">
							<img loading="lazy" src="https://test.nebula3gamefi.com/img_remote/test/game/mm/<?php echo strtolower($rarity); ?>.png" alt="" class="lazyload--loaded" style="z-index:1">
                            <img loading="lazy" data-unveil="https://cdn.aurorahunt.xyz/nft/<?php echo $info_nft['m_game_code']; ?>/img/<?php echo $info_nft['m_token_id']; ?>.png" src="https://cdn.aurorahunt.xyz/nft/?php echo $info_nft['m_game_code']; ?>/img/<?php echo $info_nft['m_token_id']; ?>.png" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/282x282/333/fff" alt="" /></noscript>
                        </figure>
                        <div class="main-nfts__info nft-info">
                            <h3 class="nft-info__title"><?php echo $info_games['m_title']; ?></h3>
                            <ul>
                                <li><span><?php echo $rarity; ?></span><span>#<?php echo $info_nft['m_token_id']; ?></span></li>
                                <li><span>Breeding Count</span><span>(<?php echo $info_nft['m_breeding']; ?>/5)</span></li>
                                <li><span>Current Level</span><span>(<?php echo $info_nft['m_level']; ?>/5)</span></li>
                            </ul>
                        </div>
                    </div><!-- .main-nfts__item --> 
				<?php
					}
				?>

                </div>
            </div><!-- .main-nfts__list -->
        </div><!-- .wrap -->
    </div><!-- .main-nfts -->

    <div class="main-section main-launchpad">
        <div class="wrap">
            <div class="main-section__head">
                <h2 class="main-section__title">LAUNCHPAD</h2>
                <div class="btn-more"><a href="/sub/launchpad.php"><span>VIEW MORE</span></a></div>
            </div>
            <div class="launchpad-list">
			<?php
				$query_launchpad = mysqli_query($connect, "SELECT m_index, m_game_code, m_chain, m_main_banner_img, m_start FROM GamesNFTLaunchpad LIMIT 3");

				while ( $info_launchpad = mysqli_fetch_array($query_launchpad) ) {
					$timestamp = strtotime($info_launchpad['m_start']);
					$info_launchpad['year'] = substr(date("Y", $timestamp), 2, 3);
					$month = date("m", $timestamp);

					switch ($month) {
						case '01':
						case '02':
						case '03':
							$info_launchpad['season'] = 'Q1';
							break;
						case '04':
						case '05':
						case '06':
							$info_launchpad['season'] = 'Q2';
							break;
						case '07':
						case '08':
						case '09':
							$info_launchpad['season'] = 'Q3';
							break;
						case '10':
						case '11':
						case '12':
							$info_launchpad['season'] = 'Q4';
							break;
					}

					$query_games = mysqli_query($connect, "SELECT m_title FROM Games WHERE m_game_code = '".$info_launchpad['m_game_code']."'");
					$info_games = mysqli_fetch_array($query_games);
					
			?>
                <div class="launchpad-item">
                    <!--a href="/sub/launchpad-view.php?idx=<?php echo $info_launchpad['m_index']; ?>"-->
					<?php if ($info_launchpad['m_game_code'] == 'mm') echo '<a href="/sub/launchpad-view.php?idx='.$info_launchpad['m_index'].'">';?>
                        <div class="launchpad-img">
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="<?php echo $info_launchpad['m_main_banner_img']; ?>" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                            </figure>
                             <div class="launchpad-date"><b><?php echo $info_launchpad['year']?></b><span><?php echo $info_launchpad['season']; ?></span></div>
                        </div><!-- .launchpad-img -->
                        <div class="launchpad-info">
                            <p class="goods"><?php if ($info_launchpad['m_chain'] == 'ICP') echo '<i><img src="../assets/images/symbol-icp.svg" alt=""></i>'?><span><?php echo $info_launchpad['m_chain']; ?></span></p>
                            <h3><?php echo $info_games['m_title']; ?> Minting</h3>
                        </div><!-- .launchpad-info -->
                    <?php if ($info_launchpad['m_game_code'] == 'mm') echo '</a>'; ?>
                </div>
			<?php
				}
			?>

            </div><!-- .launchpad-list -->
        </div><!-- .wrap -->
    </div><!-- .main-launchpad -->

    <div class="main-section main-announcement">
        <div class="wrap">
            <div class="main-announcement__inner">
                <div class="main-section__head">
                    <div class="main-section__head__inner">
                        <h2 class="main-section__title">ANNOUNCEMENT</h2>
                        <div class="btn-more"><a href="/sub/announcement.php"><span>VIEW MORE</span></a></div>
                    </div>
                </div>
                <div class="main-announcement__contents">
                    <div class="main-updates main-announcement__section">
                        <h3>Updates</h3>
                        <div class="main-updates__list">
						<?php
							$query_updates = mysqli_query($connect, "SELECT * FROM News WHERE m_type = '2' ORDER BY m_index DESC LIMIT 3");

							while ( $row = mysqli_fetch_array($query_updates)) {
								$info = $row;
								
								$timestamp = strtotime($info['m_date']);
								$month = date("m.Y", $timestamp);
								$day = date("d", $timestamp);
						?>
                            <div class="main-updates__item">
                                <a href="<?php echo $info['m_link']; ?>">
                                    <time datetime="2024-05-03"><span class="month"><?php echo $month; ?></span><span class="day"><i>.</i><?php echo $day; ?></span></time>
                                    <div class="main-updates__content">
                                        <h4><span><?php echo $info['m_title']; ?></span></h4>
                                        <p><?php echo $info['m_description']; ?></p>
                                    </div><!-- .main-updates__content -->
                                </a>
                            </div><!-- .main-updates__item -->
						<?php
							}	
						?>
                        </div><!-- .main-updates__list -->
                    </div><!-- .main-updates -->
                    <div class="main-events main-announcement__section">
                        <h3>Events</h3>
                        <div class="main-events__list">
						<?php
							$query_events = mysqli_query($connect, "SELECT * FROM News WHERE m_type = '3' AND m_status != 4 ORDER BY m_index DESC LIMIT 2");

							while ( $row = mysqli_fetch_array($query_events)) {
								$info = $row;
								
						?>
                            <div class="main-events__item">
                                <a href="<?php echo $info['m_link']; ?>">
                                    <div class="main-events__img">
                                        <figure class="lazyload">
                                            <img loading="lazy" data-unveil="<?php echo $info['m_img_url']; ?>" src="../assets/images/blank.gif" alt="" />
                                            <noscript><img loading="lazy" src="https://dummyimage.com/442x249/333/fff" alt="" /></noscript>
                                        </figure>
                                    </div><!-- .main-events__img -->
                                    <h4 class="main-events__title"><?php echo $info['m_title']; ?></h4>
                                </a>
                            </div>
						<?php
							}
						?>

                        </div>
                    </div><!-- .main-updates -->
                    <div class="main-faq main-announcement__section">
                        <h3>FAQ</h3>
                        <div class="main-faq__list">
						<?php
							$query_faq = mysqli_query($connect, "SELECT * FROM FAQ WHERE m_status != '0' ORDER BY m_index DESC LIMIT 3");

							while ( $row = mysqli_fetch_array($query_faq) ) {
								$info = $row;
						?>
                            <div class="main-faq__item">
                                <h4><span><?php echo $info['m_title']; ?></span></h4>
                                <p><?php echo $info['m_description']; ?></p>
                            </div>
						<?php
							}	
						?>

                        </div>
                    </div><!-- .main-faq -->
                </div><!-- .main-announcement__contents -->
            </div><!-- .main-announcement__inner -->
        </div><!-- .wrap -->
    </div><!-- .main-announcement -->

</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>
