<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';

	$query = "SELECT m_token_balance FROM AccountsChain WHERE m_login_id = '".$_SESSION['sess_login_id']."' AND m_symbol = 'ETH'";
//	echo $query;
	$que = mysqli_query($connect, $query);
	$info_user = mysqli_fetch_array($que);

	$token_balance = $info_user['m_token_balance'] ? rtrim(rtrim($info_user['m_token_balance'], '0'), '.') : 0;
?>

<div id="container" class="page-nfts-collections">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title">NFTs COLLECTIONS</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="collections-list accordion-list">
			<?php
				$query = "SELECT m_game_code FROM AccountsPSCTNodeWallet WHERE m_login_id = '".$_SESSION['sess_login_id']."'";
				$result = mysqli_query($connect, $query);

				$gameCodes = array();
				$token = array();
				
				while($info = mysqli_fetch_array($result)) {
					if ($info['m_game_code'] == 'clwmc') { continue;}
					$query_game = "SELECT m_title, m_main_banner_title FROM GamesLanding WHERE m_game_code = '".$info['m_game_code']."'";
					$result_game = mysqli_query($connect, $query_game);
					$game = mysqli_fetch_array($result_game);
					
					$query_nft = "SELECT m_NFTs, m_NFT_images FROM AccountsPSCTNodeWallet WHERE m_login_id = '".$_SESSION['sess_login_id']."' AND m_game_code = '".$info['m_game_code']."'";
					$result_nft = mysqli_query($connect, $query_nft);
					$info_nft = mysqli_fetch_array($result_nft);

					$nft = explode("|", $info_nft['m_NFTs']);
					$nft = array_filter($nft);

					$count = count($nft);
					
					$image_nft = rtrim($info_nft['m_NFT_images'], '|');
					$image_nft = explode("|", $image_nft);
					$image_nft = array_filter($image_nft);
				
			?>	
                <div class="collections-item <?php if($count != 0) { ?>accordion-item <?php } ?>" >
                    <div class="collections-title accordion-title">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="/assets/images/<?php echo $info['m_game_code']; ?>_NFTs_collections.png" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/200x200/333/fff" alt="" /></noscript>
                        </figure>
                        <div class="collections-title__contents">
							<p><b><?php echo $game['m_title']; ?></span></b><span><?php echo $count; ?></span></p>
                            <!--p><b>Claw Machine <span>- Catch! The Friends -</span></b><span>3</span></p-->
                            <button type="button" class="accordion-control"><i class="sr-only">open/close</i></button>
                        </div>
                    </div><!-- .collections-title -->
                    <div class="collections-content accordion-content" <?php if($count == 0) { ?>style="display:none" <?php } ?>>
					 <?php if ($info['m_game_code'] == 'mm') {?>
                        <div class="btn-nft-utility-wrap">
                            <a href="#btn-reinforce" class="btn-primary nft-utility-popup-open btn-reinforce"><span>Level Up</span></a>
                            <a href="#btn-synthesize" class="btn-primary nft-utility-popup-open btn-synthesize"><span>Breeding</span></a>
                        </div>
					<?php } ?>
                        <div class="collections-view_list">
						<?php
							for($i = 0; $i < $count; $i++){
								$query_nftData = "SELECT m_level, m_breeding FROM AccountsNFTData WHERE m_token_id = '".$nft[$i]."' AND m_game_code = '".$info['m_game_code']."'";
								$result_nftData = mysqli_query($connect, $query_nftData);
								$info_nftData = mysqli_fetch_array($result_nftData);

								if ($nft[$i] >= 0 && $nft[$i] <= 5554) {
								    $rarity = "Basic";
								} else if ($nft[$i] >= 5555 && $nft[$i] <= 7776) {
								    $rarity = "Rare";
								} else if ($nft[$i] >= 7777 && $nft[$i] <= 8887) {
								    $rarity = "Epic";
								} else if ($nft[$i] >= 8888 && $nft[$i] <= 9443) {
								    $rarity = "Unique";
								} else if ($nft[$i] >= 9444 && $nft[$i] <= 9554) {
								    $rarity = "Legend";
								}

								if ($info_nftData['m_level'] == "") {
									$info_nftData['m_level'] = 0 ;
								};
								if ($info_nftData['m_breeding'] == "") {
									$info_nftData['m_breeding'] = 5 ;
								};
						?>
                            <div class="nft-item">
                                <label class="custom-checkbox"><input type="checkbox" id="nft_select_<?php echo $nft[$i]; ?>" name="nft_select" value="<?php echo $nft[$i].':'.$info_nftData['m_level'].':'.$info_nftData['m_breeding'].':'.$info['m_game_code']; ?>"><span class="checkmark"></span>
                                    <figure class="lazyload">
										<img data-unveil="https://<?php echo $HOST; ?>.nebula3gamefi.com/img_remote/test/game/mm/<?php echo strtolower($rarity);?>.png" src="https://<?php echo $HOST; ?>.nebula3gamefi.com/img_remote/test/game/mm/<?php echo strtolower($rarity);?>.png" alt="" class="lazyload--loaded" style="z-index:1">
                                        <img data-unveil="<?php echo $image_nft[$i]; ?>" src="<?php echo $image_nft[$i]; ?>" alt="" class="lazyload--loaded">
                                        <noscript><img loading="lazy" src="<?php echo $image_nft[$i]; ?>" alt="" /></noscript>
                                    </figure>
                                    <div class="nft-info">
                                        <h3 class="nft-info__title">#<?php echo $nft[$i]; ?></h3>
                                        <a class="btn-enlarge" href="<?php echo $image_nft[$i]; ?>"><span class="sr-only">enlarge</span></a>
                                    </div>
                                </label>
                            </div>
						<?php
							}
						?>
                            <!--div class="nft-item">
                                <label class="custom-checkbox"><input type="checkbox"><span class="checkmark"></span>
                                    <figure class="lazyload">
                                        <img loading="lazy" data-unveil="https://dummyimage.com/282x282/333/fff" src="https://dummyimage.com/282x282/333/fff" alt="" class="lazyload--loaded">
                                        <noscript><img loading="lazy" src="https://dummyimage.com/282x282/333/fff" alt="" /></noscript>
                                    </figure>
                                    <div class="nft-info">
                                        <h3 class="nft-info__title">#001</h3>
                                        <a class="btn-enlarge" href="https://dummyimage.com/700x700/333/fff"><span class="sr-only">enlarge</span></a>
                                    </div>
                                </label>
                            </div-->
                        </div><!-- .collections-view_list -->
                    </div><!-- .collections-content -->
                </div><!-- .collections-item -->
				<?php
				}	
				/*
				<div class="collections-item accordion-item">
                    <div class="collections-title accordion-title">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="https://dummyimage.com/200x200/333/fff" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/200x200/333/fff" alt="" /></noscript>
                        </figure>
                        <div class="collections-title__contents">
                            <p><b>Mining Maze <span>- Get a big treasure box! -</span></b><span>1</span></p>
                            <button type="button" class="accordion-control"><i class="sr-only">open/close</i></button>
                        </div>
                    </div><!-- .collections-title -->
                    <div class="collections-content accordion-content">
                        <div class="btn-nft-utility-wrap">
                            <a href="#btn-reinforce" class="btn-primary nft-utility-popup-open btn-reinforce"><span>Level Up</span></a>
                            <a href="#btn-synthesize" class="btn-primary nft-utility-popup-open btn-synthesize"><span>Breeding</span></a>
                        </div>
                        <div class="collections-view_list">
                            <div class="nft-item">
                                <label class="custom-checkbox"><input type="checkbox"><span class="checkmark"></span>
                                    <figure class="lazyload">
                                        <img loading="lazy" data-unveil="https://dummyimage.com/282x282/333/fff" src="https://dummyimage.com/282x282/333/fff" alt="" class="lazyload--loaded">
                                        <noscript><img loading="lazy" src="https://dummyimage.com/282x282/333/fff" alt="" /></noscript>
                                    </figure>
                                    <div class="nft-info">
                                        <h3 class="nft-info__title">#001</h3>
                                        <a class="btn-enlarge" href="https://dummyimage.com/700x700/333/fff"><span class="sr-only">enlarge</span></a>
                                    </div>
                                </label>
                            </div>
                        </div><!-- .collections-view_list -->
                    </div><!-- .collections-content -->
                </div><!-- .collections-item -->
				*/
				?>

            </div><!-- .collections-list -->
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php /* 강화하기 팝업 */ ?>
<div class="nft-utility-popup mfp-hide" id="btn-reinforce" data-rating="Unique">
    <div class="nft-utility-popup__box">
        <div class="nft-utility-popup__contents nft-utility-popup__reinforce">
            <figure class="nft-utility-popup__thumb"><img id="lvUp_img" src="https://dummyimage.com/282x282/333/fff" alt=""></figure>
            <div class="nft-utility-popup__info-wrap">
				<input type='hidden' id='lvUp_game_code' name='lvUp_game_code' value=''>
                <ul class="nft-utility-popup__info">
                    <li><b>NFT Number</b><p id="lvUp_token_id">001</p></li>
                    <li><b>Rarity</b><p id="lvUp_rarity">A</p></li>
                    <li><b>Current Level</b><p id="lvUp_level">1</p></li>
                    <li><b>Upgrade Cost</b><p><img src="../assets/images/symbol-sn3.svg" alt=""><span id="lvUp_cost">-</span></p></li>
                </ul>
                <div class="nft-utility-hold">
                    <figure><img src="../assets/images/symbol-sn3.svg" alt=""></figure><span>SN3 Balance</span>
                    <p id="token_balance1"><?php echo $token_balance; ?></p>
                </div>
            </div>
        </div><!-- .nft-utility-popup__contents -->
        <div class="btn-nft-utility-execute__warp">
            <button type="button" class="btn-basic btn-primary btn-nft-utility-execute" id="level_up"><span>Level Up</span></button>
        </div>
        <div class="nft-utility-popup__guide nft-utility-popup__guide--reinforce swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Basic</p>
                    <table>
                        <tr>
                            <th>Current Level</th>
                            <th>Next Level</th>
                            <th>Upgrade Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>5</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>7.5</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3</td>
                            <td>10</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>12.5</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>15</td>
                            <td>50</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Rare</p>
                    <table>
                        <tr>
                            <th>Current Level</th>
                            <th>Next Level</th>
                            <th>Upgrade Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>50</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>70</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3</td>
                            <td>90</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>110</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>130</td>
                            <td>30</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Epic</p>
                    <table>
                        <tr>
                            <th>Current Level</th>
                            <th>Next Level</th>
                            <th>Upgrade Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>80</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>105</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3</td>
                            <td>130</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>155</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>180</td>
                            <td>25</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Unique</p>
                    <table>
                        <tr>
                            <th>Current Level</th>
                            <th>Next Level</th>
                            <th>Upgrade Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>100</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>130</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3</td>
                            <td>160</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>190</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>220</td>
                            <td>20</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Legend</p>
                    <table>
                        <tr>
                            <th>Current Level</th>
                            <th>Next Level</th>
                            <th>Upgrade Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>400</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>450</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3</td>
                            <td>500</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>550</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>600</td>
                            <td>50</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
            </div><!-- .swiper-wrapper -->
            <div class="swiper-navigation">
                <button class="swiper-button-prev"><span class="sr-only">PREVE</span></button>
                <button class="swiper-button-next"><span class="sr-only">NEXT</span></button>
            </div><!-- .swiper-navigation -->
        </div>
        <button type="button" class="nft-utility-popup__close"><span class="sr-only">Close</span></button>
    </div><!-- .nft-utility-popup__box -->
