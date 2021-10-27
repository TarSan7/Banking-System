const cardsAll = document.querySelectorAll(".card");

cardsAll.forEach(function (el, id) {

    el.addEventListener("mouseenter", (e) => {
        el.style.transform = "translateY(15px)";
        el.style.filter = "brightness(.7)";

    });

    el.addEventListener("mouseleave", (e) => {
        el.style.transition = "all 0.5s ease";
        el.style.transform = "translateZ(0px)";
        el.style.filter = "none";
    })
})
