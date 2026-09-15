//This is error.js//

const errorContainer = document.getElementById("errorContainer");
const errorDiv = document.getElementById("errorMessages");

function renderMessage(type, message) {
    if (!errorContainer || !errorDiv) {
        alert(message);
        return;
    }

    const isSuccess = type === "success";

    errorDiv.innerHTML = "";

    const icon = document.createElement("i");
    icon.className = isSuccess
        ? "fa-solid fa-heart error-icon-badge icon-success"
        : "fa-solid fa-face-frown error-icon-badge icon-error";

    const flourish = document.createElement("p");
    flourish.className = "error-flourish";
    flourish.textContent = isSuccess ? "Yay!" : "Oops!";

    const text = document.createElement("p");
    text.textContent = message;
    text.className = isSuccess
        ? "error-txt success-message"
        : "error-txt error-message";

    errorDiv.appendChild(icon);
    errorDiv.appendChild(flourish);
    errorDiv.appendChild(text);

    errorContainer.style.visibility = "visible";
    errorContainer.classList.add("error-animation");
}

function displayError(message) {
    renderMessage("error", message);
}

function displaySuccess(message) {
    renderMessage("success", message);
    // No auto-dismiss — the person has to tap the screen to close it.
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