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
            <h2 class="article-title">SYNCHRONIZED ACCOUNT</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="sync-account">
                <b class="sync-account__title">Nebula wallet</b>
                <div class="sync-account__dsec">
                    <p>Please enter your ICP Mainnet wallet address.</p>
                    <p class="caution-message"><span>[Caution] Once synchronized, the wallet address cannot be changed.</span></p>
                    <p>※ Please enter the address carefully to ensure proper synchronization.</p>
                </div>
                <input type="text" class="sync-account__input" id="account" value="<?php echo $SyncAccount; ?>">
                <button type="button" class="btn-basic btn-primary btn-sync-account" id="sync_account"><span>Sync Wallet Address</span></button>
            </div>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<script>

$("#sync_account").on('click',function(){
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
					text: "Sync wallet successfully!",
					buttons: "<?php echo $lang['Confirm']; ?>",
				 }).then(function(){
				 		location.href = "/";
				 		return false;
				 });
			 break;
			 case("already_used"):
				swal({
					text: "This wallet address is already in use!",
					buttons: "<?php echo $lang['Confirm']; ?>",
				}).then(function(){
						location.href = "/";
						return false;
				});
			 break;
		  }
	   },
	   error: function whenError(e){
		console.log("code : " + e.status + "message : " + e.responseText);
	  }
	});
});

</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>