let date = new Date();
let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();
if (month < 10) {
    var date_now = `${year}-0${month}-${day}`;
} else {
    var date_now = `${year}-${month}-${day}`;
}

export default date_now;
