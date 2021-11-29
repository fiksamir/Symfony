const voteArrows = [...document.querySelectorAll('.vote-arrows a')];
voteArrows.map((arrow) => {
    arrow.addEventListener('click', (evt) => {
        evt.preventDefault();
        const target = evt.currentTarget;
        const direction = target.dataset?.direction;
        fetch(`/api/comments/1/vote/${direction}`)
            .then(async (res) => {
                const data = await res.json();
                target.parentNode.querySelector('span').innerHTML = data.count;
            });
    });
});