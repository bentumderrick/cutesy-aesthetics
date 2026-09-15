function initProfilePage() {

    const profileForm = document.getElementById("profileForm");
    const phoneInput = document.querySelector("#phone");

    const topHeaderProfile = document.getElementById("top-header-profile");

    const confirmOverlay = document.getElementById("confirmOverlay");
    const confirmSave = document.getElementById("confirmSave");
    const cancelSave = document.getElementById("cancelSave");

    const profileInput = document.getElementById("profile_picture");
    const img = document.querySelector(".profile-edit");

    const removeBtn = document.getElementById("removeProfileBtn");


    if (!profileForm) return;


    // ================================
    // Phone Input
    // ================================

    let iti = null;

    if (phoneInput && window.intlTelInput) {

        iti = window.intlTelInput(phoneInput, {
            initialCountry: "gh",
            preferredCountries: ["gh", "ng", "us", "gb"],
            loadUtils: () =>
                import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.2/build/js/utils.js")
        });

    }



    // ================================
    // Profile Image Preview
    // ================================

    const originalProfilePic = img ? img.src : "";
    const originalHeaderPic = topHeaderProfile 
        ? topHeaderProfile.src 
        : null;


    if(profileInput && img){

        profileInput.addEventListener("change", function(){

            if(this.files.length > 0){

                const url = URL.createObjectURL(this.files[0]);

                img.src = url;

                if(topHeaderProfile){
                    topHeaderProfile.src = url;
                }

            }

        });

    }



    // ================================
    // Remove Image
    // ================================

    function removeProfile(){

        if(img){
            img.src = originalProfilePic;
        }


        if(topHeaderProfile && originalHeaderPic){
            topHeaderProfile.src = originalHeaderPic;
        }


        if(profileInput){
            profileInput.value = "";
        }

    }



    if(removeBtn){

        removeBtn.addEventListener("click", function(e){

            e.preventDefault();

            removeProfile();

        });

    }




    // ================================
    // Modal Functions
    // ================================

    function openConfirm(){

        if(confirmOverlay){

            confirmOverlay.classList.add("active");

        }

    }



    function closeConfirm(){

        if(confirmOverlay){

            confirmOverlay.classList.remove("active");
            confirmOverlay.classList.remove("closing");

        }

    }




    // ================================
    // Save Profile
    // ================================

    async function saveProfile(){


        if(!confirmSave) return;


        confirmSave.disabled = true;
        confirmSave.textContent = "Saving...";


        try{


            if(phoneInput && iti){

                phoneInput.value = iti.getNumber();

            }



            const response = await fetch("edit-profile.php",{

                method:"POST",

                body:new FormData(profileForm)

            });



            if(!response.ok){

                throw new Error(
                    "Server error: " + response.status
                );

            }



            const data = await response.json();



            if(data.error){

                displayError(data.message);

            }
            else{

                displaySuccess(
                    data.message || 
                    "Profile updated successfully!"
                );

            }



        }
        catch(error){

            displayError(
                "Error saving profile: " + error.message
            );

        }
        finally{

            confirmSave.disabled = false;

            confirmSave.textContent = "Save Changes";

        }


    }





    // ================================
    // Form Submit -> Open Modal
    // ================================

    profileForm.addEventListener("submit", function(e){

        e.preventDefault();

        openConfirm();

    });





    // ================================
    // Confirm Save Button
    // ================================

    if(confirmSave){

        confirmSave.addEventListener("click", async function(){


            if(iti && !iti.isValidNumber()){


                const errorMap = [

                    "Invalid phone number.",
                    "Invalid country code.",
                    "Phone number too short.",
                    "Phone number too long.",
                    "Invalid phone number."

                ];



                displayError(
                    errorMap[iti.getValidationError()]
                    ||
                    "Invalid phone number."
                );


                phoneInput.focus();

                return;

            }



            closeConfirm();


            await saveProfile();



        });

    }





    // ================================
    // Cancel Button
    // ================================

    if(cancelSave){

        cancelSave.addEventListener("click", function(){

            closeConfirm();

        });

    }





    // ================================
    // Click Outside Modal
    // ================================

    if(confirmOverlay){

        confirmOverlay.addEventListener("click", function(e){


            if(e.target === confirmOverlay){

                closeConfirm();

            }


        });

    }



}