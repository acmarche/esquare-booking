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
