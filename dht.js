const men_section = document.querySelector('.men');
const women_section = document.querySelector('.women');
const kids_section = document.querySelector('.kids');
const home_section = document.querySelector('.homeliving');
const beauty_section = document.querySelector('.beauty');

const icons_section = document.querySelector('.icons');

const men_section_items = document.querySelector('.men-section-items');
const women_section_items = document.querySelector('.women-section-items');
const kids_section_items = document.querySelector('.kids-section-items');
const home_section_items = document.querySelector('.home-section-items');
const beauty_section_items = document.querySelector('.beauty-section-items');

const icons_section_items = document.querySelector('.icons-section-items');

const container_ele = document.querySelector('.container');
var bodyele = document.getElementsByTagName("BODY");

men_section.onmouseover = () => {
    men_section_items.classList.remove('visibility');
}
men_section.onmouseout = () => {
    men_section_items.classList.add('visibility');
}
men_section_items.onmouseover = () => {
    men_section_items.classList.remove('visibility');
}
men_section_items.onmouseout = () => {
    men_section_items.classList.add('visibility');
} /* men section ends here */

women_section.onmouseover = () => {
    women_section_items.classList.remove('visibility');
}
women_section.onmouseout = () => {
    women_section_items.classList.add('visibility');
}
women_section_items.onmouseover = () => {
    women_section_items.classList.remove('visibility');
}
women_section_items.onmouseout = () => {
    women_section_items.classList.add('visibility');
} /* women section ends here */

kids_section.onmouseover = () => {
    kids_section_items.classList.remove('visibility');
}
kids_section.onmouseout = () => {
    kids_section_items.classList.add('visibility');
}
kids_section_items.onmouseover = () => {
    kids_section_items.classList.remove('visibility');
}
kids_section_items.onmouseout = () => {
    kids_section_items.classList.add('visibility');
} /* kids section ends here */

home_section.onmouseover = () => {
    home_section_items.classList.remove('visibility');
}
home_section.onmouseout = () => {
    home_section_items.classList.add('visibility');
}
home_section_items.onmouseover = () => {
    home_section_items.classList.remove('visibility');
}
home_section_items.onmouseout = () => {
    home_section_items.classList.add('visibility');
} /* home and living ends here */

beauty_section.onmouseover = () => {
    beauty_section_items.classList.remove('visibility');
}
beauty_section.onmouseout = () => {
    beauty_section_items.classList.add('visibility');
}
beauty_section_items.onmouseover = () => {
    beauty_section_items.classList.remove('visibility');
}
beauty_section_items.onmouseout = () => {
    beauty_section_items.classList.add('visibility');
}

icons_section.onmouseover = () => {
    icons_section_items.classList.remove('visibility');
}
icons_section.onmouseout = () => {
    icons_section_items.classList.add('visibility');
}
icons_section_items.onmouseover = () => {
    icons_section_items.classList.remove('visibility');
}
icons_section_items.onmouseout = () => {
    icons_section_items.classList.add('visibility');
} 