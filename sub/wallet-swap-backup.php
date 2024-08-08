<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; 

$token				= escape_string(trim($_REQUEST['token']));

$query = mysqli_query($connect, "SELECT m_account FROM AccountsChain WHERE m_login_id = '".$_SESSION['sess_login_id']."' AND m_symbol = 'ICP'");
$info = mysqli_fetch_array($query);

$url="https://icptest.nebula3gamefi.com:8080/api/balance/".$info['m_account'];
$return_data = curl_return_json($url);
$BAT = $return_data['balances'] / 100000000;
?>
<script>
window.addEventListener('message', function(event) {
	if (allowedDomains.includes(event.origin)) {
		if (event.data.status === 'transfer') {
			const bat_amount = event.data.amount / 100000000;
			var result = event.data.result;
			result.Ok = result.Ok.toString();
			var jsonString  = JSON.stringify(result);
			console.log(jsonString);
			if ( result.hasOwnProperty('Ok') && $("#swap_from").val() == bat_amount) {

				$.ajax({
				   type:"POST",        
				   url:"/includes/proc_baviWallet.php",     
				   data : ({mode:"bat_swap", swap_from:"bat", swap_from_amount:$("#swap_from").val(), swap_to:"sn3", swap_to_amount:$("#swap_to").val(), swap_gas_fee:BAT.gas_fee, game_code: $("#select_swap_from").val(), data:jsonString }),
				   timeout : 600000,
				   cache : false,
				   success: function whenSuccess(args){
						console.log('args: ' + args);
						switch(args.trim()){
							case("InvalidConnection"):
								show_msg("Invalid connection access.");
								break;
							case("CATTokenInsufficient"):
								show_msg("Insufficient balance for BAT token.");
								break;
							case("NoCATWallet"):
								show_msg("BAT token wallet does not exist.");
								break;
							case("NoSyncEthWallet"):
								show_msg("ETH wallet is out of sync.");
								break;
							case("OutOfGas"):
								show_msg("Not enough gas. Please contact CS Center or Community administrator.");
								break;
							case("OutOfPMWBalance"):
								show_msg("Not enough SN3 for swap. Please contact CS Center or Community administrator.");
								break;
							case("BusyNetwork"):
								show_msg("The Ethereum network is very congested. Please try again later to avoid congestion.");
								break;
							case("Success"):
								clear_msg();
								$(".valid-message--confirm span").html("The SWAP was successful.");
								$(".valid-message--confirm").show();
								$("#execute_swap_button").html("Success!!!");
								setTimeout(function() {
								  $("#execute_swap_button").html("Refreshing...");
								  location.href="/sub/wallet-swap.php";
								}, 3000);
							 break;
						}
				   },
				   error: function whenError(e){
					console.log("code : " + e.status + ", message : " + e.responseText);
				   }
				});

			}
		}
	}
});
</script>
<div id="container" class="page-swap">
    <div class="article-header">
        <div class="article-header__inner wrap">
            <h2 class="article-title">SWAP</h2>
        </div><!-- .article-header__inner -->
    </div><!-- .article-header -->

    <div class="article-body">
        <div class="wrap">
            <div class="swap-box">
                <div class="swap-top">
					<p class="valid-message valid-message--confirm"><i class="icon"></i><span>Not enough CAT.</span></p>
                    <p class="valid-message valid-message--error"><i class="icon"></i><span>Not enough CAT.</span></p>
                    <a href="javascript:void(0);" class="swap-refresh" onclick="javascript:location.reload();"><span>Refresh</span></a>
                </div>
                <div class="swap-item swap-to">
                    <div class="swap-item__left">
                        <h3 class="swap-item__title">To [Estimate]</h3>
                        <div class="swap-select__container">
                            <select class="swap-select selectric" id="select_swap_to">
                                <option class="swap-select__pmw" value="pmw">SN3</option>
                            </select>
                        </div>
                    </div>
                    <div class="swap-item__right">
                        <div class="swap-item__max"><button type="button" onclick="placeMaxSN3();"><span>MAX</span></button></div>
                        <div class="swap-input__box">
                            <label>
                                <input type="number" class="swap-input" id="swap_to" onkeyup="placeSN3value()">
                                <span class="placeholder">0.0</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="swap-transfer"><button type="button"><span class="sr-only">swap transfer</span></button></div>
                <div class="swap-item swap-from">
                    <div class="swap-item__left">
                        <h3 class="swap-item__title">From</h3>
                        <div class="swap-select__container">
                            <select class="swap-select selectric" id="select_swap_from">
                                <!--option class="swap-select__cat" value="clwmc">CLWMC CAT</option-->
                                <option class="swap-select__bat" value="mm">MM BAT</option>
                            </select>
                        </div>
                    </div>
                    <div class="swap-item__right">
                        <div class="swap-item__balance"><p>Balance : <?php echo $BAT; ?> BAT</p></div>
                        <div class="swap-input__box">
                            <label>
                                <input type="number" class="swap-input" id="swap_from" value="" readonly disabled>
                                <span class="placeholder">0.0</span>
                            </label>
                        </div>
                        <div class="swap-item__fee"><p>fee : 0 BAT</p></div>
                    </div>
                </div>
                <button type="button" class="btn-basic btn-primary btn-swap disabled" id="execute_swap_button" onclick="execute_swap();"><span>Swap</span></button>
            </div>
        </div><!-- .wrap -->
    </div><!-- .article-body -->
