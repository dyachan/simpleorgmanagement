@include('components.monthday')
@include('components.calendarLabel')
@include('components.addWorklog')
<template id="som-viewcalendar-template">
    <style>
        * {
            box-sizing: border-box;
        }

        :host{
            position: relative;
        }

        .maincalendar, .labelcalendar{
            width: 100%;
            display: grid;
            grid-template-columns: 1fr repeat(5, 2fr) 1fr;
            grid-template-rows: 30px repeat(6, 120px);
            gap: 5px 5px;
        }

        .labelcalendar {
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
        }

        .maincalendar article{
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: flex-start;
            gap: 2px;
            overflow: hidden;
        }

        .maincalendar article.header{
            height: 30px;
            background-color: #0001;
        }
        .maincalendar article.monthday{
            height: 100%;
            background-color: #0002;
            position: relative;
        }

        .maincalendar article.monthday p.date,
        .maincalendar article.monthday button.createworklog{
            min-width: 20px;
            min-height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2px;
            margin: 2px;
            border-radius: 10px;
            border-width: 0px;
            background-color: #0005;
            color: white;
            font-size: small;
        }
        .maincalendar article.monthday button.createworklog{
            cursor: pointer;
        }
        .maincalendar article.monthday button.createworklog:hover{
            background-color: #000C;
        }

        .maincalendar article.monthday p.monthdate{
            min-width: 50px;
        }

        .maincalendar article.monthday p.today{
            background-color: #000F;
        }

        .maincalendar article.monthday button.createworklog{
            min-width: 20px;
            min-height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2px;
            margin: 2px;
            border-radius: 10px;
            background-color: #0005;
            color: white;
        }

        som-calendarlabel{
            overflow: hidden;
            pointer-events: auto;
        }

    </style>

    <section class="maincalendar">
        <article class="header">Dom</article>
        <article class="header">Lun</article>
        <article class="header">Mar</article>
        <article class="header">Mié</article>
        <article class="header">Jue</article>
        <article class="header">Vie</article>
        <article class="header">Sáb</article>
    </section>
    <section class="labelcalendar">
    </section>

    <som-addworklog id="addworklogcomponent" style="display: none;"></som-addworklog>
</template>

