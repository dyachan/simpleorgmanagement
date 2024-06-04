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

    /** return datetime as string format hh:ss */
    function beautyTime(datetime){
        return twoDigits(datetime.getHours()) + ":" + twoDigits(datetime.getSeconds());
    }
</script>
