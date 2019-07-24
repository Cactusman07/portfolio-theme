$( document ).ready(function() {
    if ($("body").hasClass("page-template-page-portfolio")){
        var matrixDrop = function() {
            var c = document.getElementById("matrix"),
                ctx2 = c.getContext("2d");

            c.width = window.innerWidth;
            c.height = window.innerHeight;
            c.style.display = 'block';
            c.style.width = '100%';
            c.style.height = '100%';

            //characters
            var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            //converting the string into an array of single characters
            characters = characters.split("");

            var font_size = 10;
            var columns = c.width/font_size; //number of columns for the rain
            //an array of drops - one per column
            var drops = [];
            //x below is the x coordinate
            //1 = y co-ordinate of the drop(same for every drop initially)
            for(var x = 0; x < columns; x++)
                drops[x] = 1; 

            //drawing the characters
            function draw()
            {
                //Black BG for the canvas
                //translucent BG to show trail
                ctx2.fillStyle = "rgba(0, 0, 0, 0.05)";
                ctx2.fillRect(0, 0, c.width, c.height);
                
                ctx2.fillStyle = "#0F0"; //green text
                ctx2.font = font_size + "px arial";
                //looping over drops
                for(var i = 0; i < drops.length; i++)
                {
                    //a random character to print
                    var text = characters[Math.floor(Math.random()*characters.length)];
                    //x = i*font_size, y = value of drops[i]*font_size
                    ctx2.fillText(text, i*font_size, drops[i]*font_size);
                    
                    //sending the drop back to the top randomly after it has crossed the screen
                    //adding a randomness to the reset to make the drops scattered on the Y axis
                    if(drops[i]*font_size > c.height && Math.random() > 0.975)
                        drops[i] = 0;
                    
                    //incrementing Y coordinate
                    drops[i]++;
                }
            }
            setInterval(draw, 33);
        }
        window.onload = function() {
            matrixDrop();
        };

        window.onresize = function(){
            var c = document.getElementById("matrix");

            c.width = window.innerWidth;
            c.height = window.innerHeight;
        }; 
    }
});