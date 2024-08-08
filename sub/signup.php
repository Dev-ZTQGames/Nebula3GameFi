<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';

if ($_SESSION['sess_login_id'] != "") {
?>
	<script>
	swal({
	  title: 'Invalid access.',
	  text: '<?php echo "Please log out and try again."; ?>',
	  buttons: '<?php echo $lang['Confirm']; ?>',
	}).then(function(){
		location.href='/';
	})
	</script>
<?php
}

$return_path =escape_string(trim($_REQUEST['return_path']));
$query = escape_string(trim($_REQUEST['query']));
$user_name =  escape_string(trim($_REQUEST['register_name']));
$user_id = escape_string(trim($_REQUEST['register_id']));
$user_pw = escape_string(trim($_REQUEST['register_pw']));
$user_email = escape_string(trim($_REQUEST['register_email']));
$social_id = escape_string(trim($_REQUEST['social_id']));

$referral = escape_string(trim($_REQUEST['referral']));
$recaptcha = escape_string(trim($_REQUEST['g-recaptcha']));
$recaptchachk = escape_string(trim($_REQUEST['g-recaptchachk_commit']));

if($query == "join_process"){

	//보안 강화 끝
	$data = array('id' => $user_id);
	$url = "https://" . $HOST . ".nebula3gamefi.com/extern/sso/join_validate_id.php";
	$return_data = curl_post($url, $data);
	if ( $return_data != "true" ) exit;

	$data = array('email' => $user_email);
	$url = "https://" . $HOST . ".nebula3gamefi.com/extern/sso/join_validate_email.php";
	$return_data = curl_post($url, $data);
	if ( $return_data != "true" ) exit;

	$data = array('name' => $user_name);
	$url = "https://" . $HOST . ".nebula3gamefi.com/extern/sso/join_validate_name.php";
	$return_data = curl_post($url, $data);
	if ( $return_data != "true" ) exit;
	//보안 강화 끝

	$join_query = "INSERT INTO Accounts (login_id,login_pw,email,email_cert,createdAt,usn,name,lang_code,ip) VALUES ('".$user_id."',PASSWORD('".$user_pw."'),'".$user_email."','y',now(),(SELECT max(usn)+1 FROM Accounts account),'".$user_name."','".$lang_code."', '".$ip."')";

	if(!mysqli_query($connect,$join_query)){
		echo "<script>alert('".mysqli_error($connect)."');location.href = 'signup.php'; ;</script>";
	};

	if ( $referral && $recaptchachk ) {
		
		$secretKey = "6LesuzsnAAAAAHCm0GqLwlhQnNwZEIaxyj-9eu4p";
		$data = array(
		  'secret' => $secretKey,
		  'response' => $recaptcha,
		  'remoteip' => $_SERVER['REMOTE_ADDR']
		);

		$url = "https://www.google.com/recaptcha/api/siteverify";
		$return_data =  json_decode(curl_post($url, $data), true);

		if ($return_data["success"]) {
		  $referral_query = "INSERT INTO AccountsReferral (m_login_id,m_referral,m_date) VALUES ('".base64_decode($referral)."', '".$user_id."', now())";
		  mysqli_query($connect,$referral_query);
		  $msg_referral = $lang['Referral_Success'];
		} else {
		  $msg_referral = $lang['Referral_Failure'];
		}
		
		$msg_success = $lang["Welcome_Registration_Complete"] . "\\n\\n" . $msg_referral;
	}
	else {
		$msg_success = $lang["Welcome_Registration_Complete"];
	}

//	print_r($return_data);
//	var_dump($return_data);
//	echo $msg_success;
//	exit;

	mysqli_close($connect);

?>
	<form name="go_login" action="login.php" method="POST">
		<input type="hidden" name="return_path" value="<?php echo $return_path; ?>">
		<input type="hidden" name="id" value="<?php echo $user_id; ?>">
	</form>
	<script>
		swal({
		  text: '<?php echo $user_name?> '+'<?php echo $msg_success; ?>',
		  buttons: '<?php echo $lang["Registration_Login"]; ?>',
		}).then(function(isConfirm){
			if(isConfirm){
				document.go_login.submit();
			}
		});
	</script>
<?php
}
?>

