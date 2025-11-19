// password validation
function validatePassword() {
    // Get password and confirm password values
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
  
    // Check if passwords match
    if (password !== confirmPassword) {
      // Show error message
      document.getElementById('passwordError').classList.remove('hidden');
    } else {
      // Hide error message and proceed with form submission or other actions
      document.getElementById('passwordError').classList.add('hidden');
      // Add your code here to handle the successful validation
    }
}

// show password
function showPassword() {
    var x = document.getElementById("password");
    x.type = (x.type === "password") ? "text" : "password";
}
// confirmed password
function showPassword1() {
    var x = document.getElementById("confirmPassword");
    x.type = (x.type === "password") ? "text" : "password";
}

// // checkbox validation
function validateForm(event) {
    event.preventDefault(); // Prevent form submission by default

    // Validate checkbox
    var checkbox = document.getElementById("termsCheckbox");
    if (!checkbox.checked) {
        document.getElementById("termsError").classList.remove("hidden");
        checkbox.classList.add("border-red-500"); // Set border color to red
        checkbox.classList.remove("border-green-500"); // Remove green border color
        return; // Stop further execution if checkbox is not checked
    } else {
        document.getElementById("termsError").classList.add("hidden");
        checkbox.classList.remove("border-red-500"); // Remove red border color
        checkbox.classList.add("border-green-500"); // Set border color to green
    }

    // If checkbox is checked, submit the form
    document.querySelector("form").submit();
}

// <!--
//     Template Name: {{CryptoTrakX}}
//     Template URL: {{https://designtocodes.com/product/cryptotrakx-tailwind-crypto-dashboard}}
//     Description: {{Take Control of Your Crypto Portfolio with CryptoTrakx Tailwind Crypto Dashboard! Keep track of all your cryptocurrencies with ease using it.}}
//     Author: DesignToCodes
//     Author URL: https://www.designtocodes.com
//     Text Domain: {{ CryptoTrakX }} 
// -->