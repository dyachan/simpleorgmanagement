<template id="som-monthday-template">
  <article class="monthday">
    <p class="date"></p>
  </article>
</template>

<template id="som-workloglabel-template">
  <label>
    <span class="worklogtime"></span>
    <span class="worklogcontent"></span>
    <som-worklogdialog><som-worklogdialog>
  </label>
</template>

<script>
  const _MONTH = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  class SOM_MonthDayComponent extends HTMLElement {
    constructor() {
      super();

      this.monthday_template = document.getElementById("som-monthday-template");
      this.workloglabel_template = document.getElementById("som-workloglabel-template");

    }
    
    connectedCallback(){
      this.appendChild(document.importNode(this.monthday_template.content.cloneNode(true), true));
      this._main = this.querySelectorAll("article")[0];
    }

    setDate(date){
      let dateElem = this.querySelectorAll("p")[0];
      dateElem.classList.add("date");
      dateElem.textContent = date.getDate();
      
      // check if is first day of month
      if(date.getDate() == 1){
          dateElem.textContent += " "+_MONTH[date.getMonth()];
          dateElem.classList.add("monthdate");
      }

      // check if today
      if(date.setHours(0,0,0,0) == (new Date()).setHours(0,0,0,0)){
          dateElem.classList.add("today");
      }
    }

    addWorklog({text="", time=null, backgroundColor="#0000", borderColor="#00000000", username=null, proyect=null, initdate=null, enddate=null}){
      let worklogElem = this.workloglabel_template.content.cloneNode(true)
      let node = document.importNode(worklogElem, true)
      this._main.appendChild(node);
  
      if(time){
          let timeElm = this._main.querySelectorAll(".worklogtime")[0];
          timeElm.textContent = time;
      }
  
      let labelElem = this._main.querySelectorAll("Label")[0];
      labelElem.style.backgroundColor = backgroundColor;
      labelElem.style.borderColor = borderColor;
  
      let contentElm = this._main.querySelectorAll(".worklogcontent")[0];
      contentElm.textContent = text.trim().split("\n").join(" & ");
  
      // append worklog dialog
      let worklogDialogElem = this._main.querySelectorAll("som-worklogdialog")[0];
      worklogDialogElem.setAttribute("som-user", username);
      worklogDialogElem.setAttribute("som-proyect", proyect);
      worklogDialogElem.setAttribute("som-initdate", initdate);
      worklogDialogElem.setAttribute("som-enddate", enddate);
      worklogDialogElem.setAttribute("som-background", backgroundColor);
      worklogDialogElem.setAttribute("som-info", text);
  
      labelElem.addEventListener("click", (evt) => {
          if(worklogDialogElem.open){
              worklogDialogElem.close();
          } else {
              worklogDialogElem.show();
              worklogDialogElem.moveTo(evt.clientX, evt.clientY);
          }
      })
    }
  }

  customElements.define("som-monthday", SOM_MonthDayComponent);
</script>
