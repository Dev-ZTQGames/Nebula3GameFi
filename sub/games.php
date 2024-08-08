<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="page-games">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title">Games</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="tabs-checkbox">
                <ul class="tabs-checkbox__list">
                    <li><label class="tab-checkbox"><input type="checkbox" class="all" value="all" checked><span>ALL</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_1" value="1"><span>BROWSER</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_1" value="2"><span>PC</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_1" value="3"><span>AOS</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_1" value="4"><span>iOS</span></label></li>
                </ul>
                <ul class="tabs-checkbox__list">
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_2" value="1"><span>ARCADE</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_2" value="2"><span>PUZZLE</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_2" value="3"><span>METAVERSE</span></label></li>
                    <li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_2" value="4"><span>RPG</span></label></li>
					<li><label class="tab-checkbox"><input type="checkbox" class="categoryCheckbox_2" value="5"><span>RTS</span></label></li>
                </ul>
            </div>
            <div class="game-list">
			<?php
				$query = mysqli_query($connect, "SELECT * FROM Games WHERE m_game_code != '' ORDER BY m_game_sort ASC");
				
				while ($row = mysqli_fetch_array($query)) {
					$info_games = $row;

					if ($info_games["m_landing_url"] != "") {
						$game_url = str_replace('HOST',$HOST,$info_games["m_landing_url"]);
					} else {
						$game_url = "javascript:void(0);";
					}

					$query_landing = mysqli_query($connect, "SELECT m_platform_icon_i, m_platform_icon_o, m_platform_icon_pc, m_platform_icon_web FROM GamesLanding WHERE m_game_code = '".$info_games['m_game_code']."'");
					$info_langing = mysqli_fetch_array($query_landing);

					$icon_i = $icon_o = $icon_pc = $icon_web = "";
					if($info_langing['m_platform_icon_i'] !== 'Y'){
						$icon_i = "coming-soon";
					}
					if($info_langing['m_platform_icon_o'] !== 'Y'){
						$icon_o = "coming-soon";
					}
					if($info_langing['m_platform_icon_pc'] !== 'Y'){
						$icon_pc = "coming-soon";
					}
					if($info_langing['m_platform_icon_web'] !== 'Y'){
						$icon_web = "coming-soon";
					}

					switch($info_games['m_category2']) {
						case '1': $category2 = "ARCADE";
						break;
						case '2': $category2 = "PUZZLE";
						break;
						case '3': $category2 = "METAVERSE";
						break;
						case '4': $category2 = "RPG";
						break;
						case '5': $category2 = "RTS";
						break;
					}
			?>
                <div class="game-item" data-category1="<?php echo $info_games['m_category1'];?>" data-category2="<?php echo $info_games['m_category2'];?>">
                    <a href="<?php echo $game_url; ?>">
                        <div class="game-img">
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="<?php echo $info_games['m_img_url'];?>" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                            </figure>
                        </div><!-- .game-img -->
                        <div class="game-info">
                            <p class="game-genre"><span><?php echo $category2; ?></span></p>
                            <h3 class="game-title"><?php echo $info_games['m_name_'.$lang_code]?></h3>
                            <ul class="device-list">
                                <li class="device-browser <?php echo $icon_web; ?>"><p><span>Browser</span><?php if($icon_web) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-pc <?php echo $icon_pc; ?>"><p><span>PC</span><?php if($icon_pc) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-android <?php echo $icon_o; ?>"><p><span>Android</span><?php if($icon_o) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-ios <?php echo $icon_i; ?>"><p><span>iOS</span><?php if($icon_i) echo "<span>Coming Soon</span>"; ?></p></li>
                            </ul>
                        </div><!-- .game-info -->
                    </a>
                </div><!-- .games-item -->
			<?php
				}
			?>
                <!--div class="game-item">
                    <a href="games-view.php">
                        <div class="game-img">
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="https://dummyimage.com/488x275/333/fff" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
                            </figure>
                        </div><!-- .game-img -->
                        <!--div class="game-info">
                            <p class="game-genre"><span>Arcade</span></p>
                            <h3 class="game-title">Mining Maze: Get a big treasure box!</h3>
                            <ul class="device-list">
                                <li class="device-browser"><p><span>Browser</span></p></li>
                                <li class="device-pc"><p><span>PC</span></p></li>
                                <li class="device-android coming-soon"><p><span>Android</span><span>coming soon</span></p></li>
                                <li class="device-ios coming-soon"><p><span>ios</span><span>coming soon</span></p></li>
                            </ul>
                        </div><!-- .game-info -->
                    <!--/a>
                </div><!-- .games-item -->

            </div><!-- .games-list -->
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
$(document).ready(function() {

    $('.categoryCheckbox_1, .categoryCheckbox_2').change(function(){
        var selectedCategories = $('.categoryCheckbox_1:checked').map(function() {
            return $(this).val();
        }).get();
		
        var selectedCategories2 = $('.categoryCheckbox_2:checked').map(function() {
            return $(this).val();
        }).get();
		
		if (selectedCategories.length !== 0 || selectedCategories2.length !== 0) {
			$('.all').prop('checked', false);
		} else {
			$('.all').prop('checked', true);
		}

        $('.game-item').each(function() {
            var categories = $(this).data('category1').toString().split(',');  
            var categories2 = $(this).data('category2').toString().split(',');

            var showItem = (selectedCategories.length === 0 || selectedCategories.some(category => categories.includes(category))) &&
                           (selectedCategories2.length === 0 || selectedCategories2.some(category2 => categories2.includes(category2)));
			
            if (showItem) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

	$('.all').on('click', function() {
        if ($(this).is(':checked')) {
            $('.game-item').each(function() {
                $(this).show();
				$('.categoryCheckbox_1').prop('checked', false);
				$('.categoryCheckbox_2').prop('checked', false);
			});
        } else {
           $('.all').prop('checked', true);
        }
    });

});
</script>