<script>
    // const _MONTH = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
    class SOM_ViewWorklogComponent extends HTMLElement {

        _changeAtt(name, value){
            if(name == "view"){
                this._options.defaultView = value;
            }
        }

        _fetchUserWorklog(userID){
            return fetch("/api/getuserworklog", {method: "POST", headers: {"Content-Type": "application/json"}, body: JSON.stringify({"userID": userID})})
            .then((response) => response.json())
            .then(({data}) => {
                // this._userCalendars.push({
                //     id: data.user,
                //     name: data.user,
                //     color: SOM_COLOR[Math.floor(Math.random() * SOM_COLOR.length)], 
                //     backgroundColor: "#000000", 
                // });

                data.worklogs.forEach( (worklog) => {
                    let startDate = new Date( isoFormatWithoutSeconds(worklog.start) );
                    let endDate = new Date( isoFormatWithoutSeconds(worklog.end) );
                    let daysBetweenFirstDay = Math.floor((startDate - this._firstDate.getTime()) / (24 * 60 * 60 * 1000));
                    
                    // check if must be added to calendar
                    if(endDate > this._firstDate && startDate < this._lastDate){
                        let label = document.createElement("som-calendarlabel");
                        this._worklogs.push(label);
                        this._labelContainer.appendChild(label);

                        let startDateIndex = Math.floor((startDate.getTime() - this._firstDate.getTime()) / (24 * 60 * 60 * 1000));
                        let endDateIndex = Math.floor((endDate.getTime() - this._firstDate.getTime()) / (24 * 60 * 60 * 1000));
                        let daysBetween = Math.floor((endDate.getTime() - startDate.getTime()) / (24 * 60 * 60 * 1000));

                        let currentStartColumn = startDate.getDay();
                        let currentEndColumn = Math.min(6, currentStartColumn + daysBetween)
                        let currentRow = Math.floor(startDateIndex / 7);

                        // update levels of day
                        let maxLevel = 0;
                        for (let index = startDateIndex; index <= endDateIndex; index++) {
                            if(this._dayLevels[index] > maxLevel){
                                maxLevel = this._dayLevels[index];
                            }
                            this._dayLevels[index]++;
                        }

                        label.changeAttrs((currentStartColumn+1) + " / " + (currentEndColumn+2), (currentRow+1), {
                            level: maxLevel,
                            offset: 25,
                            time: beautyDeltaTime(startDate, endDate),
                            content: worklog.description,
                            username: data.user,
                            proyect: worklog.proyect_name,
                            initdate: isoFormatWithoutSeconds(startDate.toJSON()),
                            enddate: isoFormatWithoutSeconds(endDate.toJSON()),
                            // backgroundColor: getDeterministicColor(worklog.proyect)+"CC",
                            backgroundColor: getDeterministicColor(data.user)+"CC",
                            user_id: userID,
                            proyect_id: worklog.proyect_id,
                            proyect_logo: worklog.proyect_logo,
                            worklog_id: worklog.id
                        });
                    }

                    // if(daysBetweenFirstDay >= 0 && daysBetweenFirstDay < 7 * this._weeks){
                    //     this._dayElems[daysBetweenFirstDay].addWorklog({
                    //         text: worklog.description,
                    //         time: beautyDeltaTime(startDate, endDate),
                    //         // borderColor: getDeterministicColor(data.user),
                    //         backgroundColor: getDeterministicColor(worklog.proyect)+"CC",
                    //         username: data.user,
                    //         proyect: worklog.proyect,
                    //         initdate: beautyTime(startDate),
                    //         enddate: beautyTime(endDate)
                    //     });
                    // }
                });
            }).catch((error) => {
                console.log("error", error);
            })
        }

        _addUsersWorklogs(){
            this._userCalendars = [];
            this._userWorklogs = [];
            this._users = (this.getAttribute("som-users") || "").replaceAll(" ", "").split(",");
            if(this._users.length == 1 && this._users[0] == ""){ // if no user selected, select current loggued user.
                this._users = ["{{ Auth::user()->id }}"];
            }

            return this._users.map( (userID) => this._fetchUserWorklog(userID));
        }

        constructor() {
            super();

            let template = document.getElementById("som-viewcalendar-template");
            let templateContent = template.content;

            this._shadowRoot = this.attachShadow({ mode: "open" });
            this._shadowRoot.appendChild(templateContent.cloneNode(true));

            this._daysContainer = this._shadowRoot.querySelectorAll(".maincalendar")[0];
            this._labelContainer = this._shadowRoot.querySelectorAll(".labelcalendar")[0];

            this._firstDate = null;
            this._lastDate = null;
            this._weeks = 8;
            this._dayElems = [];
            this._dayLevels = [];

            this._worklogs = [];
        }
        
        connectedCallback(){

            // get first date of calendar
            this._firstDate = new Date();
            if(this.getAttribute("som-firstdate")){
                this._firstDate = new Date(this.getAttribute("som-firstdate"));
                this._firstDate.setDate(this._firstDate.getDate() - this._firstDate.getDay());
            } else {
                this._firstDate.setDate(this._firstDate.getDate() - this._firstDate.getDay() - 7*Math.floor(this._weeks/2));
            }
            this._firstDate.setHours(0);
            this._firstDate.setMinutes(0);
            this._firstDate.setSeconds(0);

            // build calendar
            this._dayElems = [];
            this._dayLevels = [];
            this._lastDate = new Date(this._firstDate.getTime());
            for (let day = 0; day < 7 * this._weeks; day++) {
                this._dayLevels.push(0);
                this._dayElems.push(document.createElement("som-monthday"));
                this._daysContainer.appendChild(this._dayElems[day]);
                this._dayElems[day].setDate(this._lastDate);

                this._lastDate.setDate(this._lastDate.getDate() +1);
            }

            Promise.all(this._addUsersWorklogs()).then( () => {
                // -
            });
        }

        attributeChangedCallback(name, oldValue, newValue) {
            // if(oldValue != newValue){
            //     this._changeAtt(name, newValue);
            // }
        }


    }

    customElements.define("som-viewworklog", SOM_ViewWorklogComponent);
</script>
