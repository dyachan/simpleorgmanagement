<script>
    /** random colors to use */
    const SOM_COLOR = [
        '#03bd9e', '#c1bde8', '#ceace6', '#daadae', '#d6c49e', '#93c085', '#80ffff', '#ffa87d'
    ]

    /** return a number as 2 digit string */
    function twoDigits(num){
        if(parseInt(num) < 10){
            return "0"+num;
        }
        return num;
    }

    /** return datetime as string format hh:mm */
    function beautyTime(datetime){
        const d = new Date(datetime);
        return twoDigits(d.getHours()) + ":" + twoDigits(d.getMinutes());
    }
    
    /** return datetime as string format hh:ss */
    function beautyDeltaTime(firstDatetime, lastDatetime){
        const d1 = new Date(firstDatetime);
        const d2 = new Date(lastDatetime);

        // get seconds
        let delta = Math.floor(Math.abs(d2.getTime() - d1.getTime()) / 1000);
        let moduleOp = delta % 60 
        let beautyString = twoDigits(moduleOp) + "s";

        // get minutes
        delta = Math.floor(delta / 60);
        if(delta == 0){
            return beautyString;
        }
        moduleOp = delta % 60 
        // beautyString = twoDigits(moduleOp) + "m" + beautyString;
        beautyString = twoDigits(moduleOp);

        // get hours
        delta = Math.floor(delta / 60);
        if(delta == 0){
            return beautyString;
        }
        moduleOp = delta % 24 
        beautyString = twoDigits(moduleOp) + "h" + beautyString;

        // get days
        delta = Math.floor(delta / 24);
        if(delta == 0){
            return beautyString;
        }
        moduleOp = delta % 24 
        beautyString = moduleOp + "d" + beautyString;

        return beautyString;
    }

    function getDeterministicColor(someString){
        let newWord = someString.toLowerCase();
        let randomNum = [
            newWord.length,
            newWord.split('a').length +1,
            newWord.split('e').length +1,
            newWord.split('i').length +1,
            newWord.split('o').length +1,
            newWord.split('u').length +1
        ].reduce( (accum, curr) => accum * curr, 1 );

        while(randomNum < 16777215){
            randomNum *= 8936684;
        }
        randomNum %= 16777215;

        color = randomNum.toString(16);

        // add character if color dont has 6 digits
        char2add = (newWord.length % 16).toString(16);
        for (let index = 0; index < 6 - color.length; index++) {
            color += char2add;
        }

        // arbitrary reorder digits
        color = color[4] + color[2] + color[0] + color[5] + color[3] + color[1]

        return '#'+color;
    }
</script>
