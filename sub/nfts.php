<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';


	$que = mysqli_query($connect, "SELECT m_token_id, m_level, m_breeding FROM AccountsNFTData"); 
	$list = array();
	while( $row = mysqli_fetch_array($que) ) {
		$list[] = $row;
	}
	
	$info = json_encode($list);
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
			const data = <?php echo $info?>;

			totalSupplyNormalCustom		= Number(event.data.totalSupplyNormalCustom);
			totalSupplyRareCustom		= Number(event.data.totalSupplyRareCustom) + 5556;
			totalSupplyEpicCustom		= Number(event.data.totalSupplyEpicCustom) + 7778;
			totalSupplyUniqueCustom		= Number(event.data.totalSupplyUniqueCustom) + 8889;
			totalSupplyLegendaryCustom	= Number(event.data.totalSupplyLegendaryCustom) + 9445;

			const game_code = "mm";
			
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
												'<img loading="lazy" src="https://<?php echo $HOST;?>.nebula3gamefi.com/img_remote/test/game/mm/' + rarity.toLowerCase() +'.png" alt="" class="lazyload--loaded" style="z-index:1">'+
										        '<img loading="lazy"  src="https://cdn.aurorahunt.xyz/nft/mm/img/' + id + '.png" alt="" />' +
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

<div id="container" class="page-nfts">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title">NFTs</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="nfts-filter-popup-open__wrap"><a href="#nfts-filter-popup" class="nfts-filter-popup-open"><span>Filter</span></a></div>
            <div class="nfts-info__wrap">
                <div class="nfts-filter-popup__bg"></div>
                <div id="nfts-filter-popup" class="nfts-filter-popup">
                    <div class="nfts-filter-popup__head">
                        <h2>Filter</h2>
                        <button type="button" class="nfts-filter-popup__close"><span class="sr-only">Close</span></button>
                    </div><!-- .nfts-filter-popup__head -->
                    <div class="nfts-filter-popup__body">
                        <div class="nfts-filter">
                            <div class="nfts-filter__list">
                                <div class="nfts-filter__item nfts-filter__item--active">
                                    <button type="button" class="nfts-filter__button"><span>Games</span></button>
                                    <div class="nfts-filter__content">
                                        <ul>
                                            <li><label class="custom-checkbox">Mining Maze<input type="checkbox" class="categoryCheckbox" value="mm"><span class="checkmark"></span></label></li>
                                            <!--li><label class="custom-checkbox">Claw Machine<input type="checkbox" class="categoryCheckbox" value="clwmc"><span class="checkmark"></span></label></li-->
                                        </ul>
                                    </div><!-- .nfts-filter__content -->
                                </div><!-- .nfts-filter__item -->
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
                                                <input class="nfts-search__input" id="search" name="search" type="text" value="" placeholder="Search by number">
                                            </label><!-- .nfts-search__label -->
                                            <button class="nfts-search__submit"><span class="sr-only">검색</span></button>
                                        </div>
                                    </div><!-- .nfts-filter__content -->
                                </div><!-- .nfts-filter__item -->
                            </div><!-- .nfts-filter__list -->
                        </div><!-- .nfts-filter -->
                        <button type="button" class="btn-basic btn-primary nfts-filter-popup__confirm"><span>OK</span></button>
                    </div><!-- .nfts-filter-popup__body -->
                </div><!-- .nfts-filter-popup -->

                <div class="sub-nft-list">

                    <!--div class="nft-item">
                        <figure class="lazyload">
                            <img loading="lazy" data-unveil="https://dummyimage.com/282x282/333/fff" src="../assets/images/blank.gif" alt="" />
                            <noscript><img loading="lazy" src="https://dummyimage.com/282x282/333/fff" alt="" /></noscript>
                        </figure>
                        <div class="main-nfts__info nft-info">
                            <h3 class="nft-info__title">Mining Maze</h3>
                            <ul>
                                <li><span>Legend</span><span>#001</span></li>
                                <li><span>Breeding Count</span><span>(0/5)</span></li>
                                <li><span>Current Level</span><span>(0/5)</span></li>
                            </ul>
                        </div>
                    </div><!-- .nft-item -->
                    
                </div><!-- .nft-list -->
            </div>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->
<?php /* 제거
<div id="nfts-filter-popup" class="nfts-filter-popup mfp-hide">
    <div class="nfts-filter-popup__head">
        <h2>Filter</h2>
    </div><!-- .nfts-filter-popup__head -->
    <div class="nfts-filter-popup__body">
        <button type="button" class="btn-basic btn-primary nfts-filter-popup__confirm"><span>OK</span></button>
    </div><!-- .nfts-filter-popup__body -->
</div><!-- .nfts-filter-popup -->*/ ?>

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

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>