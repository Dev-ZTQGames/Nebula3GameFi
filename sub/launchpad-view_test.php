<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';

$idx = escape_string(trim($_REQUEST['idx']));

$query_lanuchpad = mysqli_query($connect, "SELECT * FROM GamesNFTLanuchpad WHERE m_index = '".$idx."'");
$info_lanuchpad = mysqli_fetch_array($query_lanuchpad);

?>

<script>
let once = true;

let totalSupplyNormalCustom;
let LoggedIn = false;

window.addEventListener('message', function(event) {
	if (allowedDomains.includes(event.origin)) {
		if ( (event.data.LoggedIn === true || event.data.LoggedIn === false) && once === true ) {
			LoggedIn = event.data.LoggedIn ? event.data.LoggedIn : LoggedIn;
			once = false;
			var iframe = document.getElementById('Internet_Identity');
			iframe.contentWindow.postMessage('totalSupplyNormal', '*');
		}
		if (event.data.NFT_totalSupply === true) {

			totalSupplyNormalCustom	= Number(event.data.totalSupplyNormalCustom);

			if (totalSupplyNormalCustom <= 2000) {
				var percent = totalSupplyNormalCustom / 2000 * 100;

				if ( percent != 0 ) {
					percent = percent.toFixed(2);
				}
				$("#mint_percent").html(percent+"% minted");
				$("#mint_total").html(totalSupplyNormalCustom.toLocaleString('en-US') + " / 2,000");
				$("#mint_bar").css("width",percent + "%");
				$('#mint_img').css('left', 'calc('+percent+'% - 17rem)');

			} else {

				$("#mint_percent").html("100.00% minted");
				$("#mint_total").html("2,000 / 2,000");
				$("#mint_bar").css("width","100%");
				$('#mint_img').css('left', 'calc(100% - 17rem)');

				totalSupplyNormalCustom -= 2000;
				var percent = totalSupplyNormalCustom / 3555 * 100;

				if ( percent != 0 ) {
					percent = percent.toFixed(2);
				}

				$("#FCFS_mint_percent").html(percent+"% minted");
				$("#FCFS_mint_total").html(totalSupplyNormalCustom.toLocaleString('en-US') + " / 3,555");
				$("#FCFS_mint_bar").css("width",percent + "%");
				$('#FCFS_mint_img').css('left', 'calc('+percent+'% - 17rem)');
			}

		}

		if (event.data.status === 'mint') {
			var result = event.data.result;

			if (result.hasOwnProperty('Ok')) {
				swal({
					text: 'Congratulations!\n\nYou succeeded in this project minting!!!\n\n',
					buttons: 'Confirm',
				}).then(function(){
					location.reload();
				});
			} else {
				swal({
					text: 'Failure!\n\nPlease try again later.\n\n',
					buttons: 'Confirm',
				}).then(function(){
					location.reload();
				});
			}

		}
	}
});
</script>

