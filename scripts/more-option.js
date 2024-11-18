const onHover = (index) => {
    const moreIcon = document.getElementById('more-icon' + index);

    moreIcon.classList.remove('right-[-5rem]');
    moreIcon.classList.add('right-[-1.2rem]');
}

const onLeave = (index) => {
    const moreIcon = document.getElementById('more-icon' + index);

    moreIcon.classList.remove('right-[-1.2rem]');
    moreIcon.classList.add('right-[-5rem]');
}

const openMore = (index) => {
    const moreIcon = document.getElementById('more-icon' + index);
    
    moreIcon.classList.remove('top-[-1.3rem]', 'right-[-1.2rem]', 'right-[-5rem]', 'closed-more');
    moreIcon.classList.add('open-more', 'right-0');
    document.getElementById('more-img' + index).classList.add('hidden')
    document.getElementById('open-options' + index).classList.remove('hidden');
}

const closeMore = (index) => {
    const moreIcon = document.getElementById('more-icon' + index);

    moreIcon.classList.add('top-[-1.3rem]', 'right-[-5rem]', 'closed-more');
    moreIcon.classList.remove('open-more', 'right-0');
    document.getElementById('more-img' + index).classList.remove('hidden')
    document.getElementById('open-options' + index).classList.add('hidden');
}