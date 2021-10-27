const allElems = document.querySelectorAll(".standard");

allElems.forEach(function (elem) {

    elem.addEventListener("mouseenter", (e) => {
        elem.style.transition = "all 0.5s ease";
        elem.style.transform = "translateY(15px)";
        elem.style.background = "rgb(0, 0, 0, 0.3)";
    });

    elem.addEventListener("mouseleave", (e) => {
        elem.style.transform = "translateZ(0px)";
        elem.style.background = "#e2e8f0";
    });
})