</div><!-- #container -->

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>

<script>

const BAT = {
	'balance': <?php echo $BAT ; ?>,
	'gas_fee': 0,
	'symbol': 'BAT'
};

$('.valid-message').hide();

$(document).ready(function(){
    $('#select_swap_from').change(function(){
        var selectedValue = $(this).val();

		$('#swap_to').val('');
		$('#swap_from').val('');
		$('.swap-input__box').removeClass('active');
		switch (selectedValue) {

			case 'mm':
				$('.btn-swap').off('click');

				$('.swap-item__balance').find('p').text('Balance : ' + BAT.balance + ' BAT');
				$('.swap-item__fee').find('p').text('fee : ' + BAT.gas_fee + ' BAT');
				
				break;
/*
			case 'clwmc':
				$('.btn-swap').off('click');

				$('.swap-item__balance').find('p').text('Balance : ' + CAT.balance + ' CAT');
				$('.swap-item__fee').find('p').text('fee : ' + CAT.gas_fee + ' CAT');
				break;*/
		}
    });
});

function placeMaxSN3() {
	clear_msg();
	$('.swap-input__box').addClass('active');
/*
	if ( $("#select_swap_from").val() == "clwmc" ) {
		token = CAT;
		if (token.balance < token.gas_fee + 3600 ) {
			show_msg("Not enough CAT.");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
		can_swap_pmw = parseInt((token.balance - token.gas_fee ) / 3600);
		$("#swap_to").val(can_swap_pmw);
		$("#swap_from").val(can_swap_pmw * 3600 + token.gas_fee);

		if ( can_swap_pmw < 1000) {
			show_msg("The minimum swap PMW is 1000 PMW.");
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
	}*/
	if ( $("#select_swap_from").val() == "mm" ) { 
		token = BAT;
		if (token.balance < token.gas_fee ) {
			show_msg("Not enough CAT.");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
		can_swap_pmw = parseInt((token.balance - token.gas_fee ));
		$("#swap_to").val(can_swap_pmw);
		$("#swap_from").val(can_swap_pmw * 1 + token.gas_fee);

		if ( can_swap_pmw < 1000) {
			show_msg("The minimum swap PMW is 1000 PMW.");
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
	}


	$("#swap_from").removeClass("swap_over");
	$("#execute_swap_button").removeClass("disabled");
}

function placeSN3value() {
	clear_msg();
//	console.log($("#swap_to").val());
	$('.swap-input__box').addClass('active');

	var SN3_value = $("#swap_to").val(); 
	$("#swap_to").val(SN3_value.replace(/^[ 0]/g,''));

	if ( SN3_value == "" || SN3_value <= 0 ) {
		$("#swap_to").val("")
		$("#swap_from").val("");
		$('.swap-input__box').removeClass('active');
		return false;
	}
/*
	if ( $("#select_swap_from").val() == "clwmc" ) {
		token = CAT;
		if ( SN3_value * 3600 > token.balance - token.gas_fee ) {
			show_msg("Not enough CAT.");
			$("#swap_from").val(SN3_value * 3600 + token.gas_fee );
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
		else {
			$("#swap_from").val(SN3_value * 3600 + token.gas_fee );
			clear_msg();
		}

		if ( SN3_value < 1000) {
			show_msg("The minimum swap SN3 is 1000 SN3.");
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
	}*/
	if ( $("#select_swap_from").val() == "mm" ) {
		token = BAT;
		if ( SN3_value * 1 > token.balance - token.gas_fee ) {
			show_msg("Not enough BAT.");
			$("#swap_from").val(SN3_value * 1 + token.gas_fee );
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
		else {
			$("#swap_from").val(SN3_value * 1 + token.gas_fee );
			clear_msg();
		}

		if ( SN3_value < 1000) {
			show_msg("The minimum swap SN3 is 1000 SN3.");
			$("#swap_from").addClass("swap_over");
			$("#execute_swap_button").addClass("disabled");
			return false;
		}
	}

	$("#swap_from").removeClass("swap_over");
	$("#execute_swap_button").removeClass("disabled");
}

function execute_swap() {

	// check sync account
	$("#execute_swap_button").addClass("disabled");
	if ( $("#swap_to").val() < 1000) {
		show_msg("The minimum swap PMW is 1000 PMW.");
		$("#swap_from").addClass("swap_over");
		$("#execute_swap_button").addClass("disabled");
		return false;
	}
	
//	if ( $("#select_swap_from").val() == "clwmc" ) cat_swap();
	if ( $("#select_swap_from").val() == "mm" ) bat_swap();
	
}
/*
function cat_swap () {

	$.ajax({
	   type:"POST",        
	   url:"/includes/proc_baviWallet.php",     
	   data : ({mode:"ch0020"}),
	   timeout : 600000,  
	   cache : false,
	   success: function whenSuccess(args){
			//console.log(args.trim());
			switch(args.trim()){
			 case("done"):

				$("#execute_swap_button").html("Processing...");
				
				$.ajax({
				   type:"POST",        
				   url:"/includes/proc_baviWallet.php",     
				   data : ({mode:"cat_swap", swap_from:"cat", swap_from_amount:$("#swap_from").val(), swap_to:"sn3", swap_to_amount:$("#swap_to").val(), swap_gas_fee:CAT.gas_fee, game_code: $("#select_swap_from").val() }),
				   timeout : 600000,  
				   cache : false,        
				   success: function whenSuccess(args){
						console.log(args);
						switch(args.trim()){
							case("InvalidConnection"):
								show_msg("Invalid connection access.");
								break;
							case("CATTokenInsufficient"):
								show_msg("Insufficient balance for CAT token.");
								break;
							case("NoCATWallet"):
								show_msg("CAT token wallet does not exist.");
								break;
							case("NoSyncEthWallet"):
								show_msg("ETH wallet is out of sync.");
								break;
							case("OutOfGas"):
								show_msg("Not enough gas. Please contact CS Center or Community administrator.");
								break;
							case("OutOfPMWBalance"):
								show_msg("Not enough PMW for swap. Please contact CS Center or Community administrator.");
								break;
							case("BusyNetwork"):
								show_msg("The Ethereum network is very congested. Please try again later to avoid congestion.");
								break;
							case("Success"):
								clear_msg();
								$(".valid-message--confirm span").html("The SWAP was successful.");
								$(".valid-message--confirm").show()
								$("#execute_swap_button").html("Success!!!");
								setTimeout(function() {
								  $("#execute_swap_button").html("Refreshing...");
								  location.href="/sub/wallet-swap.php";
								}, 5000);
							 break;
						}
				   },
				   error: function whenError(e){
					console.log("code : " + e.status + ", message : " + e.responseText);
				   }
				});

			 break;
			 case("yet"):

				swal({
					text: '<?php echo $lang["MM_Sync_MoveToPage"]; ?>',
					buttons: {
						cancel : '<?php echo $lang["Cancel"]; ?>',
						confirm : {
							text : '<?php echo $lang["Confirm"]; ?>',
							value : 'catch'
						},
					},
				}).then(function(value){
					if(value == 'catch'){
						window.open("https://metamask.io/download/", "_blank");
						return false;
					}
					location.href="/sub/wallet-sync-account.php";
				});

				return false;

			 break;
		  }
		
	   },
	   error: function whenError(e){
		console.log("code : " + e.status + "message : " + e.responseText);
		return false;
	  }
	});
	
}
*/
function bat_swap () {

	$.ajax({
	   type:"POST",        
	   url:"/includes/proc_galaxyWallet.php",     
	   data : ({mode:"ch0020"}),
	   timeout : 600000,  
	   cache : false,
	   success: function whenSuccess(args){
			//console.log(args.trim());
			switch(args.trim()){
			 case("done"):

				$("#execute_swap_button").html("Processing...");
				$("#execute_swap_button").prop('disabled', true);
				
				const amount = Number($("#swap_from").val()) * 100000000;

				var iframe = document.getElementById('Internet_Identity');
				const data = {
					mode: 'transfer',
					amount: amount
				}
				iframe.contentWindow.postMessage(data, '*');
				
			 break;
			 case("yet"):

				swal({
					text: '<?php echo 'go to sync wallet'; ?>',
					buttons: {
						cancel : '<?php echo $lang["Cancel"]; ?>',
						confirm : {
							text : '<?php echo $lang["Confirm"]; ?>',
						},
					},
				}).then(function(value){

					location.href="/sub/wallet-sync-nebula-account.php";

				});

				return false;

			 break;
		  }
		
	   },
	   error: function whenError(e){
		console.log("code : " + e.status + "message : " + e.responseText);
		return false;
	  }
	});

}

function show_msg(msg) {
    $(".valid-message--error").show()
	$(".valid-message--error span").html(msg);
}
function clear_msg() {
    $(".valid-message--error").hide()
	$(".valid-message--error span").html("");
}
</script>