</div><!-- .nft-utility-popup -->

<?php /* 합성하기 팝업  */ ?>
<div class="nft-utility-popup mfp-hide" id="btn-synthesize" data-rating="rare">
    <div class="nft-utility-popup__box">
        <div class="nft-utility-popup__contents nft-utility-popup__synthesize">
            <div class="nft-utility-popup__synthesize-list">
			<input type='hidden' id='brdg_game_code' name='brdg_game_code' value=''>
                <div class="nft-utility-popup__synthesize-item">
                    <figure class="nft-utility-popup__thumb"><img id="brdg_token_img_1" src="https://dummyimage.com/282x282/333/fff" alt=""></figure>
                    <ul class="nft-utility-popup__info">
                        <li><b>NFT Number</b><p id="brdg_token_id_1">001</p></li>
                        <li><b>Breeding Count</b><p id="brdg_count_1">(0/5)</p></li>
                        <li><b>Breeding Cost</b><p><img src="../assets/images/symbol-sn3.svg" alt=""><span id="brdg_cost_1">-</span></p></li>
                    </ul>
                </div>
                <div class="nft-utility-popup__synthesize-item">
                    <figure class="nft-utility-popup__thumb"><img id="brdg_token_img_2" src="https://dummyimage.com/282x282/333/fff" alt=""></figure>
                    <ul class="nft-utility-popup__info">
                        <li><b>NFT Number</b><p id="brdg_token_id_2">001</p></li>
                        <li><b>Breeding Count</b><p id="brdg_count_2">(0/5)</p></li>
                        <li><b>Breeding Cost</b><p><img src="../assets/images/symbol-sn3.svg" alt=""><span id="brdg_cost_2">-</span></p></li>
                    </ul>
                </div>
                <ul class="nft-utility-popup__synthesize-result">
                    <li><b>Total Breeding Cost</b><p><img src="../assets/images/symbol-sn3.svg" alt=""><span id="brdg_total_cost">-</span></p></li>
                    <li><b>Probability</b><p id="brdg_p">50%</p></li>
                </ul>
            </div>
            <div class="nft-utility-popup__info_wrap">
                <div class="nft-utility-hold">
                    <figure><img src="../assets/images/symbol-sn3.svg" alt=""></figure><span>SN3 Balance</span>
                    <p id="token_balance2"><?php echo $token_balance; ?></p>
                </div>
            </div>
        </div><!-- .nft-utility-popup__contents -->
        <div class="btn-nft-utility-execute__warp">
            <button type="button" class="btn-basic btn-primary btn-nft-utility-execute" id="breeding"><span>Breeding</span></button>
        </div>
        <div class="nft-utility-popup__guide nft-utility-popup__guide--synthesize swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Basic</p>
                    <table>
                        <tr>
                            <th>Current Breeding Count</th>
                            <th>Breeding Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>100</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>200</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>220</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>250</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>300</td>
                            <td>80</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Rare</p>
                    <table>
                        <tr>
                            <th>Current Breeding Count</th>
                            <th>Breeding Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>200</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>1,000</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>1,100</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>1,250</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>1,600</td>
                            <td>40</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Epic</p>
                    <table>
                        <tr>
                            <th>Current Breeding Count</th>
                            <th>Breeding Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>400</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>2,800</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>3,200</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>3,800</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>4,800</td>
                            <td>30</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
                <div class="swiper-slide">
                    <p class="nft-utility-popup__guide-title">Unique</p>
                    <table>
                        <tr>
                            <th>Current Breeding Count</th>
                            <th>Breeding Cost (SN3)</th>
                            <th>Probability (%)</th>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>800</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5,600</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>6,400</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>8,000</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>10,000</td>
                            <td>20</td>
                        </tr>
                    </table>
                </div><!-- .swiper-slide -->
            </div><!-- .swiper-wrapper -->
            <div class="swiper-navigation">
                <button class="swiper-button-prev"><span class="sr-only">PREVE</span></button>
                <button class="swiper-button-next"><span class="sr-only">NEXT</span></button>
            </div><!-- .swiper-navigation -->
        </div>
        <button type="button" class="nft-utility-popup__close"><span class="sr-only">Close</span></button>
    </div><!-- .nft-utility-popup__box -->
