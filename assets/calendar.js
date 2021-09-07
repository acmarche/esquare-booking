document.addEventListener("DOMContentLoaded", function () {
    var btns = document.querySelectorAll('.bookingclick');
    Array.prototype.forEach.call(btns, function (el, i) {
        el.addEventListener('click', () => {
            console.log('click ' + el.dataset.day);
            getEntries(el.dataset.day, el.dataset.room);
        });
    });
});

async function getEntries(date, room) {
    const url = `/wp-json/booking/entries/${date}/${room}`;
    const response = await fetch(url);
    let dataJson = await response.text();
    document.querySelector("#contentmodal").innerHTML = JSON.parse(dataJson);
}

document.addEventListener("DOMContentLoaded", function () {
    var btn = document.querySelector('#btn-previous');
    btn.addEventListener('click', () => {
        console.log('click previous' + btn.dataset.month);
        getCalendar(btn.dataset.month, btn.dataset.room, 1);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var btn = document.querySelector('#btn-next');
    btn.addEventListener('click', () => {
        console.log('click next' + btn.dataset.month);
        getCalendar(btn.dataset.month, btn.dataset.room, 2);
    });
});

async function getCalendar(date, room, action) {
    const url = `/wp-json/booking/calendar/${date}/${room}/${action}`;
    const response = await fetch(url);
    let dataJson = await response.text();
    //console.log(dataJson);
    document.querySelector("#booking").innerHTML = JSON.parse(dataJson);
}
