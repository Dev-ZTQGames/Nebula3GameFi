<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; 

$game_code = escape_string(trim($_REQUEST['game_code']));

$info = array();

$query = mysqli_query($connect, "SELECT * FROM GamesLanding WHERE m_game_code = '" .$game_code. "'");
$row = mysqli_fetch_array($query);
$info = $row;

$query_games = mysqli_query($connect, "SELECT m_category2, m_chain, m_badge FROM Games WHERE m_game_code = '" .$game_code. "'");
$row_games = mysqli_fetch_array($query_games);

switch($row_games['m_category2']) {
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

$info['m_chain'] = $row_games['m_chain'];

$sub_banner = explode(",",$info['m_sub_banner']);

$icon_i = $icon_o = $icon_pc = $icon_web = "";
if($info['m_platform_icon_i'] !== 'Y'){
	$icon_i = "coming-soon";
}
if($info['m_platform_icon_o'] !== 'Y'){
	$icon_o = "coming-soon";
}
if($info['m_platform_icon_pc'] !== 'Y'){
	$icon_pc = "coming-soon";
}
if($info['m_platform_icon_web'] !== 'Y'){
	$icon_web = "coming-soon";
}


$que = mysqli_query($connect, "SELECT m_token_id, m_level, m_breeding FROM AccountsNFTData"); 
$list = array();
while( $row = mysqli_fetch_array($que) ) {
	$list[] = $row;
}

$info_nft = json_encode($list);
?>

<?php
if ($game_code == 'mm') {
?>
<script>
let once = true;

let totalSupplyNormalCustom;
let totalSupplyRareCustom;
let totalSupplyEpicCustom;
let totalSupplyUniqueCustom;
let totalSupplyLegendaryCustom;

window.addEventListener('message', function(event) {
	if (allowedDomains.includes(event.origin)) {
		if ( /*(event.data.LoggedIn === true || event.data.LoggedIn === false) &&*/ once === true ) {
			once = false;
			var iframe = document.getElementById('Internet_Identity');
			iframe.contentWindow.postMessage('totalSupply', '*');
		}
		if (event.data.NFT_totalSupply === true) {
			const data = <?php echo $info_nft; ?>;

			totalSupplyNormalCustom		= Number(event.data.totalSupplyNormalCustom);
			totalSupplyRareCustom		= Number(event.data.totalSupplyRareCustom) + 5556;
			totalSupplyEpicCustom		= Number(event.data.totalSupplyEpicCustom) + 7778;
			totalSupplyUniqueCustom		= Number(event.data.totalSupplyUniqueCustom) + 8889;
			totalSupplyLegendaryCustom	= Number(event.data.totalSupplyLegendaryCustom) + 9445;

			const game_code = "<?php echo $game_code; ?>";
			
			var totalSupplyMap = {
			    Basic: totalSupplyNormalCustom,
			    Rare: totalSupplyRareCustom,
			    Epic: totalSupplyEpicCustom,
			    Unique: totalSupplyUniqueCustom,
			    Legend: totalSupplyLegendaryCustom
			};

			for (var rarity in totalSupplyMap) {
			    if (totalSupplyMap.hasOwnProperty(rarity)) {
			        for (var id = getIdStart(rarity); id <= totalSupplyMap[rarity]; id++) {
			            var level = 0;
			            var breeding = 5;
			            var selectedToken = data.find(item => item.m_token_id === id.toString());
			            if (selectedToken) {
			                level = selectedToken.m_level;
			                breeding = selectedToken.m_breeding;
			            }
			            var newBlock = '<div class="nft-item" data-category="' + game_code + '" data-rarity="' + rarity + '">' +
										    '<figure class="lazyload">' +
												'<img loading="lazy" src="https://<?php echo $HOST; ?>.nebula3gamefi.com/img_remote/test/game/mm/' + rarity.toLowerCase() +'.png" alt="" class="lazyload--loaded" style="z-index:1">' +
										        '<img loading="lazy" src="https://cdn.aurorahunt.xyz/nft/mm/img/' + id + '.png" alt="" />' +
										        '<noscript><img loading="lazy" src="https://dummyimage.com/282x282/333/fff" alt="" /></noscript>' +
										    '</figure>' +
										    '<div class="main-nfts__info nft-info">' +
										        '<h3 class="nft-info__title">Mining Maze</h3>' +
										        '<ul>' +
										            '<li><span>' + rarity + '</span><span>#' + id + '</span></li>' +
										            '<li><span>Breeding Count</span><span>(' + breeding + '/5)</span></li>' +
										            '<li><span>Current Level</span><span>(' + level + '/5)</span></li>'+
										        '</ul>' +
										    '</div>' +
										'</div><!-- .nft-item -->'
			            $('.sub-nft-list').append(newBlock);

			        }
			    }
			}

			function getIdStart(rarity) {
			    switch (rarity) {
			        case 'Basic':
			            return 1;
			        case 'Rare':
			            return 5556;
			        case 'Epic':
			            return 7778;
			        case 'Unique':
			            return 8889;
			        case 'Legend':
			            return 9445;
			        default:
			            return 1;
			    }
			}


		}

	}
});
</script>
<?php
}
?>

<div id="container" class="page-games-view">
    <div class="article-header" style="background-image:url('<?php echo $info['m_main_banner_img']; ?>')"><?php /* game background image https://dummyimage.com/1903x500/333/fff */ ?>
        <div class="article-header__inner wrap">
            <p class="game-genre"><span><?php echo $category2; ?></span></p>
            <h2 class="article-title"><?php echo $info['m_title']; ?></h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="game-view__main">
            <div class="wrap">
                <div class="game-view__main-inner">
                    <div class="game-view__picture">
                        <div class="game-view__slider swiper-container">
                            <div class="swiper-wrapper">
							<?php
								if ($info['m_main_banner_video'] !== "") {
							?>
                                <div class="swiper-slide embed-video embed-video--youtube">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $info['m_main_banner_video']?>?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <!--div class="swiper-slide">
                                    <figure class="lazyload">
                                        <img loading="lazy" data-unveil="https://dummyimage.com/1000x563/333/fff" src="../assets/images/blank.gif" alt="" />
                                        <noscript><img loading="lazy" src="https://dummyimage.com/1000x563/333/fff" alt="" /></noscript>
                                    </figure>
                                </div-->
									<?php
								}
                                        foreach($sub_banner as $value) {
                                            if($value){
                                            echo '  <div class="swiper-slide">
													    <figure class="lazyload">
													        <img loading="lazy" data-unveil="'.$value.'" src="../assets/images/blank.gif" alt="" />
													        <noscript><img loading="lazy" src="https://dummyimage.com/1000x563/333/fff" alt="" /></noscript>
													    </figure>
													</div>';
                                            }
                                        }
                                    ?>

                            </div><!-- .swiper-wrapper -->
                        </div><!-- .game-view__slider -->
                        <div class="game-view__control-wrap">
                            <div class="game-view__control swiper-container">
                                <div class="swiper-wrapper">
								<?php
									if ($info['m_main_banner_video'] !== "") {
								?>
                                    <div class="swiper-slide">
                                        <button type="button">
                                            <figure class="lazyload">
                                                <?php /* 유튜브 썸네일 이미지 주소 가져오기 : https://img.youtube.com/vi/{★YouTube video ID}/hqdefault.jpg */?>
                                                <img loading="lazy" data-unveil="https://img.youtube.com/vi/<?php echo $info['m_main_banner_video']?>/hqdefault.jpg" src="../assets/images/blank.gif" alt="" />
                                                <noscript><img loading="lazy" src="https://img.youtube.com/vi/7ijwiqxvKVc/hqdefault.jpg" alt="" /></noscript>
                                            </figure>
                                            <i class="video-icon"></i>
                                        </button>
                                    </div>
									<?php
									}
                                        foreach($sub_banner as $value) {
                                            if($value){
                                            echo '<div class="swiper-slide"><!--190x107-->
												     <button type="button">
												         <figure class="lazyload">
												             <img loading="lazy" data-unveil="'.$value.'" src="../assets/images/blank.gif" alt="" />
												             <noscript><img loading="lazy" src="https://dummyimage.com/190x107/333/fff" alt="" /></noscript>
												         </figure>
												     </button>
												 </div>';
                                            }
                                        }
                                    ?>
                                    <!--div class="swiper-slide">190x107
                                        <button type="button">
                                            <figure class="lazyload">
                                                <img loading="lazy" data-unveil="https://dummyimage.com/190x107/333/fff" src="../assets/images/blank.gif" alt="" />
                                                <noscript><img loading="lazy" src="https://dummyimage.com/190x107/333/fff" alt="" /></noscript>
                                            </figure>
                                        </button>
                                    </div-->
                                </div><!-- .swiper-wrapper -->
                            </div><!-- .game-view__control -->
                            <div class="swiper-navigation">
                                <button class="swiper-button-prev"><span class="sr-only">PREV</span></button>
                                <button class="swiper-button-next"><span class="sr-only">NEXT</span></button>
                            </div><!-- .swiper_navigation -->
                        </div><!-- .game-view__control-wrap -->
                    </div><!-- .game-view__picture -->
                    <div class="game-view__info">
                        <div class="game-view__info-logo"><!--260x70-->
                            <figure class="lazyload">
                                <img loading="lazy" data-unveil="<?php echo $info['m_main_banner_title']; ?>" src="../assets/images/blank.gif" alt="" />
                                <noscript><img loading="lazy" src="https://dummyimage.com/260x70/transparent/fff" alt="" /></noscript>
                            </figure>
                        </div>
                        <div class="game-view__info-detail">
                            <ul>
                                <li><p>Developer</p><b><?php echo $info['g_title']; ?></b></li>
                                <li><p>Genre</p><b><?php echo $category2; ?></b></li>
                                <li><p>Chain</p><b class="goods"><i><img src="../assets/images/symbol-icp.svg" alt=""></i><span><?php echo $info['m_chain']; ?></span></b></li>
                            </ul>
                            <div class="game-view__info-desc"><?php echo $info['m_content']; ?></div>
                        </div>
                        <div class="game-view__info-community">
                            <p>Community</p>
                            <ul class="sns-list">
							<?php
								if ($info['m_web_url'] != "") {
							?>
                            <li class="sns-web"><a href="<?php echo $info['m_web_url']; ?>" target="_blank"><span class="sr-only">web site</span></a></li>
							<?php
								}
								if ($info['m_dc_url'] != "") {
							?>
                            <li class="sns-discord"><a href="<?php echo $info['m_dc_url'];?>" target="_blank"><span class="sr-only">discord</span></a></li>
							<?php
								}
								if ($info['m_x_url'] != "") {
							?>
                            <li class="sns-twitter"><a href="<?php echo $info['m_x_url'];?>" target="_blank"><span class="sr-only">twitter</span></a></li>
							<?php
								}
								if ($info['m_tg_url'] != "") {
							?>
                            <li class="sns-telegram"><a href="<?php echo $info['m_tg_url'];?>" target="_blank"><span class="sr-only">telegram</span></a></li>
							<?php
								}
							?>
                            </ul>
                        </div>
                        <div class="game-view__info-device">
                            <ul class="device-list">
                                <li class="device-browser <?php echo $icon_web; ?>"><p><span>Browser</span><?php if($icon_web) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-pc <?php echo $icon_pc; ?>"><p><span>PC</span><?php if($icon_pc) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-android <?php echo $icon_o; ?>"><p><span>Android</span><?php if($icon_o) echo "<span>Coming Soon</span>"; ?></p></li>
                                <li class="device-ios <?php echo $icon_i; ?>"><p><span>ios</span><?php if($icon_i) echo "<span>Coming Soon</span>"; ?></p></li>
                            </ul>
                        </div>
						<?php
						$ip = get_client_ip();
						$ip_info = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
						$region = strtolower($ip_info['geoplugin_countryCode']);
						if ($region == "kr") {
						?>
						<div>
							<a href="javascript:void(0);" class="btn-basic btn-primary btn-play disabled"><span>Game Play</span></a>
							<p style="text-align: center;color: gray;">Sorry, this game is not available in your region.</p>
						</div>
						<?php
						} else if ( $_SESSION['sess_login_id'] != "" && $row_games['m_badge'] !== '2' ) {
							if ($game_code == 'mm') {
								$query_nft = mysqli_query($connect, "SELECT * FROM AccountsPSCTNodeWallet WHERE m_login_id = '" . $_SESSION['sess_login_id'] . "' AND m_game_code = 'mm'");
								$info_nft = mysqli_fetch_array($query_nft);

								if ($info_nft['m_NFTs'] == "") {
						?>
								<a href="javascript:void(0);" onclick="swal({text: 'To play the game, you need a Mining Maze NFT in your wallet.',	buttons: 'Confirm',	});" class="btn-basic btn-primary btn-play"><span>Game Play</span></a>
						<?php
								} else {
						?>
								<a href="javascript:void(0);" onclick="window.open('<?php echo str_replace('HOST',$HOST,$info['m_game_url']); ?>','','menubar=1');" class="btn-basic btn-primary btn-play"><span>Game Play</span></a>
						<?php
								}
							} else {
						?>
							<a href="javascript:void(0);" onclick="window.open('<?php echo str_replace('HOST',$HOST,$info['m_game_url']); ?>','','menubar=1');" class="btn-basic btn-primary btn-play"><span>Game Play</span></a>
						<?php
							}
						} else if ($row_games['m_badge'] == '2') {
						?>
							<a href="javascript:void(0);" class="btn-basic btn-primary btn-play"><span>Coming Soon</span></a>
						<?php
						} else {
						?>
							<a href="javascript:void(0);" onclick="needLogin()" class="btn-basic btn-primary btn-play"><span>Game Play</span></a>
						<?php
						}	
						?>
					</div><!-- .game-view__info -->
                </div><!-- .game-view__main-inner -->
            </div><!-- .wrap -->
        </div><!-- .game-view__main -->
		<?php
			if ($game_code == "mm") {
		?>
        <div class="game-view__announcement">
            <div class="wrap">
                <div class="game-view__announcement__inner">
                    <div class="game-view-section__head">
                        <h2 class="game-view-section__head__title">ANNOUNCEMENT</h2>
						<?php
							$show = true;
							if ($game_code == 'mm') {
								$link_view_more = 'https://medium.com/test-4formonth/game1/home';
							} else if ($game_code == 'clwmc') {
								$link_view_more = 'https://medium.com/test-4formonth/game2/home';
							} else {
								$show = false;
								$link_view_more = 'javascript:void(0);';
							}
						?>
                        <div class="btn-more"><a href="<?php echo $link_view_more; ?>" <?php if($show) echo 'target="_blank"'; ?>><span>VIEW MORE</span></a></div>
                    </div>
                    <div class="game-view-section__contents">
                        <div class="announcement-list announcement-slide swiper-container">
                            <div class="swiper-wrapper">
							<?php
								$query_news = mysqli_query($connect, "SELECT * FROM News WHERE m_game_code = '" . $game_code . "' ORDER BY m_index DESC LIMIT 3");

								while ( $row = mysqli_fetch_array($query_news) ) {
									$info = $row;

									$timestamp = strtotime($info['m_date']);
									$time = date("d F Y", $timestamp);

									$datetime = date("Y-m-d", $timestamp);

									$link = $info['m_link'] ? $info['m_link'] : "javascript:void(0);";
									$img_url = $info['m_img_url'] ? $info['m_img_url'] : "https://dummyimage.com/526x296/333/fff";
							?>
                                <div class="announcement-item swiper-slide">
                                    <a href="<?php echo $link;?>" <?php if ($info['m_link'] !== "") echo "target='_blank'";?>>
                                        <figure class="lazyload">
                                            <img loading="lazy" data-unveil="<?php echo $img_url; ?>" src="../assets/images/blank.gif" alt="" />
                                            <noscript><img loading="lazy" src="https://dummyimage.com/526x296/333/fff" alt="" /></noscript>
                                        </figure>
                                        <div class="announcement-info">
                                            <h3 class="announcement-info__title"><?php echo $info['m_title']; ?></h3>
                                            <div class="announcement-info__desc"><?php echo $info['m_descrpition']; ?></div>
                                            <time datetime="<?php echo $datetime; ?>"><span><?php echo $time; ?></span></time>
                                        </div>
                                    </a>
                                </div><!-- .announcement-item -->
							<?php
								}
							?>
                                
                            </div><!-- .swiper-wrapper -->
                        </div><!-- .announcement-list -->
                        <div class="swiper-navigation">
                            <button class="swiper-button-prev"><span class="sr-only">PREV</span></button>
                            <button class="swiper-button-next"><span class="sr-only">NEXT</span></button>
                        </div><!-- .swiper_navigation -->
                    </div><!-- .game-view-section__contents -->
                </div><!-- .game-view__announcement__inner -->
            </div><!-- .wrap -->
        </div><!-- .game-view__announcement -->

        <div class="game-view__nfts">
            <div class="wrap">
                <div class="game-view-section__head">
                    <h2 class="game-view-section__head__title">NFTs</h2>
                    <a href="#nfts-filter-popup" class="nfts-filter-popup-open"><span>Filter</span></a>
                    <div class="btn-more"><a href="/sub/nfts.php"><span>VIEW MORE</span></a></div>
                </div>
                <div class="game-view-section__contents">
                    <div class="nfts-filter">
                        <div class="nfts-filter__list">
                            <!--div class="nfts-filter__item nfts-filter__item--active">
                                <button type="button" class="nfts-filter__button"><span>Games</span></button>
                                <div class="nfts-filter__content">
                                    <ul>
                                        <li><label class="custom-checkbox">Mining Maze<input type="checkbox"><span class="checkmark"></span></label></li>
                                        <li><label class="custom-checkbox">Claw Machine<input type="checkbox"><span class="checkmark"></span></label></li>
                                    </ul>
                                </div><!-- .nfts-filter__content -->
                            <!--/div><!-- .nfts-filter__item -->
                            <div class="nfts-filter__item nfts-filter__item--active">
                                <button type="button" class="nfts-filter__button"><span>Rarity</span></button>
                                <div class="nfts-filter__content">
                                    <ul>
										<li><label class="custom-checkbox">Basic<input type="checkbox" class="rarityCheckbox" value="Basic"><span class="checkmark"></span></label></li>
										<li><label class="custom-checkbox">Rare<input type="checkbox" class="rarityCheckbox" value="Rare"><span class="checkmark"></span></label></li>
										<li><label class="custom-checkbox">Epic<input type="checkbox" class="rarityCheckbox" value="Epic"><span class="checkmark"></span></label></li>
										<li><label class="custom-checkbox">Unique<input type="checkbox" class="rarityCheckbox" value="Unique"><span class="checkmark"></span></label></li>
										<li><label class="custom-checkbox">Legend<input type="checkbox" class="rarityCheckbox" value="Legend"><span class="checkmark"></span></label></li>
                                    </ul>
                                </div><!-- .nfts-filter__content -->
                            </div><!-- .nfts-filter__item -->
                            <div class="nfts-filter__item nfts-filter__item--active">
                                <button type="button" class="nfts-filter__button"><span>Number</span></button>
                                <div class="nfts-filter__content">
                                    <div class="nfts-filter__search">
                                        <label class="nfts-search__label">
                                            <input class="nfts-search__input" name="search" id="search" type="text" value="" placeholder="Search by number">
                                        </label><!-- .nfts-search__label -->
                                        <button class="nfts-search__submit"><span class="sr-only">검색</span></button>
                                    </div>
                                </div><!-- .nfts-filter__content -->
                            </div><!-- .nfts-filter__item -->
                        </div><!-- .nfts-filter__list -->
                    </div><!-- .nfts-filter -->

                    <div class="sub-nft-list">

                    </div><!-- .nft-list -->
                </div><!-- .game-view-section__contents -->
            </div><!-- .wrap -->
        </div><!-- .game-view__nfts -->
		<?php
			}	
		?>
    </div><!-- .article-body -->
</div><!-- #container -->

<div id="nfts-filter-popup" class="nfts-filter-popup mfp-hide">
    <div class="nfts-filter-popup__head">
        <h2>Filter</h2>
    </div><!-- .nfts-filter-popup__head -->
    <div class="nfts-filter-popup__body">
        <button type="button" class="btn-basic btn-primary nfts-filter-popup__confirm"><span>OK</span></button>
    </div><!-- .nfts-filter-popup__body -->
</div><!-- .nfts-filter-popup -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
$(document).ready(function() {

    $('.categoryCheckbox, .rarityCheckbox').change(function(){
        var selectedCategories = $('.categoryCheckbox:checked').map(function() {
            return $(this).val();
        }).get();

        var selectedRarities = $('.rarityCheckbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        $('.nft-item').each(function(){
            var category = $(this).data('category');
            var rarity = $(this).data('rarity');
            
            if((selectedCategories.length === 0 || selectedCategories.includes(category)) &&
               (selectedRarities.length === 0 || selectedRarities.includes(rarity))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });


	$("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".nft-item").filter(function() {
            var nftNumber = $(this).find("li span:contains('#')").text().toLowerCase();
            $(this).toggle(nftNumber.indexOf(value) > -1 || value === "");
        });
    });
});

    function needLogin() {
        swal({
            text: '<?php echo $lang["Login_Required"]; ?>',
            buttons: {
                cancel : '<?php echo $lang["Cancel"]; ?>',
                confirm : {
                    text : '<?php echo $lang["Go_To_Login"]; ?>',
                    value : 'catch'
                },
            },
        }).then(function(value){
            if(value == 'catch'){
                location.href='/sub/login.php?return_path=<?php echo $return_url; ?>';
            }
        })
    }


	async function get_nft_list_etherscan_all(game_code) {
	    let tokenIdArray = [];

	    const etherscanApiUrl = "https://api.etherscan.io/api";  // live api server
	    let sendUrl = etherscanApiUrl
	        + "?module=account"
	        + "&action=tokennfttx"
	        + "&page=1"
	        + "&offset=10000"
	        + "&sort=asc"
	        + "&contractaddress=" + nftAddress[game_code]
	        + "&apikey=49RC9FF8J4T9UT8P3TJIMI32PIXDJBJ79K";
	    //console.log(sendUrl);

	    try {
	        let response = await fetch(sendUrl);
	        let data = await response.json();
	        let resJsonResult = data.result;
			console.log(resJsonResult.length);
	        // 모든 받은 NFT ID 를 push 한다.
	        for (let i = 0; i < resJsonResult.length; i++) {
	            let trxOne = resJsonResult[i];
	            if (!tokenIdArray.includes(trxOne.tokenID)) {
	                tokenIdArray.push(trxOne.tokenID);
	            }
	        }
			console.log(tokenIdArray);
	        return tokenIdArray;
	    } catch (error) {
	        console.error('Error fetching NFT list:', error);
	        return [];
	    }
	}
</script>