</div><!-- .nft-utility-popup -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
var level_up = { 
	Basic : { 
		'0': 5, 
		'1': 7.5,
		'2': 10,
		'3': 12.5,
		'4': 15,
	},
	Rare : { 
		'0': 50, 
		'1': 70,
		'2': 90,
		'3': 110,
		'4': 130,
	},
	Epic : { 
		'0': 80, 
		'1': 105,
		'2': 130,
		'3': 155,
		'4': 180,
	},
	Unique : { 
		'0': 100, 
		'1': 130,
		'2': 160,
		'3': 190,
		'4': 220,
	},
	Legend : { 
		'0': 400, 
		'1': 450,
		'2': 500,
		'3': 550,
		'4': 600,
	},
};

var breeding = { 
	Basic : { 
		'5': 100, 
		'4': 200,
		'3': 220,
		'2': 250,
		'1': 300,
	},
	Rare : { 
		'5': 200, 
		'4': 1000,
		'3': 1100,
		'2': 1250,
		'1': 1600,
	},
	Epic : { 
		'5': 400, 
		'4': 2800,
		'3': 3200,
		'2': 3800,
		'1': 4800,
	},
	Unique : { 
		'5': 800, 
		'4': 5600,
		'3': 6400,
		'2': 8000,
		'1': 10000,
	}
};


