@include('components.calendarLabel')
@include('components.monthday')
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
        }

        .maincalendar article{
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
            justify-content: flex-start;
            position: relative;
        }

        .maincalendar article.monthday p.date{
            position: absolute;
            min-width: 20px;
            min-height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 0px;
            right: 0px;
            padding: 2px;
            margin: 2px;
            border-radius: 10px;
            background-color: #0005;
            color: white;
        }

        .maincalendar article.monthday p.monthdate{
            min-width: 50px;
        }

        .maincalendar article.monthday p.today{
            background-color: #000F;
        }

        .maincalendar article label{
            width: 100%;
            min-height: 20px;
            border-width: medium;
            border-style: solid;
            cursor: pointer;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .maincalendar article label span.worklogtime{
            font-style: italic;
            font-size: small;
            opacity: 0.6;
            margin-right: 2px;
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
      <som-calendarlabel som-gridcolumn="2 / 4" som-gridrow="2"></som-calendarlabel>
      <som-calendarlabel som-gridcolumn="3 / 5" som-gridrow="3"></som-calendarlabel>
      <som-calendarlabel som-gridcolumn="3 / 6" som-gridrow="2" som-offset="1"></som-calendarlabel>
    </section>
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
                    let startDate = new Date(worklog.start);
                    let endDate = new Date(worklog.end);
                    let daysBetweenFirstDay = Math.floor((startDate - this._firstDate.getTime()) / (24 * 60 * 60 * 1000));
                    
                    if(daysBetweenFirstDay >= 0 && daysBetweenFirstDay < 7 * this._weeks){
                        this._dayElems[daysBetweenFirstDay].addWorklog({
                            text: worklog.description,
                            time: beautyDeltaTime(startDate, endDate),
                            // borderColor: getDeterministicColor(data.user),
                            backgroundColor: getDeterministicColor(worklog.proyect)+"CC",
                            username: data.user,
                            proyect: worklog.proyect,
                            initdate: beautyTime(startDate),
                            enddate: beautyTime(endDate)
                        });
                    }
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

            this._container = this._shadowRoot.querySelectorAll("section")[0];

            this._firstDate = null;
            this._weeks = 8;
            this._dayElems = [];
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

            // build calendar
            let currentDate = new Date(this._firstDate.getTime());
            for (let day = 0; day < 7 * this._weeks; day++) {
                this._dayElems.push(document.createElement("som-monthday"));
                this._container.appendChild(this._dayElems[day]);
                this._dayElems[day].setDate(currentDate);

                currentDate.setDate(currentDate.getDate() +1);
            }

            // Promise.all(this._addUsersWorklogs()).then( () => {
            //     // -
            // });
        }

        attributeChangedCallback(name, oldValue, newValue) {
            // if(oldValue != newValue){
            //     this._changeAtt(name, newValue);
            // }
        }


    }

    customElements.define("som-viewworklog", SOM_ViewWorklogComponent);
</script>
