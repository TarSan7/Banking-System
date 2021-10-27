/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/moveOther.js ***!
  \***********************************/
var allElems = document.querySelectorAll(".standard");
allElems.forEach(function (elem) {
  elem.addEventListener("mouseenter", function (e) {
    elem.style.transition = "all 0.5s ease";
    elem.style.transform = "translateY(15px)";
    elem.style.background = "rgb(0, 0, 0, 0.3)";
  });
  elem.addEventListener("mouseleave", function (e) {
    elem.style.transform = "translateZ(0px)";
    elem.style.background = "#e2e8f0";
  });
});
/******/ })()
;