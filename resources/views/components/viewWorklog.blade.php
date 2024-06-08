<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
<template id="som-viewworklog-template">
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <style>
        .toastui-calendar-section-button{
            display: none;
        }
        .toastui-calendar-detail-item{
            display: none;
        }
        .toastui-calendar-detail-item:first-child{
            display: block;
        }
    </style>

    <div style="height: 100%;"></div>
</template>

<script>
    const options = {
        defaultView: 'month',
        isReadOnly: true,
        timezone: {
            zones: [
                {
                    timezoneName: 'America/Santiago'
                }
            ],
        },
        // calendars: [
        //     @foreach ($users as $user)
        //     {
        //         id: "{{ $user->email }}",
        //         name: "{{ $user->email }}",
        //         backgroundColor: SOM_COLOR[Math.floor(Math.random() * SOM_COLOR.length)], 
        //     },
        //     @endforeach
        // ],
        week: {
          startDayOfWeek: 1,
          dayNames: [],
          narrowWeekend: false,
          workweek: false,
          showNowIndicator: false,
          showTimezoneCollapseButton: false,
          timezonesCollapsed: false,
          hourStart: 0,
          hourEnd: 24,
          eventView: ['time'],
          taskView: false,
          collapseDuplicateEvents: false,
        },
    };
    
    class SOM_ViewWorklogComponent extends HTMLElement {
        _changeAtt(name, value){
            if(name == "view"){
                this._options.defaultView = value;
            }
            if(name == "view"){
                this._options.defaultView = value;
            }
        }

        _fetchUserWorklog(userID){
            return fetch("/api/getuserworklog", {method: "POST", headers: {"Content-Type": "application/json"}, body: JSON.stringify({"userID": userID})})
            .then((response) => response.json())
            .then(({data}) => {
                // add to calendar
                // let tempCal = []; // need a temp array because array become inmutable after setOptions
                // this._userCalendars.forEach((e) => tempCal.push(e));
                // tempCal.push({
                //     id: data.id,
                //     name: data.user,
                //     backgroundColor: SOM_COLOR[Math.floor(Math.random() * SOM_COLOR.length)], 
                // });
                // this._userCalendars = tempCal;

                this._userCalendars.push({
                    id: data.id,
                    name: data.user,
                    backgroundColor: SOM_COLOR[Math.floor(Math.random() * SOM_COLOR.length)], 
                });

                data.worklogs.forEach( (worklog) => 
                    this._userWorklogs.push({
                        id: worklog.id,
                        calendarId: data.id,
                        title: worklog.description.replaceAll("\n", " Y "),
                        start: worklog.start,
                        end: worklog.end,
                    })
                );

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

            let template = document.getElementById("som-viewworklog-template");
            let templateContent = template.content;

            this._shadowRoot = this.attachShadow({ mode: "open" });
            this._shadowRoot.appendChild(templateContent.cloneNode(true));

            this._container = this._shadowRoot.querySelectorAll("div")[0];

            this._users = [];
            this._userCalendars = [];
            this._userWorklogs = [];
            this._options = {
                defaultView: 'month',
                isReadOnly: true,
                useDetailPopup: true,
                timezone: {
                    zones: [
                        {
                            timezoneName: 'America/Santiago'
                        }
                    ],
                },
                week: {
                    startDayOfWeek: 1,
                    dayNames: [],
                    narrowWeekend: false,
                    workweek: false,
                    showNowIndicator: false,
                    showTimezoneCollapseButton: false,
                    timezonesCollapsed: false,
                    hourStart: 0,
                    hourEnd: 24,
                    eventView: ['time'],
                    taskView: false,
                    collapseDuplicateEvents: false,
                },
                template: {
                    popupDetailTitle({ title }) {
                        return "<span>"+title.split(" Y ").map( (task) => "<span>"+task+"</span>").join("<br>")+"</span>"
                    },
                    popupDetailDate({ start, end }) {
                        return beautyTime(start) + " - " + beautyTime(end);
                    },
                    popupDetailAttendees(params) {
                        return params.calendarId;
                    },
                    popupDetailBody({ body }) {
                        return "";
                    }
                }
            };

            // calendar.createEvents([
            //     @foreach ($worklogs as $worklog)
            //         {
            //             id: "{{ $worklog->id }}",
            //             calendarId: "{{ $worklog->user->email }}",
            //             title: '{{ str_replace("\n", " Y ", $worklog->description) }}',
            //             start: "{{ $worklog->start }}",
            //             end: "{{ $worklog->end }}",
            //         },
            //     @endforeach
            // ]);
        }
        
        connectedCallback(){
            this._changeAtt("view", this.getAttribute("som-view") || "week");
            Promise.all(this._addUsersWorklogs()).then( () => {
                this._calendar = new tui.Calendar(this._container, this._options);

                // user calendars
                this._calendar.setOptions({
                    calendars: this._userCalendars
                });

                // add worklogs
                this._calendar.createEvents(this._userWorklogs);

            });
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if(oldValue != newValue){
                this._changeAtt(name, newValue);
            }
        }


    }

    customElements.define("som-viewworklog", SOM_ViewWorklogComponent);
</script>
