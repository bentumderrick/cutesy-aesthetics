function initUploadArtwork() {
    // Get all elements
    const artworkInput = document.getElementById("artwork-image");
    const artworkPreview = document.getElementById("artwork-preview");
    const descriptionPreview = document.querySelector(".description-preview");
    const title = document.getElementById("title");
    const liveTitle = document.getElementById("image-title");
    const description = document.getElementById("description");
    const price = document.getElementById("price");
    const pricePreview = document.getElementById("price-preview");
    const imagePreviewContainer = document.querySelector(".artworkPreview-container");
    const videoPreview = document.getElementById("video-preview");
    
    // New elements for AJAX
    const uploadForm = document.getElementById("uploadForm");
    const publishBtn = document.getElementById("publishBtn");
    const draftBtn = document.getElementById("draftBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const actionInput = document.getElementById("action");
    const visibilityInput = document.getElementById("visibility");
    const publicDiv = document.querySelector(".public");
    const privateDiv = document.querySelector(".private");
    const notForSale = document.getElementById("not-for-sale");
const nickname = document.getElementById('nickname').dataset.set;
    // Create a waiting message initially
    const p = document.createElement("p");
    p.textContent = "Waiting for you to select an image...";
    p.className = "waiting-for-image";
    imagePreviewContainer.append(p);

    let temporaryURL = null;

    // --- Price preview (now ZMW), aware of the not-for-sale lock ---
    function updatePricePreview() {
        if (notForSale.checked) {
            pricePreview.textContent = "Not for sale";
            return;
        }
        const priceValue = price.value.trim();
        pricePreview.textContent = priceValue === "" ? "Price" : "ZMW " + priceValue;
    }

    // --- Video uploads aren't sellable: force "not for sale" and lock the price field ---
    function lockAsNotForSale() {
        notForSale.checked = true;
        notForSale.disabled = true;
        price.disabled = true;
        updatePricePreview();
    }

    function unlockPriceForSale() {
        notForSale.checked = false;
        notForSale.disabled = false;
        price.disabled = false;
        updatePricePreview();
    }

    // --- File preview ---
    artworkInput.addEventListener("change", function() {
        const file = artworkInput.files[0];
        if (!file) return;
        if (p) p.remove(); // remove waiting message
        if (temporaryURL) URL.revokeObjectURL(temporaryURL);
        temporaryURL = URL.createObjectURL(file);

        if (file.type.startsWith("image/")) {
            artworkPreview.src = temporaryURL;
            artworkPreview.style.display = "block";
            videoPreview.style.display = "none";
            unlockPriceForSale();
        } else if (file.type.startsWith("video/")) {
            videoPreview.src = temporaryURL;
            videoPreview.style.display = "block";
            artworkPreview.style.display = "none";
            lockAsNotForSale();
        }
    });

    // --- Title preview ---
    title.addEventListener("input", function() {
        const titleValue = title.value.trim();
        liveTitle.textContent = titleValue === "" ? "Title" : titleValue;
    });

    price.addEventListener("input", updatePricePreview);
    notForSale.addEventListener("change", updatePricePreview);

    // --- Description preview ---
    description.addEventListener("input", function() {
        const descriptionValue = description.value.trim();
        descriptionPreview.textContent = descriptionValue === "" ? "Description" : descriptionValue;
    });

    // --- Visibility toggle ---
    publicDiv.addEventListener('click', function() {
        visibilityInput.value = 'public';
        publicDiv.classList.add('active');
        privateDiv.classList.remove('active');
    });

    privateDiv.addEventListener('click', function() {
        visibilityInput.value = 'private';
        privateDiv.classList.add('active');
        publicDiv.classList.remove('active');
    });

    // --- Submit functions ---
    function submitUpload() {
      
        // Client-side validation
        if (!title.value.trim()) {
            displayError('You forgot to put in the title 😛');
            return;
        }
        if (!artworkInput.files.length) {
            displayError('You forgot to select the file ' + nickname + '😗');
            return;
        }

        const formData = new FormData(uploadForm);

        fetch('upload_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySuccess(data.message);
               
            } else {
                displayError(data.message);
            }
        })
        .catch(error => {
            displayError('Network error: ' + error.message);
        });
    }

    publishBtn.addEventListener('click', function(e) {
        e.preventDefault();
     
        actionInput.value = 'publish';
        submitUpload();
    });

    draftBtn.addEventListener('click', function(e) {
    
        e.preventDefault();
        actionInput.value = 'draft';
        submitUpload();
    });

    cancelBtn.addEventListener('click', function(e) {
        e.preventDefault();
        uploadForm.reset();
        unlockPriceForSale();
        // clear previews
        artworkPreview.style.display = "none";
        videoPreview.style.display = "none";
        liveTitle.textContent = "Title";
        pricePreview.textContent = "Price";
        descriptionPreview.textContent = "Description Preview";
        // re-add waiting message
        const msg = document.createElement("p");
        msg.textContent = "Waiting for you to select an image...";
        msg.className = "waiting-for-image";
        imagePreviewContainer.append(msg);
    });
}