<template id="som-calendarlabel-template">
  <style>
    .calendarlabel{
      display: flex;
      background-color: #0005;
      height: 20px;
      width: 100%;
    }
  </style>
    
  <label class="calendarlabel">
  </label>
</template>

<script>
  class SOM_CalendarLabelComponent extends HTMLElement {
    constructor() {
      super();

      let template = document.getElementById("som-calendarlabel-template");
      let templateContent = template.content;

      this._root = document.importNode(templateContent.cloneNode(true), true);
    }

    connectedCallback(){
      this.appendChild(this._root);

      this.style.gridColumn = this.getAttribute("som-gridcolumn");
      this.style.gridRow = this.getAttribute("som-gridrow") +" / "+ this.getAttribute("som-gridrow");
      this.style.marginTop = (25 + parseInt(this.getAttribute("som-offset") || 0)*21)+"px";
    }
  }

  customElements.define("som-calendarlabel", SOM_CalendarLabelComponent);
</script>