function updateSN3() {
	$.ajax({
		     type:"POST",        
		     url:"/includes/proc_baviWallet.php",     
		     data : ({mode:"ch0040"}),
		     timeout : 5000,  
		     cache : false,        
		     success: function whenSuccess(args){
		  		var token_balance = args.trim();
				token_balance = addCommas(token_balance);
				$('.SN3_balance_n').text(formatDecimal(token_balance) + " SN3");

				if (token_balance > 1000) {
					$("#transferOut_message_caution").hide();
				} else {
					$("#transferOut_message_caution").show();
				}
				$('#token_balance1').text(formatDecimal(token_balance));
				$('#token_balance2').text(formatDecimal(token_balance));
		     },
		     error: function whenError(e){
			//	console.log("code : " + e.status + "message : " + e.responseText);
		     }
		  });
}


$('input:checkbox[name="nft_select"]').change(function() {
    var checked = $('input:checkbox[name="nft_select"]:checked');
    if (checked.length > 2) {
        $(this).prop('checked', false);
    }
});

$('.btn-primary.nft-utility-popup-open.btn-reinforce').on('click',function(){
	var cbx = "-";
	var	rarity = "-";
	const checkedCheckboxes = $('input:checkbox:checked[name="nft_select"]');
	if (checkedCheckboxes.length < 1) {
		$.magnificPopup.close();
	    swal({
			text: 'Failure!\n\nYou need to select NFT!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	if (checkedCheckboxes.length > 1) {
		$.magnificPopup.close();
	    swal({
			text: 'Failure!\n\nYou can only select 1 NFT!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	$('input:checkbox:checked[name="nft_select"]').each(function(i) { cbx = this.value; return; });
	
	const parts = cbx.split(":");
	const token_id = parts[0]; 
	const level = parts[1];
	const game_code = parts[3];

	if (token_id >= 0 && token_id <= 5554) {
	    rarity = "Basic";
	} else if (token_id >= 5555 && token_id <= 7776) {
	    rarity = "Rare";
	} else if (token_id >= 7777 && token_id <= 8887) {
	    rarity = "Epic";
	} else if (token_id >= 8888 && token_id <= 9443) {
	    rarity = "Unique";
	} else if (token_id >= 9444 && token_id <= 9554) {
	    rarity = "Legend";
	}

	$('.nft-utility-popup').data('rating', rarity);
	console.log($('.nft-utility-popup').data('rating'));
	nfts_utility_popup_slider();

	$('#lvUp_img').attr('src','https://cdn.aurorahunt.xyz/nft/' + game_code + '/img/'+ token_id +'.png');
	$('#lvUp_token_id').text(token_id);
	$('#lvUp_rarity').text(rarity);
	$('#lvUp_level').text(level + '/5');
	$('#lvUp_cost').text(level_up[rarity][level]);

	$('#lvUp_game_code').val(game_code);

	
	$.magnificPopup.open({
        items: {
            src: '#btn-reinforce'
        },
        type: 'inline',
        showCloseBtn: false,
        closeOnContentClick : false,
        fixedContentPos: true,
        fixedBgPos: true,
        callbacks: {
            open: function() {
                $('body').addClass('mfp-popup-open');
                //$('html').css('overflow-y','hidden');
            },
            afterClose: function() {
                $('body').removeClass('mfp-popup-open');
                //$('html').removeAttr('style');
            }
        },
        midClick: true
    });
});

$('.btn-primary.nft-utility-popup-open.btn-synthesize').on('click',function(){
	var cbxlist = new Array();
	$('input:checkbox:checked[name="nft_select"]').each(function(i) { cbxlist[i] = this.value; });
	if (cbxlist.length != 2) {
	    swal({
			text: 'Failure!\n\nYou need to select 2 NFT!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	
	cbxlist.forEach(function(cbx, index) {

	    const parts = cbx.split(':');
	    const token_id = parts[0];
	    const level = parts[1];
	    const breeding = parts[2];
		const game_code = parts[3];

		let rarity;

		if (token_id >= 0 && token_id <= 5554) {
			rarity = "Basic";
		} else if (token_id >= 5555 && token_id <= 7776) {
		    rarity = "Rare";
		} else if (token_id >= 7777 && token_id <= 8887) {
		    rarity = "Epic";
		} else if (token_id >= 8888 && token_id <= 9443) {
		    rarity = "Unique";
		} else if (token_id >= 9444 && token_id <= 9554) {
			swal({
				text: 'Failure!\n\nLegend can\'t breeding !!!\n\n',
				buttons: 'Confirm',
			});
		    return;
		}

	    window['token_id' + (index + 1)] = token_id;
		window['level' + (index + 1)] = level;
		window['breeding' + (index + 1)] = breeding;
		window['rarity' + (index + 1)] = rarity;
		window['game_code' + (index + 1)] = game_code;
	});

	if (game_code1 != game_code2) {
		swal({
			text: 'Failure!\n\nYou can only select the same project!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}

	if (level1 != 5 || level2 != 5) {
		swal({
			text: 'Failure!\n\nYou can only select level 5 NFT!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	
	const cost_1 = breeding[rarity1][breeding1];
	const cost_2 = breeding[rarity2][breeding2];
	let Probability;

	if (rarity1 !== rarity2) {
		swal({
			text: 'Failure!\n\nYou can only select the same rarity!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	} else if ( rarity1 == "Basic") {
		Probability = '80%';
	} else if ( rarity1 == "Rare") {
		Probability = '40%';
	} else if ( rarity1 == "Epic") {
		Probability = '30%';
	} else if ( rarity1 == "Unique") {
		Probability = '20%';
	}

	$('.nft-utility-popup').data('rating', rarity1);
	console.log($('.nft-utility-popup').data('rating'));
	nfts_utility_popup_slider();

	$('#brdg_token_img_1').attr('src','https://cdn.aurorahunt.xyz/nft/' + game_code1 + '/img/'+ token_id1 +'.png');
	$('#brdg_token_id_1').text(token_id1);
	$('#brdg_count_1').text('(' + breeding1 + '/5)');
	$('#brdg_cost_1').text(breeding[rarity1][breeding1]);

	$('#brdg_token_img_2').attr('src','https://cdn.aurorahunt.xyz/nft/' + game_code2 + '/img/'+ token_id2 +'.png');
	$('#brdg_token_id_2').text(token_id2);
	$('#brdg_count_2').text('(' + breeding2 + '/5)');
	$('#brdg_cost_2').text(breeding[rarity2][breeding2]);

	let total_cost = !isNaN(cost_1) && !isNaN(cost_2) ? cost_1 + cost_2 : '-';

	$('#brdg_total_cost').text(total_cost);
	$('#brdg_p').text(Probability);

	$('#brdg_game_code').val(game_code1);

	$.magnificPopup.open({
        items: {
            src: '#btn-synthesize'
        },
        type: 'inline',
        showCloseBtn: false,
        closeOnContentClick : false,
        fixedContentPos: true,
        fixedBgPos: true,
        callbacks: {
            open: function() {
                $('body').addClass('mfp-popup-open');
                //$('html').css('overflow-y','hidden');
            },
            afterClose: function() {
                $('body').removeClass('mfp-popup-open');
                //$('html').removeAttr('style');
            }
        },
        midClick: true
    });
});


function nfts_utility_popup_slider(){

    if( !$('.nft-utility-popup__guide').length ){ return; }

    //table slider
    $('.nft-utility-popup__guide').each(function () {

        var $slider = $(this);

        var nft_utility_slider = new Swiper($slider, {
            init: false,
            spaceBetween: 30,
            speed : 800,
            loop : true,
            loopedSlides: 1,
            observer: true,
            observeParents: true,
            parallax:true,
            simulateTouch:true,
            navigation: {
                nextEl: $slider.find('.swiper-button-next'),
                prevEl: $slider.find('.swiper-button-prev')
            },
        });

        nft_utility_slider.on('init', function () {
            //nft_rating
            if( $('.nft-utility-popup').data('rating') == 'Basic' ){
                nft_utility_slider.slideTo(1,10)
            } else if ( $('.nft-utility-popup').data('rating') == 'Rare' ){
                nft_utility_slider.slideTo(2,10)
            } else if ( $('.nft-utility-popup').data('rating') == 'Epic' ){
                nft_utility_slider.slideTo(3,10)
            } else if ( $('.nft-utility-popup').data('rating') == 'Unique' ){
                nft_utility_slider.slideTo(4,10)
            } else if ( $('.nft-utility-popup').data('rating') == 'Legend' ){
                nft_utility_slider.slideTo(5,10)
            }
        });

        nft_utility_slider.init();
        nft_utility_slider.update()

    })

}

$('#level_up').on('click',function(){ 

	const r = Math.random(); // 0~1 random float
	const token_id = $('#lvUp_token_id').text();
	let p; //Probability
	if (token_id >= 0 && token_id <= 5554) {
	    p = 0.5;
	} else if (token_id >= 5555 && token_id <= 7776) {
	    p = 0.3;
	} else if (token_id >= 7777 && token_id <= 8887) {
	    p = 0.25;
	} else if (token_id >= 8888 && token_id <= 9443) {
	    p = 0.2;
	} else if (token_id >= 9444 && token_id <= 9554) {
	    p = 0.5;
	}

	const cost = $('#lvUp_cost').text();
	let str = $('#lvUp_level').text();
	let parts = str.split('/');
	const level = parts[0];
	const game_code = $('#lvUp_game_code').val();

	const token_balance = $('#token_balance1').text();

	if (Number(cost) > Number(token_balance)) {
		swal({
			text: 'Failure!\n\nInsufficient Funds!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}

	if (level == 5) {
		swal({
			text: 'Failure!\n\nYou can only upgrade NFTs that are below level 5!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	
	if (r < p) {
		$.ajax({
		  type:"POST",        
		  url:"/includes/proc_galaxyWallet.php",     
		  data : ({mode:"level_up", status:"success",token_id: token_id, cost:cost, game_code: game_code}),
		  timeout : 5000,  
		  cache : false,        
		  success: function whenSuccess(args){
			//	console.log(args);
			updateSN3();
			$('#lvUp_level').text(Number(level)+1 + '/5');

			var str = $('#nft_select_'+token_id).val();
			let parts = str.split(":");
			parts[1] = Number(level) + 1;
			let newStr = parts.join(":");
			$('#nft_select_'+token_id).val(newStr);

			const rarity = $('#lvUp_rarity').text();
			const lv = Number(level)+1;
			$('#lvUp_cost').text(level_up[rarity][lv]);

				swal({
					text: 'Congratulations!\n\nNFT: '+token_id+' upgrade succeeded!!!\n\n',
					buttons: 'Confirm',
				})
				return;
		  },
		  error: function whenError(e){
			console.log("code : " + e.status + "message : " + e.responseText);
		  }
		});
	} else {
		$.ajax({
		  type:"POST",        
		  url:"/includes/proc_galaxyWallet.php",     
		  data : ({mode:"level_up", status:"failure",token_id: token_id, cost:cost, game_code: game_code}),
		  timeout : 5000,  
		  cache : false,        
		  success: function whenSuccess(args){
			//	console.log(args);
			updateSN3();
				swal({
					text: 'Failure!\n\nNFT: '+token_id+' upgrade fail!!!\n\n',
					buttons: 'Confirm',
				})
				return;
		  },
		  error: function whenError(e){
			console.log("code : " + e.status + "message : " + e.responseText);
		  }
		});

	}

});

$('#breeding').on('click',function(){
	
	const token_id1 = $('#brdg_token_id_1').text();
	const token_id2 = $('#brdg_token_id_2').text();
	const cost = $('#brdg_total_cost').text();

	const game_code = $('#brdg_game_code').val();

	const token_balance = $('#token_balance2').text();

	if (Number(cost) > Number(token_balance)) {
		swal({
			text: 'Failure!\n\nInsufficient Funds!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}

	var match1 = $('#brdg_count_1').text().match(/\d+/);
	var match2 = $('#brdg_count_2').text().match(/\d+/);

	var breeding1 = match1 ? parseInt(match1[0]) : null;
	var breeding2 = match2 ? parseInt(match2[0]) : null;

	if (breeding1 <= 0) {
		swal({
			text: 'Failure!\n\nNFT: '+token_id1+' has reached the breeding limit!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}
	if (breeding2 <= 0) {
		swal({
			text: 'Failure!\n\nNFT: '+token_id2+' has reached the breeding limit!!!\n\n',
			buttons: 'Confirm',
		});
		return;
	}

/*
	$('#brdg_count_1').text('('+(Number(match1)-1)+'/5)');
	var str = $('#nft_select_'+token_id1).val();
	let parts = str.split(":");
	parts[2] = breeding1 - 1;
	let newStr = parts.join(":");
	$('#nft_select_'+token_id1).val(newStr);
	
	$('#brdg_count_2').text('('+(Number(match2)-1)+'/5)');
	var str = $('#nft_select_'+token_id2).val();
	let parts2 = str.split(":");
	parts2[2] = breeding1 - 1;
	let newStr2 = parts2.join(":");
	$('#nft_select_'+token_id2).val(newStr2);
*/
	let rarity;
	let breed_rarity;

	if (token_id1 >= 0 && token_id1 <= 5554) {
		rarity = "Basic";
		breed_rarity = "Rare";
	} else if (token_id1 >= 5555 && token_id1 <= 7776) {
	    rarity = "Rare";
		breed_rarity = "Epic";
	} else if (token_id1 >= 7777 && token_id1 <= 8887) {
	    rarity = "Epic";
		breed_rarity = "Unique";
	} else if (token_id1 >= 8888 && token_id1 <= 9443) {
	    rarity = "Unique";
		breed_rarity = "Legend";
	}

	const r = Math.random();
	const Probability = $('#brdg_p').text();
	const p = parseFloat(Probability.replace('%', '')) / 100;
	if (r < p) {

		$('#breeding').off('click');
		$('#breeding').find('span').text("Processing...");
		
		$.ajax({
		  type:"POST",        
		  url:"/includes/proc_galaxyWallet.php",     
		  data : ({mode:"breeding", status:"success", rarity: breed_rarity, token_id: token_id1, token_id2: token_id2, cost:cost, game_code: game_code}),
		  timeout : 5000,  
		  cache : false,        
		  success: function whenSuccess(args){
				console.log(args);
				switch(args.trim()){
					case("success"):
						swal({
							text: 'Congratulations!\n\nYou succeeded in this breeding!!!\n\n',
							buttons: 'Confirm',
						}).then(function(){
							location.reload();
						});
					break;
					case("failure"):
						swal({
							text: 'Failure!\n\nNFT: '+token_id1+' and '+token_id2+'  breeding fail!!!\n\n',
							buttons: 'Confirm',
						})
						
					break;
				}
				
		  },
		  error: function whenError(e){
			console.log("code : " + e.status + "message : " + e.responseText);
		  }
		});
			
	} else {

	//	$('#brdg_cost_1').text(breeding[rarity][Number(match1)-1]);
	//	$('#brdg_cost_2').text(breeding[rarity][Number(match2)-1]);
	//	const total_cost = breeding[rarity][Number(match1)-1] + breeding[rarity][Number(match2)-1];
	//	$('#brdg_total_cost').text(total_cost);
		
		$.ajax({
		  type:"POST",        
		  url:"/includes/proc_galaxyWallet.php",     
		  data : ({mode:"breeding", status:"failure", token_id: token_id1, token_id2: token_id2, cost:cost, game_code: game_code}),
		  timeout : 5000,  
		  cache : false,        
		  success: function whenSuccess(args){
			//	console.log(args);
			updateSN3();
				swal({
					text: 'Failure!\n\nNFT: '+token_id1+' and '+token_id2+'  breeding fail!!!\n\n',
					buttons: 'Confirm',
				})
				return;
		  },
		  error: function whenError(e){
			console.log("code : " + e.status + "message : " + e.responseText);
		  }
		});
	}
});

</script>