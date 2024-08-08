<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';

	$query_lanuchpad = mysqli_query($connect, "SELECT m_index, m_title, m_game_code, m_chain, m_main_banner_img, m_status, m_start FROM GamesNFTLaunchpad");
	
	$live = $upcoming = $prev = array();

	while ($row_lanuchpad = mysqli_fetch_array($query_lanuchpad)) {
		$info_lanuchpad = $row_lanuchpad;

		$timestamp = strtotime($info_lanuchpad['m_start']);
		$info_lanuchpad['year'] = substr(date("Y", $timestamp), 2, 3);
		$month = date("m", $timestamp);

		switch ($month) {
			case '01':
			case '02':
			case '03':
				$info_lanuchpad['season'] = 'Q1';
				break;
			case '04':
			case '05':
			case '06':
				$info_lanuchpad['season'] = 'Q2';
				break;
			case '07':
			case '08':
			case '09':
				$info_lanuchpad['season'] = 'Q3';
				break;
			case '10':
			case '11':
			case '12':
				$info_lanuchpad['season'] = 'Q4';
				break;
		}

		$query_games = mysqli_query($connect, "SELECT m_title FROM Games WHERE m_game_code = '".$info_lanuchpad['m_game_code']."'");
		$info_games = mysqli_fetch_array($query_games);
		$info_lanuchpad['m_title'] = $info_games['m_title'];

		if ($info_lanuchpad['m_status'] == '1') {
			$upcoming[] = $info_lanuchpad;
		} else if ($info_lanuchpad['m_status'] == '2') {
			$live[] = $info_lanuchpad;
		} else if ($info_lanuchpad['m_status'] == '3') {
			$prev[] = $info_lanuchpad;
		}
	}
?>

<div id="container" class="page-launchpad">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title">Launchpad</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="launchpad-wrap">
                <h2>Live Projects</h2>
				<?php
				if (empty($live)) {
				    echo '<p class="no-list"><span>Currently, no project data are available</span></p>';
				} else {
				    echo "<div class='nft-launchpad__list'>";
					foreach($live as $row) {
						echo '<div class="launchpad-item">
								<a href="launchpad-view.php?idx='.$row['m_index'].'">
								    <div class="launchpad-img">
								        <figure class="lazyload">
								            <img loading="lazy" data-unveil="'.$row['m_main_banner_img'].'" src="../assets/images/blank.gif" alt="" />
								            <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
								        </figure>
								        <div class="launchpad-date"><b>'.$row['year'].'</b><span>'.$row['season'].'</span></div>
								    </div><!-- .launchpad-img -->
								    <div class="launchpad-info">
								        <p class="goods">';
									if ($row['m_chain'] == 'ICP') echo '<i><img src="../assets/images/symbol-icp.svg" alt=""></i>';
									echo '<span>'.$row['m_chain'].'</span></p>
								        <h3>'.$row['m_title'].'  Minting</h3>
								    </div><!-- .launchpad-info -->
									</a>
								</div>';
					}
					echo "</div><!-- .nft-launchpad__list -->";
				}
				?>
            </div><!-- .launchpad-wrap -->

            <div class="launchpad-wrap">
                <h2>Upcoming Projects</h2>
				<?php
				if (empty($upcoming)) {
				    echo '<p class="no-list"><span>Currently, no project data are available</span></p>';
				} else {
				    echo "<div class='nft-launchpad__list'>";
					foreach($upcoming as $row) {
						echo '<div class="launchpad-item">
								'./*<a href="launchpad-view.php?idx='.$row['m_index'].'">*/'
								    <div class="launchpad-img">
								        <figure class="lazyload">
								            <img loading="lazy" data-unveil="'.$row['m_main_banner_img'].'" src="../assets/images/blank.gif" alt="" />
								            <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
								        </figure>
								        <div class="launchpad-date"><b>'.$row['year'].'</b><span>'.$row['season'].'</span></div>
								    </div><!-- .launchpad-img -->
								    <div class="launchpad-info">
								        <p class="goods">';
									if ($row['m_chain'] == 'ICP') echo '<i><img src="../assets/images/symbol-icp.svg" alt=""></i>';
									echo '<span>'.$row['m_chain'].'</span></p>
								        <h3>'.$row['m_title'].'  Minting</h3>
								    </div><!-- .launchpad-info -->
									'./*</a>*/'
								</div>';
					}
					echo "</div><!-- .nft-launchpad__list -->";
				}
				?>
            </div><!-- .launchpad-wrap -->

            <div class="launchpad-wrap">
                <h2>Previous Projects</h2>
                <?php
				if (empty($prev)) {
				    echo '<p class="no-list"><span>Currently, no project data are available</span></p>';
				} else {
				    echo "<div class='nft-launchpad__list'>";
					foreach($prev as $row) {
						echo '<div class="launchpad-item">
								<a href="launchpad-view.php?idx='.$row['m_index'].'">
								    <div class="launchpad-img">
								        <figure class="lazyload">
								            <img loading="lazy" data-unveil="'.$row['m_main_banner_img'].'" src="../assets/images/blank.gif" alt="" />
								            <noscript><img loading="lazy" src="https://dummyimage.com/488x275/333/fff" alt="" /></noscript>
								        </figure>
								        <div class="launchpad-date"><b>'.$row['year'].'</b><span>'.$row['season'].'</span></div>
								    </div><!-- .launchpad-img -->
								    <div class="launchpad-info">
								        <p class="goods">';
									if ($row['m_chain'] == 'ICP') echo '<i><img src="../assets/images/symbol-icp.svg" alt=""></i>';
									echo '<span>'.$row['m_chain'].'</span></p>
								        <h3>'.$row['m_title'].'  Minting</h3>
								    </div><!-- .launchpad-info -->
									</a>
								</div>';
					}
					echo "</div><!-- .nft-launchpad__list -->";
				}
				?>
            </div><!-- .launchpad-wrap -->

        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>