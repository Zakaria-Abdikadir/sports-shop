function updateClock(){

    const now = new Date();

    document.getElementById("liveClock").innerHTML =
        now.toLocaleString();

}

setInterval(updateClock,1000);

updateClock();