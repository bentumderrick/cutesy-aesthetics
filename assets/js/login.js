
const profileForm = document.getElementById("profileForm");
const errorContainer = document.getElementById("errorContainer");
const errorDiv = document.getElementById("errorMessages");
const phoneInput = document.querySelector("#phone");

const follower = document.getElementById('follower');
if (follower) {
    window.addEventListener('mousemove', (e) => {
        follower.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
    });
}
if (errorContainer) {
    errorContainer.addEventListener("click", function(event) {
        if (event.target === event.currentTarget) {
            // 1. Remove the show animation
            errorContainer.classList.remove("error-animation");
            
            // 2. Add the remove animation
            errorContainer.classList.add("error-animation-remove");
            
            // 3. Listen for animation to end (ONLY ONCE)
            errorContainer.addEventListener("animationend", function() {
                // 4. Hide and clean up
                errorContainer.style.visibility = "hidden";
                errorContainer.classList.remove("error-animation-remove");
                if (errorDiv) {
                    errorDiv.innerHTML = "";
                }
            }, { once: true });
        }
    });
}

// ============================================
// STEP 7: Helper functions (recipe cards)
// ============================================

// Function to show error messages
function displayError(message) {
    if (!errorContainer || !errorDiv) {
        alert(message);
        return;
    }
    
    errorDiv.innerHTML = "";
    
    const errorText = document.createElement("p");
    errorText.textContent = message;
    errorText.style.color = "#dc3545";
    errorText.className = "error-txt";
errorText.className = "error-txt error-message";  // ← Add error-message class
    errorDiv.appendChild(errorText);
    
    errorContainer.style.visibility = "visible";
    errorContainer.classList.add("error-animation");
}

// Function to show success messages
function displaySuccess(message) {
    if (!errorContainer || !errorDiv) {
        alert(message);
        return;
    }
    
    errorDiv.innerHTML = "";
    
    const successText = document.createElement("p");
    successText.textContent = message;
    successText.style.color = "#28a745";
    successText.className = "error-txt";
successText.className = "error-txt success-message";  // ← Add success-message class
    errorDiv.appendChild(successText);
    
    errorContainer.style.visibility = "visible";
    errorContainer.classList.add("error-animation");
  
    setTimeout(function() {
        hideMessages();
    }, 4000);
}

// Function to hide the error container
function hideMessages() {
    if (errorContainer && errorDiv) {
        errorContainer.classList.remove("error-animation");
        errorContainer.classList.remove("error-animation-remove");
        errorContainer.style.visibility = "hidden";
        errorDiv.innerHTML = "";
    }
}

// ============================================
// STEP 8: Handle form submission
// ============================================
profileForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    
    // Clear any old messages first
    hideMessages();


    try {
        const res = await fetch("login-redirect.php", {
            method: "POST",
            body: new FormData(profileForm)
        });

        if (!res.ok) {
            throw new Error("Server error: " + res.status);
        }
        
        const data = await res.json();

       if (data.error) {
    displayError(data.message);
} else {
    displaySuccess(data.message);
     setTimeout(function() {
       window.location.href = "dashboard.php";
    }, 3000);
    
}
    } catch (error) {
        displayError("Error searching details: " + error.message);
    }
});