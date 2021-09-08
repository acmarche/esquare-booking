document.addEventListener("DOMContentLoaded", function () {
    var hidden = document.querySelector('#entry_api_form_room');
    hidden.value = document.querySelector('#entry_room').value;//set to form
});

async function getEntries(date, room) {

    setDate(date);

    const url = `/wp-json/booking/entries/${date}/${room}`;
    const response = await fetch(url);
    let dataJson = await response.text();
    document.querySelector("#contentmodal").innerHTML = JSON.parse(dataJson);
}

async function getCalendar(date, room, action) {
    const url = `/wp-json/booking/calendar/${date}/${room}/${action}`;
    const response = await fetch(url);
    let dataJson = await response.text();
    document.querySelector("#booking").innerHTML = JSON.parse(dataJson);
}

function setDate(date) {
    var fieldDate = document.querySelector('#entry_api_form_jour');
    console.log(date);
    fieldDate.value = date;
}
