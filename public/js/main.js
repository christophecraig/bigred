const toggleBurgerMenu = () => {
    document.getElementById('burger-button').classList.toggle('is-active');
    document.getElementById('navbar').classList.toggle('is-active');
}

document.getElementById('burger-button').addEventListener('click', toggleBurgerMenu)