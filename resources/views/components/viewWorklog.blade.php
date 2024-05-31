<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
<template>
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

    <div id="calendar" style="height: 600px;"></div>
</template>

<script>
    function twoDigits(num){
        if(parseInt(num) < 10){
            return "0"+num;
        }
        return num;
    }
    function beautyTime(datetime){
        return twoDigits(datetime.getHours()) + ":" + twoDigits(datetime.getSeconds());
    }

    const colors = [
        '#03bd9e', '#c1bde8', '#ceace6', '#daadae', '#d6c49e', '#93c085', '#80ffff', '#ffa87d'
    ]

    const Calendar = tui.Calendar;
    const container = document.getElementById('calendar');
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
        calendars: [
            @foreach ($users as $user)
            {
                id: "{{ $user->email }}",
                name: "{{ $user->email }}",
                backgroundColor: colors[Math.floor(Math.random() * colors.length)], 
            },
            @endforeach
        ],
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

    const calendar = new Calendar(container, options);
    calendar.setOptions({
        useDetailPopup: true,
    });
    // calendar.setOptions({
    //   template: {
    //     time(event) {
    //       const { start, end, title } = event;
    //       return `<span style="color: white;">${title}</span>`;
    //     }
    //   },
    // });
    calendar.setOptions({
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
    calendar.createEvents([
        @foreach ($worklogs as $worklog)
            {
                id: "{{ $worklog->id }}",
                calendarId: "{{ $worklog->user->email }}",
                title: '{{ str_replace("\n", " Y ", $worklog->description) }}',
                start: "{{ $worklog->start }}",
                end: "{{ $worklog->end }}",
            },
        @endforeach
    ]);
    
    class SOM_ViewWorklogComponent extends HTMLElement {
        constructor() {
            super();

            let template = document.getElementById("som-addworklog-template");
            let templateContent = template.content;

            const shadowRoot = this.attachShadow({ mode: "open" });
            shadowRoot.appendChild(templateContent.cloneNode(true));
        }
    }

    customElements.define("som-addworklog", SOM_AddWorklogComponent);
</script>
