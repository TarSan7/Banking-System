const cards = document.querySelectorAll(".card");
const leftArr = document.querySelector('.lArrow');
const rightArr = document.querySelector('.rArrow');
const dots = document.querySelectorAll('.dot');
let index = 0;
cards[0].classList.add('active');
dots[0].classList.add('active');

const cardEffectNext = (elem) => {
    elem.animate([
        {transform: "translateX(80px)", offset: 0},
        {transform: "translateX(0px)", offset: 1}
    ], {
        duration: 300,
        iterations: 1
    });
}
const cardEffectPrev = (elem) => {
    elem.animate([
        {transform: "translateX(-80px)", offset: 0},
        {transform: "translateX(0px)", offset: 1}
    ], {
        duration: 300,
        iterations: 1
    });
}

const prepareCurrSlide = () => {
    cards[index].classList.add('active');
    dots[index].classList.add('active');
}

rightArr.addEventListener('click', e => {
    for(let i = 0; i < cards.length; i++)
    {
        dots[i].classList.remove('active');
        cards[i].classList.remove('active');
    }
    if (index === cards.length - 1) {
        index = 0;
        prepareCurrSlide();
    } else {
        index++;
        prepareCurrSlide();
    }
    cardEffectNext(cards[index]);
})

leftArr.addEventListener('click', e => {
    for(let i = 0; i < cards.length; i++)
    {
        dots[i].classList.remove('active');
        cards[i].classList.remove('active');
    }
    if (index === 0) {
        index = cards.length - 1;
        prepareCurrSlide();
    } else {
        index--;
        prepareCurrSlide();
    }
    cardEffectPrev(cards[index]);
})

dots.forEach(function (elem, id) {
    elem.addEventListener('click', e => {
        for(let i = 0; i < dots.length; i++)
        {
            dots[i].classList.remove('active');
            cards[i].classList.remove('active');
        }
        elem.classList.add('active');
        if (index < id) {
            index = id;
            cardEffectNext(cards[index]);
        } else {
            index = id;
            cardEffectPrev(cards[index]);
        }
        cards[id].classList.add('active');
    });
})
