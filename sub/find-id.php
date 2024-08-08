<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="page-find-id">
    <div class="article-header">
        <div class="article-header__inner wrap-narrow">
            <h2 class="article-title">Forget your account</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap-narrow">
            <div class="find-user-info">
                <div class="form-field-wrap">
                    <div class="form-field-item form-email required">
                        <label class="form-label" for="join-email"><span>E-Mail</span></label>
                        <div class="form-input__wrap">
                            <div class="form-input__inner">
                                <input type="text" class="form-input" id="join-email" required>
                            </div>
                            <p class="valid-message valid-message--info"><i class="icon"></i><span>You need to enter an E-Mail for an account.</span></p>  
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
            </div>
            <div class="btn-wrap center btn-confirm"><a href="#" class="btn-basic btn-primary" onclick="check_find_id();">OK</a></div>
        </div><!-- .wrap-narrow -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
function check_find_id(){
	var email = $("#join-email").val();

	if (email == "" || !isValidEmail(email)) {
		swal({
			text: '<?php echo $lang['Email_Check']; ?>',
			buttons: '<?php echo $lang['Confirm']; ?>',
		})			
		$('#email').focus();

	} else {
			$.ajax({
			   type:"POST",       
			   url:"/extern/sso/find_validate_email.php",   
			   data : ({email: email}),
			   timeout : 5000,     
			   cache : false,   
			   beforeSend: function (){
			//	showLoadingBar();
			   }, 
			   success: function whenSuccess(args){  
				   //console.log(args);
				   if(args.includes('noId')){
				//	   loading();
						swal({
							text: '<?php echo $lang["No_Account"]; ?>',
							buttons: '<?php echo $lang["Confirm"]; ?>',
						})					
				   } else {
				//		showLoadingBar();
						go_search(email);
				   } 
			   },
		  });
	}	
}

function go_search(email) {
	var email = email;

	$.ajax({
	   type:"POST",       
	   url:"/extern/sso/sendmail_find_id.php",   
	   data : ({email: email}),
	   timeout : 5000,     
	   cache : false,   
	   complete: function (){
	//	loading();
		swal({
			text: '<?php echo $lang["Send_Email"]; ?>',
			//text: 'ko<br>ko',
			buttons: '<?php echo $lang["Confirm"]; ?>',
		})	
	   },
  });
	
}

function isValidEmail(email) {
	var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

	if(regex.test(email) == false) {
		return false;
	}

	return true;
}

</script>