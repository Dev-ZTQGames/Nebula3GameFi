<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';

$SyncAccount		= escape_string(trim($_REQUEST['ac']));

$query = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$_SESSION['sess_login_id']."' AND m_symbol = 'ICP'");
$info = mysqli_fetch_array($query);

if ( $info['m_account'] ) {
?>
	<script>location.href = "/";</script>
<?php
}
?>

<div id="container" class="page-sync-account">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title"><?php echo $lang['SYNCHRONIZEDACCOUNT']; ?></h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="sync-account">
                <b class="sync-account__title"><?php echo $lang['Nebulawallet']; ?></b>
                <div class="sync-account__dsec">
                    <p><?php echo $lang['EnterICP']; ?></p>
                    <p class="caution-message"><span>[<?php echo $lang['Caution']; ?>] <?php echo $lang['WalletAddressCantChanged']; ?></span></p>
                    <p><?php echo $lang['EnterAddress']; ?></p>
                </div>
                <input type="text" class="sync-account__input" id="account" value="<?php echo $SyncAccount; ?>">
                <button type="button" class="btn-basic btn-primary btn-sync-account" id="sync_account"><span><?php echo $lang['SyncWalletAddress']; ?></span></button>
            </div>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<script>

$("#sync_account").on('click',function(){
	const principal = $("#account").val();
	if (isValidPrincipal(principal)) {
	$.ajax({
	   type:"POST",        
	   url:"/includes/proc_galaxyWallet.php",     
	   data : ({mode:"ch0010",account:$("#account").val()}),
	   timeout : 5000,  
	   cache : false,        
	   success: function whenSuccess(args){
		switch(args.trim()){
			 case("sync_done"):
				 swal({
					text: "<?php echo $lang['SyncWalletsuccessfully']; ?>",
					buttons: "<?php echo $lang['Confirm']; ?>",
				 }).then(function(){
				 		location.href = "/";
				 		return false;
				 });
			 break;
			 case("already_used"):
				swal({
					text: "<?php echo $lang['WalletAddressUse']; ?>",
					buttons: "<?php echo $lang['Confirm']; ?>",
				}).then(function(){
						var iframe = document.getElementById('Internet_Identity');
						iframe.contentWindow.postMessage('logout', '*');
				});
			 break;
		  }
	   },
	   error: function whenError(e){
		console.log("<?php echo $lang['code']; ?> : " + e.status + "<?php echo $lang['message']; ?> : " + e.responseText);
	  }
	});
	} else {
		swal({
			text: "Format error",
			buttons: "<?php echo $lang['Confirm']; ?>",
		});
	}
});

function isValidPrincipal (input) {

	const regex = /^([a-z0-9]{5}-){10}[a-z0-9]{3}$/;

	return regex.test(input);
}

</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>