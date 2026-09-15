const main = document.getElementById("h");
const contain = document.getElementById("load-id");
const body = document.body;



window.addEventListener("load", function () {


    // Prevent clicking while loading
    body.addEventListener("click", function (e) {
        if (isLocked) {
            e.preventDefault();
            e.stopPropagation();
        }
    });

    // Loader
    if (!sessionStorage.getItem("loaderItem")) {

        sessionStorage.setItem("loaderItem", "true");

        main.style.display = "none";
        main.style.opacity = "0";

        contain.classList.add("animation");
        body.classList.add("body");

        setTimeout(() => {
            main.style.display = "block";
        }, 3700);

        setTimeout(() => {
            main.style.transition = "2s ease-in-out";
            main.style.opacity = "1";
            body.classList.remove("body");
           
        }, 3800);

        contain.addEventListener("animationend", function () {
            contain.classList.remove("animation");
        });

    } else {

        contain.style.display = "none";
        main.style.display = "block";
        main.style.opacity = "1";
        body.classList.remove("body");
       

    }

});