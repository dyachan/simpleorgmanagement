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
        defaultView: 'week',
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
            console.log(name, value);
            if(name == "view"){
                this._calendar.setOptions({
                    defaultView: value,
                });
            }
        }

        constructor() {
            super();

            let template = document.getElementById("som-viewworklog-template");
            let templateContent = template.content;

            this._shadowRoot = this.attachShadow({ mode: "open" });
            this._shadowRoot.appendChild(templateContent.cloneNode(true));

            this._container = this._shadowRoot.querySelectorAll("div")[0];

            this._calendar = new tui.Calendar(this._container, options);

            this._calendar.setOptions({
                useDetailPopup: true,
            });

            this._calendar.setOptions({
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
                    },
                },
            });
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
            console.log("asdasd");
            console.log(this.getAttribute("som-view"));
            this._changeAtt("view", this.getAttribute("som-view") || "week");
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if(name == "view" && oldValue != newValue){
                this._changeAtt(name, newValue);
            }
        }
    }

    customElements.define("som-viewworklog", SOM_ViewWorklogComponent);
</script>