<div id="container" class="page-signup">
    <div class="article-header">
        <div class="article-header__inner wrap-middle">
            <h2 class="article-title">Sign up</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap-middle">
            <div class="join-form__head">
                <h3 class="join-form__title">Please enter membership information</h3>
                <p class="join-form__guide"><span><i>*</i>Marked section is mandatory, so please fill it out.</span></p>
            </div><!-- .join-form__title -->
            <div class="join-form">

			<form id="register_form" name="register_form" action="signup.php" method="POST">
				<input type='hidden' name='query' id='query' value='join_process'>
				<input type="hidden" name="namechk_commit" id="namechk_commit" value="">
				<input type="hidden" name="idchk_commit" id="idchk_commit" value="">
				<input type="hidden" name="passwdchk_commit" id="passwdchk_commit" value="">
				<input type="hidden" name="emailchk_commit" id="emailchk_commit" value="">
				<input type="hidden" name="social_id" id="social_id" value="<?php echo $social_chk_id; ?>">
				<input type="hidden" name="type" id="type" value="<?php echo $register_type; ?>">
				<input type="hidden" name="return_path" id="return_path" value="<?php echo $return_path; ?>">
				<input type="hidden" name="g-recaptcha" id="g-recaptcha" value="">
				<input type="hidden" name="g-recaptchachk_commit" id="g-recaptchachk_commit" value="">

                <div class="form-field-wrap">
                    <div class="form-field-item required">
                        <label class="form-label" for="register_name"><span>Nickname</span></label>
                        <div class="form-input__wrap">
                            <input type="text" class="form-input" id="register_name" name="register_name" required placeholder="Please enter within 20 words excluding special characters.">
                            <div id="valid_message_name"><!--p class="valid-message valid-message--error"><i class="icon"></i><span>error message</span></p--></div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item required">
                        <label class="form-label" for="register_id"><span>ID</span></label>
                        <div class="form-input__wrap">
                            <input type="text" class="form-input" id="register_id" name="register_id" required placeholder="Please enter combination of 8~16 characters and numbers">
                            <div id="valid_message_id"><!--p class="valid-message valid-message--confirm"><i class="icon"></i><span>confirm message</span></p--></div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item required">
                        <label class="form-label" for="join-password"><span>Password</span></label>
                        <div class="form-input__wrap">
                            <input type="password" class="form-input" id="register_pw" name="register_pw" onkeyup="check_passwd(this.value)" required placeholder="Please enter combination of 8~16 characters and numbers">
							<div id="valid_message_pw"></div>
						</div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item required">
                        <label class="form-label" for="join-password-check"><span>Confirm your password</span></label>
                        <div class="form-input__wrap">
                            <input type="password" class="form-input" id="register_pw_check" name="register_pw_check" required>
							<div id="valid_message_check_pw"></div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item form-email required">
                        <label class="form-label" for="register_email"><span>E-Mail</span></label>
                        <div class="form-input__wrap">
                            <div class="form-input__inner">
                                <input type="text" class="form-input" id="register_email" name="register_email" required>
                                <button type="button" class="btn-basic btn-primary btn-email-verify disabled" id="send_email_verify" onclick="email_verify();"><span>Verify your email</span></button>
                            </div>
                            <div id="valid_message_email"><p class="valid-message valid-message--info"><i class="icon"></i><span>You need an E-Mail to access to Nebula.</span></p></div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item required form-email-vertify" style="display:none">
                        <label class="form-label" for="email_vertify"><span>E-Mail verification code</span></label>
                        <div class="form-input__wrap">
                            <div class="form-input__inner">
                                <div class="email-vertify__input">
                                    <input type="text" class="form-input input-email-vertify" id="email_vertify" onkeyup="check_email_cert_number();">
                                    <input type="hidden" name="emailchk_cert_num" id="emailchk_cert_num">
                                    <span id="timer">0:00</span>
                                </div>
                                <button type="button" class="btn-basic btn-primary" id="btn_cert" onclick="email_cert_number_confirm();"><span>Verify</span></button>
                                <button type="button" class="btn-basic btn-email-verify disabled" id="resend_email_verify"><span>Verification sent</span></a>
                            </div><!-- .form-input__inner -->
                        </div><!-- .form-input__wrap -->
                    </div>
                </div><!-- .form-field-wrap -->
                <div class="form-field-wrap">
                    <div class="form-field-item form-referral">
                        <label class="form-label" for="join-referral"><span>My referral</span></label>
                        <div class="form-input__wrap">
                            <div class="form-input__inner">
                                <input type="text" class="form-input" id="join-referral" name="referral" value="<?php echo $referral; ?>">
                                <!--button type="button" class="btn-basic btn-primary"><span>Verify</span></button-->
                            </div>
                        </div><!-- .form_input_wrap -->
                    </div><!-- .form-field-item -->
                </div><!-- .form-field-wrap -->

			</form>

            </div><!-- .join-form -->
            <div class="btn-wrap center btn-confirm"><a href="#" class="btn-basic btn-primary" onclick="join_check();">Sign up</a></div>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>
	function isValidName(name) {
		var check = /[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/;

		if (check.test(name))     {
			return false;
		}

		if (name.length > 20) {
			return false;
		}

		return true;
	};


	function isValidLoginId(id) {
		var check = /^(?=.*[a-zA-Z])(?=.*[0-9]).{8,16}$/;

		if (!check.test(id))     {
			return false;
		}

		if (id.length < 8 || id.length > 16) {
			return false;
		}

		return true;
	};

	function isValidPassword(pw) {
	//	var check = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/;
		var check = /^(?=.*[a-zA-Z])(?=.*[0-9]).{8,16}$/;

		if (!check.test(pw))     {
			return false;
		}

		if (pw.length < 8 || pw.length > 16) {
			return false;
		}

		return true;
	};

/*
	function isValidEmail(email) {
		var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

		if(regex.test(email) == false) {
			return false;
		}

		return true;
	};
*/

	function isValidEmail(email) {
	    const allowedDomains = ['gmail.com'];

	    const pattern = /^([\w-]+(?:\.[\w-]+)*)@([\w-]+\.\w{2,})(?:\.[a-z]{2})?$/;

	    
	    const match = email.match(pattern);
	    if (match) {
	        
	        const domain = match[2] + (match[3] ? '.' + match[3] : '');
	        
	        return allowedDomains.includes(domain);
	    }
	    return false;
	}

	function join_check(){
		var join_name = $.trim($("#register_name").val());
		var join_id = $.trim($("#register_id").val());
		var join_pw = $.trim($("#register_pw").val());
		var check_pw = $.trim($("#register_pw_check").val());
		var email = $.trim($("#register_email").val());

		if (join_name == "" || !isValidName(join_name) ) {
			swal({
			  text: '<?php echo $lang['Check_Nickname']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		}

		if (join_id == "" || !isValidLoginId(join_id)) {
			 swal({
			  text: '<?php echo $lang['ID_Check']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			 return false;
		} 

		if (join_pw == "" || !isValidPassword(join_pw)) {
			swal({
			  text: '<?php echo $lang['Please_Confirm_Password']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		} 

		if (join_pw != check_pw) {
			swal({
			  text: '<?php echo $lang['Password_Not_Match']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		}

		if (email == "" || !isValidEmail(email)) {
			swal({
			  text: '<?php echo $lang['Email_Check']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		} 		
		
		if ( $("#idchk_commit").val() != "Y" ) {
			swal({
			  text: '<?php echo $lang['ID_Check']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		} 

		if (  $("#emailchk_commit").val() != "Y" ) {
			swal({
			  text: '<?php echo $lang['Check your email verification']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return false;
		}

		document.register_form.submit();
	   
	}

	$("#register_name").keyup(function(){
		$.ajax({
		   type:"POST",        
		   url:"/extern/sso/join_validate_name.php",     
		   data : ({name: $("#register_name").val() }),
		   timeout : 5000,  
		   cache : false,        
		   success: function whenSuccess(args){
			$("#namechk_commit").val("");
			switch(args.trim()){
				 case("true"):
				  $("#namechk_commit").val("Y");
				  $("#valid_message_name").html("<p class=' valid-message valid-message--confirm'><i class='icon'></i><span><?php echo $lang['Available_Nickname']; ?></span></p>");
				 break;
				 case("false"):			  				 
				  $("#valid_message_name").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Nickname_Already_Exists']; ?></span></p>");
				 break;
				 case("none"):
				  $("#valid_message_name").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Enter_Nickname']; ?></span></p>");
				 break;
				 case("long"):
				   $("#valid_message_name").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Within_20_Characters']; ?></span></p>");
				 break;
				 case("bad_character"):
				   $("#valid_message_name").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Within_20_Characters']; ?></span></p>");
				 break;
			  }
		   },
		   error: function whenError(e){
			//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
		  }
		});
	});


	$("#register_id").keyup(function(){
		$.ajax({
		   type:"POST",        
		   url:"/extern/sso/join_validate_id.php",     
		   data : ({id: $("#register_id").val() }),
		   timeout : 5000,  
		   cache : false,        
		   success: function whenSuccess(args){
			console.log(args);
			$("#idchk_commit").val("");
			switch(args.trim()){
				 case("true"):
				  $("#idchk_commit").val("Y");
				  $("#valid_message_id").html("<p class='valid-message valid-message--confirm'><i class='icon'></i><span><?php echo $lang['Available_ID']; ?></span></p>");
				 break;
				 case("false"):			  				 
				  $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Exist_ID']; ?></span></p>");
				 break;
				 case("none"):
				  $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Enter_Using_ID']; ?></span></p>");
				 break;
				 case("short"):
				   $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Valid_Message_Id_Possible']; ?></span></p>");
				 break;
				 case("long"):
				   $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Valid_Message_Id_Possible']; ?></span></p>");
				 break;
				 case("bad_character"):
				   $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Valid_Message_Id_Possible']; ?></span></p>");
				 break;
				 case("bad_character2"):
				   $("#valid_message_id").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Valid_Message_Id_Possible']; ?></span></p>");
				 break;
			  }
		   },
		   error: function whenError(e){
			//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
		  }
		});
	});


	function check_passwd(passwd) {
		if ( passwd.length >= 0 )	{

			if (passwd == "" || !isValidPassword(passwd)) {
				$("#passwdchk_commit").val("N");
				$("#valid_message_pw").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Alphanumeric_Combinations']; ?></span></p>");
				return false;
			}

			$.ajax({
				type: "POST",
				url: "/extern/sso/join_validate_pw.php",
				data : ({ pw: passwd }),
				cache: false,
				success: function(args){
					if(args.trim().includes("bad_pw")){
						$("#valid_message_pw").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Alphanumeric_Combinations']; ?></span></p>");
						$("#pw_commit").val("N");
					}
					else {
						$("#passwdchk_commit").val("Y");
						$("#valid_message_pw").html("<p class='valid-message valid-message--confirm'><i class='icon'></i><span><?php echo $lang['Available_Passwords']; ?></span></p>");
					}

				}
			});
		}
	}

	//비밀번호
	$("#register_pw").keyup(function(){
		if($('#register_pw').val() != $('#register_pw_check').val() || $('#register_pw_check').val() == ''){
			$("#valid_message_check_pw").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Password_Not_Match']; ?></span></p>");
		} else {
			$("#valid_message_check_pw").html("<p class='valid-message valid-message--confirm'><i class='icon'></i><span><?php echo $lang['Password_Match']; ?></span></p>");
		}
	});

	//비밀번호 확인
	$("#register_pw_check").keyup(function(){
		if($('#register_pw').val() != $('#register_pw_check').val() || $('#register_pw_check').val() == ''){
			$("#valid_message_check_pw").html("<p class='valid-message valid-message--error'><i class='icon'></i><span><?php echo $lang['Password_Not_Match']; ?></span></p>");
		} else {
			$("#valid_message_check_pw").html("<p class='valid-message valid-message--confirm'><i class='icon'></i><span><?php echo $lang['Password_Match']; ?></span></p>");
		}
	});



	$("#register_email").keyup(function(){
		var email = $.trim($("#register_email").val());
		if($("#register_email").val() != '' ){
			$("#send_email_verify").removeClass('disabled');
			/*
			$.ajax({
				   type:"POST",       
				   url:"https://<?php echo $HOST; ?>.nebula3gamefi.com/extern/sso/join_validate_email.php",   
				   data : ({email: email}),
				   timeout : 5000,     
				   cache : false,       
				   success: function whenSuccess(args){  
					$("#send_email_verify").removeClass('disabled');
					switch(args.trim()){
					 case("true"):
					  $("#valid_error_msg").html("");
					 break;
					 case("none"):
					   $("#valid_error_msg").html("<i class='icon'></i><span>이메일을 입력해주세요.</span>");
					   $("#send_email_verify").addClass('disabled');
					 break;
					}
				   },
				   error: function whenError(e){    // ERROR FUNCTION
					//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
				   }
			  });*/
			} else {
				$("#send_email_verify").addClass('disabled');
			}
	});

	function email_verify(){

		var email = $.trim($("#register_email").val());

		if ( !isValidEmail(email) ) {
			swal({
			  text: '<?php echo $lang['Email_Check']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			return;
		}
		
		$.ajax({
		   type:"POST",       
		   url:"/extern/sso/join_validate_email.php",   
		   data : ({email: email}),
		   timeout : 5000,     
		   cache : false,
		   success: function whenSuccess(args){  
			switch(args.trim()){
				case("true"):
					send_mail();
					break;
				case("false"):
					swal({
						text: email+', <?php echo $lang['Signed_Up_Same_Email']; ?>',
						buttons: {
							cancel : '<?php echo $lang['Cancel']; ?>',
							confirm : {
								text : '<?php echo $lang['Confirm']; ?>',
								value : 'catch'
							},
						},
					}).then(function(value){
						if(value == 'catch'){
							location.href='/sub/login.php?return_path=<?php echo $return_url; ?>';
						} else {
							$("#send_email_verify").removeClass('disabled');
						}
					})
				 break;
				case("bad_character"):
					swal({
					  text: '<?php echo $lang['Email_Check']; ?>',
					  buttons: '<?php echo $lang['Confirm']; ?>',
					})
					$("#send_email_verify").removeClass('disabled');
					break;
			}
		   },
		   error: function whenError(e){    // ERROR FUNCTION
			swal({
			  text: '<?php echo $lang['Try_Again_Later']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
		   }
	  });
		
	}

	function rand(min, max) {
	  return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	function send_mail(){
		$('#register_email').prop('readonly', true);
		$('#send_email_verify').addClass('disabled');
		var email = $.trim($("#register_email").val());
		var num = rand(000000, 999999);

		$.ajax({
		   type:"POST",       
		   url:"/extern/sso/sendmail_certificate.php",   
		   data : ({email: email, num:num}),
		   timeout : 5000,     
		   cache : false,  
		   
		   beforeSend: function (){
		//	showLoadingBar();
		   },
		   complete: function (){
		//	loading();
			
			$('.form-email-vertify').show();
			$("#emailchk_cert_num").val(num);
		//	$("#email_vertify").val(num);
			swal({
			  text: email+', <?php echo $lang['Verification_Code_Sending_Text']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			  closeOnClickOutside: false,
			  closeOnEsc: false,
			}).then(function(isConfirm) {
			  if (isConfirm) {

				  var time = 600;
				  var min = '';
				  var sec = '';

				  var x = setInterval(function(){
					min = parseInt(time/60);
					sec = time%60;

					if(sec < 10){
						sec = '0'+sec;
					} 

					$('#timer').html(min+':'+sec);
					time--;

					if(time < 0){
						clearInterval(x);
						$('#timer').html('<?php echo $lang['Timeout']; ?>');
						$("#btn_cert").addClass("disabled");
						$("#emailchk_cert_num").val('timeout');
					}
				  },1000)

				  setTimeout(function(){
					$('#resend_email_verify').removeClass('disabled');
				  },300000);

			  }
			})
		   },
	  });
	}

	function resend_mail(){
		var email = $.trim($("#register_email").val());
		var num = rand(000000, 999999);

		$.ajax({
		   type:"POST",       
		   url:"/extern/sso/sendmail_certificate.php",   
		   data : ({email: email, num:num}),
		   timeout : 5000,     
		   cache : false,  
			
		   beforeSend: function (){
		//	showLoadingBar();
		   },
		   complete: function (){
		//	loading();
			$("#emailchk_cert_num").val(num);
			swal({
			  text: email+', <?php echo $lang['Verification_Code_Resending_Text']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			}).then(function(isConfirm) {
			  if (isConfirm) {
				  $('#resend_email_verify').addClass('disabled');
				  $("#btn_cert").removeClass("disabled");
				  $('#timer').css("display","default");

				  var time = 600;
				  var min = '';
				  var sec = '';

				  var x = setInterval(function(){
					min = parseInt(time/60);
					sec = time%60;
					
					if(sec < 10){
						sec = '0'+sec;
					} 

					$('#timer').html(min+':'+sec);
					time--;

					if(time < 0){
						clearInterval(x);
						$('#timer').html('<?php echo $lang['Timeout']; ?>');
						$("#btn_cert").addClass("disabled");
						$("#emailchk_cert_num").val('timeout');
					}
				  },1000)

				  setTimeout(function(){
					$('#resend_email_verify').removeClass('disabled');
				  },300000);
			  }
			})
		   },
	  });
	}


	function check_email_cert_number(){
		$("#email_vertify").val($("#email_vertify").val().replace(/[^0-9]/g,""));
	}

	function email_cert_number_confirm(){
			
		if ( $("#email_vertify").val() != $("#emailchk_cert_num").val() || $("#register_email").val() == "" || $('#email_vertify').val() == "") {
			//alert
			swal({
			  text: '<?php echo $lang['Not_Match_Verification_Code']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
		} else {
			$('#register_email').prop('readonly', true);
			$('#email_vertify').prop('readonly', true);
			$("#emailchk_commit").val("Y");
			//alert
			swal({
			  text: '<?php echo $lang['Have_Been_Authenticated']; ?>',
			  buttons: '<?php echo $lang['Confirm']; ?>',
			})
			$("#btn_cert").addClass("disabled");
			$('#timer').css("display","none");
			$("#btn_confirm_referral").css('display','inline-block');
		}
	}
</script>