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
        return twoDigits(datetime.getHours()) + ":" + twoDigits(datetime.getMinutes());
    }
    
    /** return datetime as string format hh:ss */
    function beautyDeltaTime(firstDatetime, lastDatetime){
        // get seconds
        let delta = Math.floor(Math.abs(lastDatetime.getTime() - firstDatetime.getTime()) / 1000);
        let moduleOp = delta % 60 
        let beautyString = twoDigits(moduleOp) + "s";

        // get minutes
        delta = Math.floor(delta / 60);
        if(delta == 0){
            return beautyString;
        }
        moduleOp = delta % 60 
        beautyString = twoDigits(moduleOp) + "m" + beautyString;

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
</script>