<div id="container" class="page-launchpad">
    <div class="article-body">
        <div class="wrap">
            <?php /* live project start */ 
			if ($info_lanuchpad['m_status'] == '2') {
			?>
            <div class="launchpad-view">
                <div class="launchpad-view__left">
                    <div class="launchpad-view__img">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="<?php echo $info_lanuchpad['m_project_img']; ?>" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/700x700/333/fff" alt="" /></noscript>
                        </figure>
                    </div>
                </div>
                <div class="launchpad-view__right">
                    <div class="launchpad-view__info">
                        <div class="launchpad-view__tit">
                            <figure class="launchpad-view__logo"><img src="<?php echo $info_lanuchpad['m_icon']; ?>" alt=""></figure>
                            <div class="launchpad-view__tit__info">
                                <h3><?php echo $info_lanuchpad['m_title'];?></h3>
                                <p class="goods"><i><img src="../assets/images/symbol-icp.svg" alt=""></i><span><?php echo $info_lanuchpad['m_chain'];?></span></p>
                            </div>
                        </div>
                        <ul class="sns-list">
							<?php
								if ($info_lanuchpad['m_web_url'] != "") {
							?>
                            <li class="sns-web"><a href="<?php echo $info_lanuchpad['m_web_url']; ?>" target="_blank"><span class="sr-only">web site</span></a></li>
							<?php
								}
								if ($info_lanuchpad['m_dc_url'] != "") {
							?>
                            <li class="sns-discord"><a href="<?php echo $info_lanuchpad['m_dc_url'];?>" target="_blank"><span class="sr-only">discord</span></a></li>
							<?php
								}
								if ($info_lanuchpad['m_x_url'] != "") {
							?>
                            <li class="sns-twitter"><a href="<?php echo $info_lanuchpad['m_x_url'];?>" target="_blank"><span class="sr-only">twitter</span></a></li>
							<?php
								}
								if ($info_lanuchpad['m_tg_url'] != "") {
							?>
                            <li class="sns-telegram"><a href="<?php echo $info_lanuchpad['m_tg_url'];?>" target="_blank"><span class="sr-only">telegram</span></a></li>
							<?php
								}
							?>
                        </ul>
                        <p class="desc">
                            <?php echo $info_lanuchpad['m_description']; ?>
                        </p>
                        <div class="launchpad-view__btn">
							<?php
							if ($_SESSION['sess_login_id'] != "") {
							?>
                            <a href="#mint-purchase-popup" class="btn-mint"><span>Mint</span></a>
							<?php
							} else {
							?>
							<a href="/sub/login.php"><span>Login</span></a>
							<?php
							}
							?>
                        </div>
                    </div>
                    
                    <div class="launchpad-view__mint-info">
                        <div class="launchpad-view__mint-info__item">
                            <h3>Guaranteed WL</h3>
                            <ul class="launchpad-view__mint-info__box">
                                <li>
                                    <b>Price</b>
                                    <p>Free</p>
                                </li>
                                <li>
                                    <b>Pieces</b>
                                    <p>3,333</p>
                                </li>
                                <li>
                                    <b>Limit</b>
                                    <p><span>1 pc per wallet</span></p>
                                </li>
                                <li>
                                    <b>Minting Date</b>
                                    <p><span>20 March 2024, 13:00-15:00 UTC</span></p>
                                </li>
                            </ul>
                            <div class="mint-progressbar">
                                <div class="mint-progressbar__box">
                                    <div class="progressbar">
                                        <figure id="mint_img"><img src="../assets/images/nft-bar-character.gif" alt=""></figure>
                                        <div class="progressbar-item" data-value="10" id="mint_bar"></div> 
                                    </div>
                                </div>
                                <ul>
                                    <li><span id="mint_percent">-% minted</span></li>
                                    <li><span id="mint_total">- / -</span></li>
                                </ul>
                            </div>
                        </div><!-- .launchpad-view__mint-info__item -->
                        
                        <div class="launchpad-view__mint-info__item">
                            <h3>FCFS WL</h3>
                            <ul class="launchpad-view__mint-info__box">
                                <li>
                                    <b>Price</b>
                                    <p>Free</p>
                                </li>
                                <li>
                                    <b>Pieces</b>
                                    <p>2,222</p>
                                </li>
                                <li>
                                    <b>Limit</b>
                                    <p><span>1 pc per wallet</span></p>
                                </li>
                                <li>
                                    <b>Minting Date</b>
                                    <p><span>20 March 2024, 13:00-15:00 UTC</span></p>
                                </li>
                            </ul>
                            <div class="mint-progressbar">
                                <div class="mint-progressbar__box">
                                    <div class="progressbar">
                                        <figure id="FCFS_mint_img"><img src="../assets/images/nft-bar-character.gif" alt=""></figure>
                                        <div class="progressbar-item" data-value="0" id="FCFS_mint_bar"></div> 
                                    </div>
                                </div>
                                <ul>
                                    <li><span id="FCFS_mint_percent">0% minted</span></li>
                                    <li><span id="FCFS_mint_total">0 / 3,555</span></li>
                                </ul>
                            </div>
                        </div><!-- .launchpad-view__mint-info__item -->
                    </div>
                </div><!-- .launchpad-view__right -->
            </div><!-- .launchpad-view -->
            <?php /* live project end */ 
			}	
			?>

            <?php /* upcoming project start */ 
			if ($info_lanuchpad['m_status'] == '1') {
			?>
            <div class="launchpad-view">
                <div class="launchpad-view__left">
                    <div class="launchpad-view__img">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="https://dummyimage.com/700x700/333/fff" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/700x700/333/fff" alt="" /></noscript>
                        </figure>
                    </div>
                </div>
                <div class="launchpad-view__right">
                    <div class="launchpad-view__info">
                        <div class="launchpad-view__tit">
                            <figure class="launchpad-view__logo"><img src="../assets/images/launchpad-logo-mm.png" alt=""></figure>
                            <div class="launchpad-view__tit__info">
                                <h3>Mining Maze: Get a big treasure box!</h3>
                                <p class="goods"><i><img src="../assets/images/symbol-icp.svg" alt=""></i><span>ICP</span></p>
                            </div>
                        </div>
                        <ul class="sns-list">
                            <li class="sns-web"><a href="#" target="_blank"><span class="sr-only">web site</span></a></li>
                            <li class="sns-discord"><a href="https://discord.com/invite/photonmilkyway" target="_blank"><span class="sr-only">discord</span></a></li>
                            <li class="sns-twitter"><a href="https://twitter.com/PhotonMilkyway" target="_blank"><span class="sr-only">twitter</span></a></li>
                            <li class="sns-telegram"><a href="https://t.me/photon_milkyway" target="_blank"><span class="sr-only">telegram</span></a></li>
                        </ul>
                        <p class="desc">
                            Embark on an adventure to a different dimension with animal friends! <br>
                            Have the collection and mining the PMW within short time bla bla
                        </p>
                    </div>
                    
                    <div class="launchpad-view__mint-info">
                        <div class="launchpad-view__mint-info__item">
                            <h3>NFT Launch Information</h3>
                            <ul class="launchpad-view__mint-info__box">
                                <li>
                                    <b>Price</b>
                                    <p>Free</p>
                                </li>
                                <li>
                                    <b>Pieces</b>
                                    <p>3,333</p>
                                </li>
                                <li>
                                    <b>Limit</b>
                                    <p><span>1 pc per wallet</span></p>
                                </li>
                                <li>
                                    <b>Minting Date</b>
                                    <p><span>20 March 2024, 13:00-15:00 UTC</span></p>
                                </li>
                            </ul>
                        </div><!-- .launchpad-view__mint-info__item -->
                    </div>
                </div><!-- .launchpad-view__right -->
            </div><!-- .launchpad-view -->
			<?php /* upcoming project end */
			}
			?>

            <?php /* previous project start */
			if ($info_lanuchpad['m_status'] == '3') {
			?>
            <div class="launchpad-view">
                <div class="launchpad-view__left">
                    <div class="launchpad-view__img">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="https://dummyimage.com/700x700/333/fff" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/700x700/333/fff" alt="" /></noscript>
                        </figure>
                        <p class="minting-result"><span>SOLD OUT</span></p>
                        <!--<p class="minting-result"><span>SALE ENDED</span></p>-->
                    </div>
                </div>
                <div class="launchpad-view__right">
                    <div class="launchpad-view__info">
                        <div class="launchpad-view__tit">
                            <figure class="launchpad-view__logo"><img src="../assets/images/launchpad-logo-mm.png" alt=""></figure>
                            <div class="launchpad-view__tit__info">
                                <h3>Mining Maze: Get a big treasure box!</h3>
                                <p class="goods"><i><img src="../assets/images/symbol-icp.svg" alt=""></i><span>ICP</span></p>
                            </div>
                        </div>
                        <ul class="sns-list">
                            <li class="sns-web"><a href="#" target="_blank"><span class="sr-only">web site</span></a></li>
                            <li class="sns-discord"><a href="https://discord.com/invite/photonmilkyway" target="_blank"><span class="sr-only">discord</span></a></li>
                            <li class="sns-twitter"><a href="https://twitter.com/PhotonMilkyway" target="_blank"><span class="sr-only">twitter</span></a></li>
                            <li class="sns-telegram"><a href="https://t.me/photon_milkyway" target="_blank"><span class="sr-only">telegram</span></a></li>
                        </ul>
                        <p class="desc">
                            Embark on an adventure to a different dimension with animal friends! <br>
                            Have the collection and mining the PMW within short time bla bla
                        </p>
                    </div>
                    
                    <div class="launchpad-view__mint-info">
                        <div class="launchpad-view__mint-info__item">
                            <h3>NFT Launch Information</h3>
                            <ul class="launchpad-view__mint-info__box">
                                <li>
                                    <b>Price</b>
                                    <p>Free</p>
                                </li>
                                <li>
                                    <b>Pieces</b>
                                    <p>3,333</p>
                                </li>
                                <li>
                                    <b>Limit</b>
                                    <p><span>1 pc per wallet</span></p>
                                </li>
                                <li>
                                    <b>Minting Date</b>
                                    <p><span>20 March 2024, 13:00-15:00 UTC</span></p>
                                </li>
                            </ul>
                        </div><!-- .launchpad-view__mint-info__item -->
                    </div>
                </div><!-- .launchpad-view__right -->
            </div><!-- .launchpad-view -->
			<?php /* previous project end */
			}
			?>

        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<div id="mint-purchase-popup" class="mint-purchase-popup mfp-hide">
    <div class="mint-purchase-popup__head">
        <h2>Mint</h2>
    </div><!-- .mint-purchase-popup__head -->
    <div class="mint-purchase-popup__body">
        <div class="project-info__wrap">
            <figure class="project-info__img"><img src="../assets/images/launchpad-logo-mm.png" alt=""></figure>
            <div class="project-info">
                <b>Mining Maze</b>
                <p><i><img src="../assets/images/symbol-icp.svg" alt=""></i><span>ICP</span></p>
            </div>
        </div><!-- .project-info -->
        <ul class="mint-purchase-info">
            <li>
                <b>Price</b>
                <p><i><img src="../assets/images/symbol-icp.svg" alt="ICP"></i> <span>0.00</span> ( free minting )</p>
            </li>
            <li>
                <b>Quantity</b>
                <div class="quantity">
                    <button class="btn-count count-down" disabled><span class="sr-only">-</span></button>
                    <p><input type="text" class="qty-input" value="1" id="mint_times" readonly></p>
                    <button class="btn-count count-up" disabled><span class="sr-only">+</span></button>
                </div><!-- .quantity -->
            </li>
        </ul>
        <div class="mint-total">
            <b>Total</b>
            <p><em>0 ETH</em> + gas fee</p>
        </div>
		<!--button type="button" class="btn-pruchase" id="button-mint"><span>TBA</span></button-->
        <button type="button" class="btn-pruchase" id="button-mint" onclick="go_Mint();"><span id="button_mint_span">Mint</span></button>
    </div><!-- .mint-purchase-popup__body -->
