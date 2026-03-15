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

let hideTimeout;

const showItems = (items) => {
    clearTimeout(hideTimeout);
    items.classList.remove('visibility');
};

const hideItems = (items) => {
    hideTimeout = setTimeout(() => {
        items.classList.add('visibility');
    }, 100); // 100ms delay to allow moving between nav and dropdown
};

men_section.onmouseover = () => showItems(men_section_items);
men_section.onmouseout = () => hideItems(men_section_items);
men_section_items.onmouseover = () => showItems(men_section_items);
men_section_items.onmouseout = () => hideItems(men_section_items);

women_section.onmouseover = () => showItems(women_section_items);
women_section.onmouseout = () => hideItems(women_section_items);
women_section_items.onmouseover = () => showItems(women_section_items);
women_section_items.onmouseout = () => hideItems(women_section_items);

kids_section.onmouseover = () => showItems(kids_section_items);
kids_section.onmouseout = () => hideItems(kids_section_items);
kids_section_items.onmouseover = () => showItems(kids_section_items);
kids_section_items.onmouseout = () => hideItems(kids_section_items);

home_section.onmouseover = () => showItems(home_section_items);
home_section.onmouseout = () => hideItems(home_section_items);
home_section_items.onmouseover = () => showItems(home_section_items);
home_section_items.onmouseout = () => hideItems(home_section_items);

beauty_section.onmouseover = () => showItems(beauty_section_items);
beauty_section.onmouseout = () => hideItems(beauty_section_items);
beauty_section_items.onmouseover = () => showItems(beauty_section_items);
beauty_section_items.onmouseout = () => hideItems(beauty_section_items);

icons_section.onmouseover = () => showItems(icons_section_items);
icons_section.onmouseout = () => hideItems(icons_section_items);
icons_section_items.onmouseover = () => showItems(icons_section_items);
icons_section_items.onmouseout = () => hideItems(icons_section_items);