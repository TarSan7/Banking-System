const cardToRotate = document.querySelector(".card");
const cardText = document.querySelector(".cardText");
const appear = document.querySelector(".displayNone");
let input = document.getElementById("reset");
let targetName, backgr;
let count = 0;

input.addEventListener('click', (e) => {
    cardToRotate.style.background = 'beige';
});

cardToRotate.addEventListener("click", (e) => {
    count++;
    targetName = null;
    if (count % 2 === 1) {
        cardToRotate.style.transform = "scaleX(-1)";
        cardText.style.display = "none";
        appear.style.display = "block";
        appear.style.transform = "scaleX(-1)";
    } else {
        targetName = e.target.localName;
        if (targetName !== 'input') {
            cardText.style.display = "block";
            cardToRotate.style.transform = "scaleX(1)";
            appear.style.display = "none";
        } else {
            count--;
        }
    }
})

cardToRotate.addEventListener("mouseenter", () => {
    if (count % 2 === 0 && targetName !== 'input') {
        cardToRotate.style.transform = "translateY(15px)";
        cardToRotate.style.filter = "brightness(.7)";
    }
});

cardToRotate.addEventListener("mouseleave", (e) => {
    cardToRotate.style.transition = "all 0.5s ease";
    if (count % 2 === 0 && targetName !== 'input') {
        cardToRotate.style.transform = "translateY(0px)";
    }
    cardToRotate.style.filter = "none";
});
