<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; 

$login_id			= escape_string(trim($_POST['login_id']));
$login_pw			= escape_string(trim($_POST['login_pw']));
$query				= escape_string(trim($_POST['query']));


if( $query == "login" ){

	$info = array();

	$que = mysqli_query($connect, "SELECT *,PASSWORD('".$login_pw."') as check_pw FROM Accounts WHERE login_id='".$login_id."'");
	$row = mysqli_fetch_array($que);
	$info = $row;

	if($login_id == ''){
		echo '<script>
			swal({
				text: "'.$lang['ID_Check'].'",
				buttons: "'.$lang['Confirm'].'",
			});
			</script>';
		$query = '';
	} else if ($login_pw == '' || $info['login_pw'] != $info['check_pw']) {
		echo '<script>
			swal({
				text: "'.$lang['Password_Not_Match'].'",
				buttons: "'.$lang['Confirm'].'",
			});
			</script>';
		$query = '';
	} else if(!isset($info['login_id'])) {
		echo '<script>
			swal({
				text: "'.$lang['Not_Exist_ID'].'",
				buttons: "'.$lang['Confirm'].'",
			});
			</script>';
	}	else if ($info['email_cert'] != 'y') {
			echo '<script>
				swal({
					text: "'.$lang['Email_Not_Verified'].'",
					buttons: "'.$lang['Confirm'].'",
				});
				</script>';
			$_POST['query'] = "";
	} else { 
?>

		<form name="login_proc" method="post" action="/extern/sso/login_check.php">
			<input type='hidden' name='login_id' value='<?php echo $login_id; ?>'>
			<input type='hidden' name='login_pw' value='<?php echo $login_pw; ?>'>
			<input type='hidden' name='return_path' value='<?php echo $return_path; ?>'>
		</form>

		<script type='text/javascript'>
			document.login_proc.submit();
		</script>
<?php
	}
}
?>

<div id="container" class="page-login">
    <div class="article-header">
        <div class="article-header__inner login-wrap">
            <h2 class="article-title">Login</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="login-wrap">
            <div class="login-form__wrap">
			<form name="form_login" id="form_login" action="login.php" method="POST">
			<input type="hidden" name="query" value="login">
			<input type="hidden" name="return_path" value="<?php echo $return_path; ?>">
			<input type="hidden" id="idchk_commit" value="">
			<input type="hidden" id="pwchk_commit" value="">
                <div class="login-form__field">
                    <div class="form-field__item">
                        <label class="form-label" for="login_id"><span>ID</span></label>
                        <input type="text" class="form-input" id="login_id" name="login_id">
                    </div><!-- .form-field__item -->
                    <div class="form-field__item">
                        <label class="form-label" for="login_pw"><span>Password</span></label>
                        <input type="password" class="form-input" id="login_pw" name="login_pw">
                    </div><!-- .form-field__item -->
                </div>
                <p class="valid-message valid-message--error capsLock_on" id="capsLock_on" style="display:none;"><i class="icon"></i><span>Caps lock is on</span></p>
                <p class="valid-message valid-message--error" id="valid_message_error" style="display:none;"><i class="icon" ></i><span>The account or password you entered is incorrect, please enter again.</span></p>
                <button class="btn-basic btn-login btn-primary" type="button" onclick="login_check();"><span>Login</span></button>
			</form>
            </div>
            <div class="or">or</div>
            <div class="signup"><a href="./signup.php" class="btn-basic btn-signup btn-primary"><span>Sign up</span></a></div>
            <ul class="login-helper">     
                <li><a href="./find-id.php"><span>Forget your account</span></a></li>
                <li><a href="./find-pw.php"><span>Forget the password</span></a></li>
            </ul>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<script>

	function isValidLoginId(id) {
		var check = /^(?=.*[a-zA-Z])(?=.*[0-9]).{5,16}$/;

		if (!check.test(id))     {
			return false;
		}

		if (id.length < 5 || id.length > 16) {
			return false;
		}

		return true;
	}

	function isValidPassword(pw) {
		var check = /^(?=.*[a-zA-Z])(?=.*[0-9]).{8,16}$/;	

		if (!check.test(pw))     {
			return false;
		}

		if (pw.length < 8 || pw.length > 16) {
			return false;
		}

		return true;
	}

	function login_check() {
		var login_id = $.trim($("#login_id").val());
		var login_pw = $.trim($("#login_pw").val());
		var chk_login_id = $.trim($("#idchk_commit").val());
		var chk_login_pw = $.trim($("#pwchk_commit").val());

		if (login_id == "" || !isValidLoginId(login_id) || chk_login_id != "Y") {
			$("#valid_message_error").show();
			$("#login_id").focus();
			return false;
		} 

		if (login_pw == "" || !isValidPassword(login_pw) || chk_login_id != "Y") {
			$("#valid_message_error").show();
			$("#login_pw").focus();
			return false;
		} else {
			$("#valid_message_error").hide();
			document.form_login.submit();
		}
	}

	$("#form_login").keypress(function(e){
		if(e.keyCode === 13){
			login_check();
		}
	});

	$("#login_id").keyup(function(e){
		if(e.keyCode !== 13) {
		$("#valid_message_error").hide();

		$.ajax({
		   type:"POST",        
		   url:"/extern/sso/login_validate_id.php",     
		   data : ({id: $("#login_id").val() }),
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
		}
	})

	$("#login_pw").keyup(function(e){
		if(e.keyCode !== 13) {
		$("#valid_message_error").hide();

		 if (event.getModifierState("CapsLock")) {
			$('#capsLock_on').show();
			//console.log('true');
		} else {
			$('#capsLock_on').hide();
			//console.log('false');
		}
		
		var passwd = $("#login_pw").val();
		$.ajax({
			type: "POST",
			url: "/extern/sso/login_validate_pw.php",
			data : ({pw: passwd }),
			cache: false,
			success: function(args){
				if(args.trim().includes("bad_pw")){
					$("#pwchk_commit").val("N");
				}
				else {
					$("#pwchk_commit").val("Y");
				}

			}
		});
		}
	})
</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>