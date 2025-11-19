document.addEventListener("DOMContentLoaded", function () {
    // SideBar Handler
    var sideBar = document.getElementById("sidebar");
    var openSidebar = document.getElementById("openSideBar");
    var closeSidebar = document.getElementById("closeSideBar");

    if (sideBar && openSidebar && closeSidebar) {
        var isOpen = false;

        // Function to open the sidebar
        function openSideBar() {
            sideBar.classList.remove("-translate-x-full");
            sideBar.classList.add("translate-x-0");
            isOpen = true;
        }

        // Function to close the sidebar
        function closeSideBar() {
            sideBar.classList.add("-translate-x-full");
            sideBar.classList.remove("translate-x-0");
            isOpen = false;
        }

        openSidebar.addEventListener("click", function (event) {
            // Check if the clicked element or any of its ancestors is #openSideBar
            if (event.target === openSidebar || event.target.closest('#openSideBar')) {
                if (!isOpen) {
                    openSideBar();
                } else {
                    closeSideBar();
                }
            }
        });

        closeSidebar.addEventListener("click", function () {
            closeSideBar();
        });

        // Close sidebar when clicked outside
        document.body.addEventListener('click', function(event) {
            var target = event.target;
            if (target !== sideBar && !sideBar.contains(target) && target !== openSidebar) {
                closeSideBar();
            }
        });
    }
});


	

    

// Dropdown Handler
function dropdownHandler(element) {
	let single = element.getElementsByTagName("ul")[0];
	single.classList.toggle("hidden");
}

// Get all menu items and submenus
const menuItems = document.querySelectorAll("[data-tdg-dropdown]");
const subMenus = document.querySelectorAll("[data-tdg-dropdownItem]");
menuItems.forEach((menuItem, index) => {
	menuItem.addEventListener("click", () => {
		const submenu = subMenus[index];
		submenu.classList.toggle("hidden");
		submenu.classList.toggle("active");
		menuItem.classList.toggle("active");
		menuItem.href = "javascript:void(0);";
		menuItems.forEach((otherMenuItem, otherIndex) => {
			if (otherIndex !== index) {
				const otherSubmenu = subMenus[otherIndex];
				otherSubmenu.classList.add("hidden");
				otherMenuItem.classList.remove("active");
				otherSubmenu.classList.remove("active");
			}
		});
	});
});

// Dropdown
const dropdownBtns = document.querySelectorAll(
	'.dropdown > [data-dropdown-label="btn"]'
);
const showMenus = document.querySelectorAll(
	'.dropdown [data-dropdown-item="item"]'
);

dropdownBtns.forEach((dropdownBtn, index) => {
	dropdownBtn.addEventListener("click", (event) => {
		const dropdownMenu = showMenus[index];
		dropdownMenu.classList.toggle("hidden");
		// Prevent the event from propagating to the document body
		event.stopPropagation();
	});
});

// Add event listener to hide dropdowns when clicking outside
document.body.addEventListener('click', (event) => {
	dropdownBtns.forEach((dropdownBtn, index) => {
		const dropdownMenu = showMenus[index];
		// Check if the click target is not inside the dropdown
		if (dropdownMenu && !dropdownMenu.contains(event.target) && event.target !== dropdownBtn) {
			dropdownMenu.classList.add('hidden');
		}
	});
});


// Form Validation
const forms = document.querySelectorAll(".validation");

forms.forEach((form) => {
	const inputFuild = form.querySelectorAll(
		"input[required], select[required], textarea[required]"
	);

	inputFuild.forEach((input) => {
		input.addEventListener("focus", () => {
			removeError(input);
		});
		input.addEventListener("blur", () => {
			validateInput(input);
		});
	});

	form.addEventListener("submit", function (event) {
		event.preventDefault();

		let isValid = true;

		inputFuild.forEach((input) => {
			if (!validateInput(input)) {
				isValid = false;
				input.classList.add("invalid");
			} else {
				input.classList.remove("invalid");
			}
		});

		if (isValid) {
			form.submit();
		}
	});
});