</div><!-- .mint-purchase-popup -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
var game_code = '<?php echo $info_lanuchpad['m_game_code']; ?>';

function go_Mint() { //	whiteList or not

	if(LoggedIn) {
		if (totalSupplyNormalCustom < 2000) {

			if ( !checkWhiteAddress(principalID, game_code, "WhitelistSale_Guaranteed") ) {
				alert("You are not WhiteList");
				location.reload();
				return false;
			}
			
		} else if (totalSupplyNormalCustom < 5555) {

			if ( !checkWhiteAddress(principalID, game_code, "WhitelistSale_FCFS") ) {
				alert("You are not WhiteList");
				location.reload();
				return false;
			}

		} else {
			return false;
		}
	} else {
		swal({
			text: 'please connect wallet\n\n or try again later',
			buttons: 'Confirm',
		});
		return false;
	}

	CountMint();

}

function CountMint() {
	const times = $('#mint_times').val();
	
	if (times == 1) {
		mintDip721();
	} else {
		swal({
			text: 'Failure!\n\nYou can only get 1 NFTs minting!!!\n\n',
			buttons: 'Confirm',
		}).then(function(){
			location.reload();
		});
	}
}

function mintDip721() {
	document.getElementById('button-mint').onclick = null;
	$("#button_mint_span").html("Processing...");
	console.log('mint');
	
	$.ajax({
	  type:"POST",        
	  url:"/includes/proc_galaxyWallet.php",     
	  data : ({mode:"mint", game_code: game_code}),
	  timeout : 5000,  
	  cache : false,        
	  success: function whenSuccess(args){
		//	console.log(args);

		switch(args.trim()){
			case("success"):
			swal({
				text: 'Congratulations!\n\nYou succeeded in this project minting!!!\n\n',
				buttons: 'Confirm',
			}).then(function(){
				location.reload();
			});
			break;
			case("failure"):
			swal({
				text: 'Failure!\n\nPlease try again later.\n\n',
				buttons: 'Confirm',
			}).then(function(){
				location.reload();
			});
			break;
		}
	  },
	  error: function whenError(e){
		console.log("code : " + e.status + "message : " + e.responseText);
	  }
	});
	
}

function checkWhiteAddress(address, game_code, step ) {

	address =  address.toLowerCase();

	for(var i=0; i<WhiteListAddress[game_code][step].length; i++){
		WhiteListAddress[game_code][step][i] = WhiteListAddress[game_code][step][i].toLowerCase();
		if( WhiteListAddress[game_code][step][i] === address ){
			console.log("checked : " + address);
			return true;
		}
	}

	return false;
}

</script>