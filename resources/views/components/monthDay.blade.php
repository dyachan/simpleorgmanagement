<template id="som-monthday-template">
  <article class="monthday">
    <button class="createworklog">+</button>
    <p class="date"></p>
  </article>
</template>

<script>
  const _MONTH = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  class SOM_MonthDayComponent extends HTMLElement {
    constructor() {
      super();

      this.monthday_template = document.getElementById("som-monthday-template");
      this.workloglabel_template = document.getElementById("som-workloglabel-template");

      this.date = null;

    }
    
    connectedCallback(){
      this.appendChild(document.importNode(this.monthday_template.content.cloneNode(true), true));
      this._main = this.querySelectorAll("article")[0];
      this._createWorklogButton = this.querySelectorAll("button")[0];

      this._createWorklogButton.addEventListener("click", (evt) => {
        ADDWORKLOGCOMPONENT.show()
        let dateString = this.date.toJSON().split("T")[0]
        ADDWORKLOGCOMPONENT.setAttributes({
          start: dateString+"T00:00",
          end: dateString+"T"+(new Date()).toJSON().split("T")[1].split(".")[0].split(":").slice(0, -1).join(":")
        });
      });
    }

    setDate(date){
      this.date = new Date(date);
      date.setHours(0,0,0,0);
      let dateElem = this.querySelectorAll("p")[0];
      dateElem.classList.add("date");
      dateElem.textContent = this.date.getDate();
      
      // check if is first day of month
      if(this.date.getDate() == 1){
          dateElem.textContent += " "+_MONTH[this.date.getMonth()];
          dateElem.classList.add("monthdate");
      }

      // check if today
      if(this.date.toDateString() == (new Date()).toDateString()){
          dateElem.classList.add("today");
      }
    }
  }

  customElements.define("som-monthday", SOM_MonthDayComponent);
</script>
