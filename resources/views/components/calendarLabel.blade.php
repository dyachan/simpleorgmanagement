@include('components.worklogDialog')

<template id="som-calendarlabel-template">
  <style>
    .calendarlabel{
      display: flex;
      background-color: #0005;
      min-height: 20px;
      max-height: 20px;
      height: 20px;
      width: 100%;
      border-radius: 3px;
      padding: 3px;
      cursor: pointer;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      /* border-width: 0 0 0 thick; */
      border-width: 0;
      border-style: solid;
      border-color: #0000;
    }

    label span.worklogtime{
      font-style: italic;
      font-size: small;
      opacity: 0.6;
      margin-right: 2px;
    }

    img.worklogproyect{
      height: 90%;
    }
  </style>
    
  <label class="calendarlabel">
    <img class="worklogproyect"></span>
    <span class="worklogtime"></span>
    <span class="worklogcontent"></span>
    <som-worklogdialog><som-worklogdialog>
  </label>
</template>

<script>
  class SOM_CalendarLabelComponent extends HTMLElement {
    constructor() {
      super();

      let template = document.getElementById("som-calendarlabel-template");
      let templateContent = template.content;

      this._root = document.importNode(templateContent.cloneNode(true), true);
      this._label = this._root.querySelectorAll(".calendarlabel")[0]; 
      this._proyectlogo = this._root.querySelectorAll(".worklogproyect")[0];
      this._time = this._root.querySelectorAll(".worklogtime")[0];
      this._content = this._root.querySelectorAll(".worklogcontent")[0];
      this._dialog = this._root.querySelectorAll("som-worklogdialog")[0];

    }

    _build(){
      this.changeAttrs(this.getAttribute("som-gridcolumn"), this.getAttribute("som-gridrow"), {
        level: parseInt(this.getAttribute("som-level") || 0),
        offset: parseInt(this.getAttribute("som-offset") || 0),
        time: this.getAttribute("som-time") || "",
        content: this.getAttribute("som-content") || ""
      });
    }

    changeAttrs(gridcolumn, gridrow, {
      level=0, 
      offset=0, 
      content="", 
      username="", 
      proyect="", 
      initdate="", 
      enddate="", 
      backgroundColor="#0005", 
      user_id="0", 
      proyect_id="0", 
      proyect_logo=null, 
      worklog_id=null
    }){
      if(!gridcolumn || !gridrow){
        return;
      }

      // positionate
      this.style.gridColumn = gridcolumn;
      this.style.gridRow = (gridrow+1) +" / "+ (gridrow+1); // add 1 to skip calendar header
      this.style.marginTop = ( offset + level * 21 )+"px";
      
      // content
      this._proyectlogo.src = proyect_logo;
      this._time.textContent = beautyDeltaTime(initdate, enddate);
      this._content.textContent = content;
      this._label.style.backgroundColor = backgroundColor;
      // this._label.style.borderColor = getDeterministicColor(username);

      // dialog
      this._dialog.setAttribute("som-user", username);
      this._dialog.setAttribute("som-proyect", proyect);
      this._dialog.setAttribute("som-initdate", initdate);
      this._dialog.setAttribute("som-enddate", enddate);
      this._dialog.setAttribute("som-background", backgroundColor);
      this._dialog.setAttribute("som-info", content);
      this._dialog.setAttribute("som-proyectid", user_id);
      this._dialog.setAttribute("som-userid", proyect_id);
      this._dialog.setAttribute("som-worklogid", worklog_id);
      this._dialog.connectedCallback(); // why I need do this?
      this._label.addEventListener("click", (evt) => {
          if(this._dialog.open){
              this._dialog.close();
          } else {
              this._dialog.show();
              this._dialog.moveTo(evt.clientX, evt.clientY);
          }
      })

    }

    connectedCallback(){
      this.appendChild(this._root);
      this._build();
    }

    attributeChangedCallback(name, oldValue, newValue) {
      this._build();
    }

  }

  customElements.define("som-calendarlabel", SOM_CalendarLabelComponent);
</script>
