/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/cardRotate.js ***!
  \************************************/
var cardToRotate = document.querySelector(".card");
var cardText = document.querySelector(".cardText");
var appear = document.querySelector(".displayNone");
var input = document.getElementById("reset");
var targetName, backgr;
var count = 0;
input.addEventListener('click', function (e) {
  cardToRotate.style.background = 'beige';
});
cardToRotate.addEventListener("click", function (e) {
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
});
cardToRotate.addEventListener("mouseenter", function () {
  if (count % 2 === 0 && targetName !== 'input') {
    cardToRotate.style.transform = "translateY(15px)";
    cardToRotate.style.filter = "brightness(.7)";
  }
});
cardToRotate.addEventListener("mouseleave", function (e) {
  cardToRotate.style.transition = "all 0.5s ease";

  if (count % 2 === 0 && targetName !== 'input') {
    cardToRotate.style.transform = "translateY(0px)";
  }

  cardToRotate.style.filter = "none";
});
/******/ })()
;