<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<div id="container" class="page-find-pw">
    <div class="article-header">
        <div class="article-header__inner wrap-narrow">
            <h2 class="article-title">Forget the password</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap-narrow">
            <div class="find-user-info">
                <div class="form-field-wrap">
                    <div class="form-field-item required">
                        <label class="form-label" for="find-id"><span>ID</span></label>
                        <div class="form-input__wrap">
							<input type="hidden" id="idchk_commit" value="">
                            <input type="text" class="form-input" id="find-id" required>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item form-email required">
                        <label class="form-label" for="find-email"><span>E-Mail</span></label>
                        <div class="form-input__wrap">
                            <div class="form-input__inner">
								<input type="hidden" id="emailchk_commit" value="">
                                <input type="text" class="form-input" id="find-email" required>
                            </div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
            </div>
            <div class="btn-wrap center btn-confirm"><a href="javascript:void(0);" onclick="check_find_pw();" class="btn-basic btn-primary">OK</a></div>
        </div><!-- .wrap-narrow -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>

function isValidLoginId(id) {
	var check = /^(?=.*[a-zA-Z]|[0-9]).{5,16}$/;

	if (!check.test(id))     {
		return false;
	}

	if (id.length < 5 || id.length > 16) {
		return false;
	}

	return true;
};

function isValidEmail(email) {
	var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

	if(regex.test(email) == false) {
		return false;
	}

	return true;
};

$("#find-id").keyup(function(){
	$.ajax({
	   type:"POST",        
	   url:"/extern/sso/login_validate_id.php",     
	   data : ({id: $("#find-id").val() }),
	   timeout : 5000,  
	   cache : false,        
	   success: function whenSuccess(args){
		switch(args.trim()){
			 case("true"):
			  $("#idchk_commit").val("Y");
			 break;
			 case("none"):
			  $("#idchk_commit").val("N");
			 break;
			 case("short"):
			  $("#idchk_commit").val("N");
			 break;
			 case("long"):
			  $("#idchk_commit").val("N");
			 break;
			 case("bad_character"):
			  $("#idchk_commit").val("N");
			 break;
			 case("bad_character2"):
			  $("#idchk_commit").val("N");
			 break;
		  }
	   },
	   error: function whenError(e){
		//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
	  }
	});
})

$("#find-email").keyup(function(){
	var email = $("#find-email").val();

	$.ajax({
		   type:"POST",       
		   url:"/extern/sso/find_validate_email.php",   
		   data : ({email: email}),
		   timeout : 5000,     
		   cache : false,       
		   success: function whenSuccess(args){  
			if(args == "true"){
				$("#emailchk_commit").val("Y");
			} else {
				$("#emailchk_commit").val("N");
			}
		   },
		   error: function whenError(e){    // ERROR FUNCTION
			//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
		   }
	  });
})

function check_find_pw(){
	var id = $("#find-id").val();
	var email = $("#find-email").val();
	var chk_login_id = $("#idchk_commit").val();
	var chk_email_id = $("#emailchk_commit").val();

	if ( id == "" || !isValidLoginId(id) || chk_login_id != "Y" ) {
			swal({
				text: '<?php echo $lang['ID_Check']; ?>',
				buttons: '<?php echo $lang['Confirm']; ?>',
			})	
			$('#find-id').focus();
		} else if (email == "" || !isValidEmail(email) || chk_email_id != "Y" ) {
			swal({
				text: '<?php echo $lang['Email_Check']; ?>',
				buttons: '<?php echo $lang['Confirm']; ?>',
			})	
			$('#find-email').focus();
		} else {
			$.ajax({
			   type:"POST",       
			   url:"/extern/sso/check_find_pw.php",   
			   data : ({email: email,id:id}),
			   timeout : 5000,     
			   cache : false,
			   beforeSend: function (){
			//	showLoadingBar();
			   },
			   success: function whenSuccess(args){  
				   if(args.includes('incorrect')){
					   loading();
						swal({
							text: '<?php echo $lang['Not_Match_Info']; ?>',
							buttons: '<?php echo $lang['Confirm']; ?>',
						})					
				   } else if(args.includes('fail')){
						loading();
						swal({
							text: '<?php echo $lang['Not_Exist_ID']; ?>',
							buttons: '<?php echo $lang['Confirm']; ?>',
						})					
				   } else if(args.includes('ok')){
			//		showLoadingBar();
					go_search(id,email);
				   } else {
			//		   loading();
						swal({
							text: '<?php echo $lang['Not_Valid_Email']; ?>',
							buttons: '<?php echo $lang['Confirm']; ?>',
						})							
				   }
			   },

		  });	
			
		}	
}

function go_search(id,email) {
	var id = id;
	var email = email;

	$.ajax({
	   type:"POST",       
	   url:"/extern/sso/sendmail_find_pw.php",   
	   data : ({email: email,id:id}),
	   timeout : 5000,     
	   cache : false,
	   beforeSend: function (){
	//	showLoadingBar();
	   },
	   complete: function (){
	//	loading();
		swal({
			text: '<?php echo $lang['Send_Email']; ?>',
			buttons: '<?php echo $lang['Confirm']; ?>',
		})	
	   },
  });	
	
}

</script>