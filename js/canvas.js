/* 
    Taken from stackoverflow, thanks for the support! :) 
    http://stackoverflow.com/users/411591/marke
*/

$(document).ready(function(){

        var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");

    // set context styles
    ctx.lineWidth = 40;
    ctx.strokeStyle = '#85c3b8';
    ctx.shadowColor = "black"
    ctx.shadowOffsetX = 2;
    ctx.shadowOffsetY = 2;
    ctx.shadowBlur = 1;
    ctx.font = "60px Oswald";

    var quart = Math.PI / 2;
    var PI2 = Math.PI * 2;
    var percent = 0;

    var guages = [];
    guages.push({
        x: 630,
        y: 405,
        radius: 300,
        start: 0,
        end: 100,
        color: "white"
    });

/*    guages.push({
        x: 200,
        y: 100,
        radius: 40,
        start: 0,
        end: 90,
        color: "green"
    });
    guages.push({
        x: 50,
        y: 225,
        radius: 40,
        start: 0,
        end: 35,
        color: "gold"
    });
    guages.push({
        x: 200,
        y: 225,
        radius: 40,
        start: 0,
        end: 55,
        color: "purple"
    });
*/
    animate();

    function drawAll(percent) {

        // clear the canvas

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // draw all the guages

        for (var i = 0; i < guages.length; i++) {
            render(guages[i], percent);
        }

    }

    function render(guage, percent) {
        var pct = percent / 100;
        var extent = parseInt((guage.end - guage.start) * pct);
        var current = (guage.end - guage.start) / 100 * PI2 * pct - quart;
        if ( percent < 100 )
        {
            ctx.beginPath();
            ctx.arc(guage.x, guage.y, guage.radius, -quart, current);
            ctx.strokeStyle = guage.color;
            ctx.lineCap = "round";
            ctx.stroke();
            ctx.fillStyle = guage.color;
            ctx.fillText("Cargando...", guage.x - 120, guage.y + 10);
        }
        else
        {
            ctx.beginPath();
            ctx.arc(guage.x, guage.y, guage.radius, -quart, current);
            ctx.fillStyle = '#610B0B';
            ctx.fill();
            ctx.strokeStyle = "white";
            ctx.lineCap = "round";
            ctx.stroke();
            base_image = new Image();
            base_image.src = './img/logo.png';
            ctx.drawImage(base_image, guage.x - 150, guage.y - 60);
            setInterval(function(){
                $("#canvaspos").fadeOut(1000);
                $(".contents").empty().append("");
                setInterval(function(){
                    window.location.href='http://localhost:8080/userpanel/home.php?jq=1';
                }, 1500);
            }, 1500);
        }
    }


    function animate() {

        // if the animation is not 100% then request another frame

        if (percent < 100) {
            requestAnimationFrame(animate);
        }

        // redraw all guages with the current percent

        drawAll(percent);

        // increase percent for the next frame

        percent += 1;

    }
});