// Validation function
function validateInput(input) {
	const value = input.value.trim();
	const type = input.getAttribute("type");

	if (value === "") {
		setError(input, "Please enter a value");
		return false;
	}

	if (type === "") {
		setError(input, "Please set input");
		return false;
	}

	if (type === "text") {
		if (value.length < 0) {
			setError(input, "Please enter a value");
			return false;
		}
	}

	if (type === "email") {
		const emailPattern =
			/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		if (!emailPattern.test(value)) {
			setError(input, "Please enter a valid email address.");
			return false;
		}
	}

	if (type === "password") {
		if (value.length < 6) {
			setError(input, "Password must be at least 6 characters.");
			return false;
		}
	}

	removeError(input);
	return true;
}

function setError(input, errorMessage) {
	input.classList.add("invalid-input");
	const errorElement = document.getElementById(`${input.id}-error`);
}

function removeError(input) {
	input.classList.remove("invalid-input");
	const errorElement = document.getElementById(`${input.id}-error`);
	if (errorElement) {
		errorElement.textContent = "";
	}
}

// Tab
var buttons = document.querySelectorAll(".tab-button");
buttons.forEach(function (button) {
	button.onclick = function () {
		var tabId = button.getAttribute("data-d2c-tab");
		openTab(tabId);
	};
});

function openTab(tabId) {
	var tabContents = document.querySelectorAll(".tab-content");
	tabContents.forEach(function (tabContent) {
		tabContent.classList.add("hidden");
	});

	var tabButtons = document.querySelectorAll(".tab-button");
	tabButtons.forEach(function (button) {
		button.classList.remove("active");
	});

	var tabElement = document.getElementById(tabId);
	if (tabElement) {
		tabElement.classList.remove("hidden");
	}

	var tabButton = document.querySelector(`[data-d2c-tab="${tabId}"]`);
	if (tabButton) {
		tabButton.classList.add("active");
	}
}

openTab("tab1");


// Preloader Js
// Set initial opacity
$(".preloader").css("opacity", 1);

// Delay execution for 2 seconds
setTimeout(function() {
    // Set opacity to 0
    $(".preloader").css("opacity", 0);
    // After a short delay (for the fade-out effect to complete), set display to none
    setTimeout(function() {
        $(".preloader").css("display", "none");
    }, 400); // Adjust the delay to match the fade-out duration
}, 400);



// dropdown
const dropdownBtnsMain = document.querySelectorAll('.dropdown-btn');
const dropdownMenus = document.querySelectorAll('.dropdown-menu');

dropdownBtnsMain.forEach((btn, index) => {
    btn.addEventListener('click', function() {
        dropdownMenus[index].classList.toggle('hidden');
    });

    // Close dropdown when clicking outside of it
    document.addEventListener('click', function(event) {
        const isClickInside = btn.contains(event.target) || dropdownMenus[index].contains(event.target);
        if (!isClickInside) {
            dropdownMenus[index].classList.add('hidden');
        }
    });
});



// accordion
const accordionHeaders = document.querySelectorAll(".accordion-header");

accordionHeaders.forEach((header) => {
	header.addEventListener("click", () => {
		const accordionContent = header.nextElementSibling;
		const isOpen = accordionContent.style.maxHeight !== "";

		accordionHeaders.forEach((otherHeader) => {
			const otherAccordionContent = otherHeader.nextElementSibling;
			otherAccordionContent.style.maxHeight = "";
			otherHeader.classList.remove("active"); // Remove 'active' class from all headers
			otherHeader.querySelector(".fas").classList.remove("fa-chevron-up");
			otherHeader.querySelector(".fas").classList.add("fa-chevron-down");
		});

		if (!isOpen) {
			accordionContent.style.maxHeight = `${accordionContent.scrollHeight + 32}px`;
			header.classList.add("active"); // Add 'active' class to the clicked header
			header.querySelector(".fas").classList.remove("fa-chevron-down");
			header.querySelector(".fas").classList.add("fa-chevron-up");
		}
	});
});

// <!--
//     Template Name: {{CryptoTrakX}}
//     Template URL: {{https://designtocodes.com/product/cryptotrakx-tailwind-crypto-dashboard}}
//     Description: {{Take Control of Your Crypto Portfolio with CryptoTrakx Tailwind Crypto Dashboard! Keep track of all your cryptocurrencies with ease using it.}}
//     Author: DesignToCodes
//     Author URL: https://www.designtocodes.com
//     Text Domain: {{ CryptoTrakX }} 
// -->