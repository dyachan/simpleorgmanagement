<template id="som-worklogdialog-template">
  <style>
    * {
        box-sizing: border-box;
    }

    dialog {
      position: fixed;
      background-color: #0005;

      height: 100vh;
      width: 100vw;
      top: 0px;
      left: 0px;

      z-index: 100;
    }

    section {
      position: absolute;
      background-color: white;

      height: 200px;
      width: 323px; /* Golden ratio: w = h * 1.618 */

      border-radius: 5px;
    }

    article {
      width: 100%;
      height: 100%;
      border-width: medium;
      border-style: solid;

      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;
    }

    button {
      align-self: flex-end;
    }

  </style>

  <dialog>
    <section>
      <article>
        <span>
          <span>&#8594;</span><span class="username"></span>
        </span>
  
        <span>
          <span>&#8608;</span><span class="proyect"></span>
        </span>
  
        <pre></pre>
    
        <span>
          <span>&#9201;</span> <span class="initdate"></span> <span>&#8596;</span> <span class="enddate"></span>
        </span>
    
        <div style="flex-grow: 2;"></div>

        <button>modificar</button>
      </article>
    </section>
  </dialog>
</template>

<script>
  class SOM_WorklogDialogComponent extends HTMLElement {
    constructor() {
      super();
      this.open = false;

      let template = document.getElementById("som-worklogdialog-template");
      let templateContent = template.content;

      this._shadowRoot = this.attachShadow({ mode: "open" });
      this._shadowRoot.appendChild(templateContent.cloneNode(true));

      this._dialog = this._shadowRoot.querySelectorAll("dialog")[0];
      this._box = this._shadowRoot.querySelectorAll("section")[0];
      this._info = this._shadowRoot.querySelectorAll("pre")[0];
      this._username = this._box.getElementsByClassName("username")[0];
      this._proyect = this._box.getElementsByClassName("proyect")[0];
      this._initdate = this._box.getElementsByClassName("initdate")[0];
      this._enddate = this._box.getElementsByClassName("enddate")[0];
    }
    connectedCallback(){
      // set border color for user
      let bcolor = "#0000";
      if(this.getAttribute("som-user")){
        let bcolor = getDeterministicColor(this.getAttribute("som-user"));
        this._username.textContent = this.getAttribute("som-user");
      }
      this._box.firstElementChild.style.borderColor = bcolor;

      if(this.getAttribute("som-proyect")){
        this._proyect.textContent = this.getAttribute("som-proyect");
      }

      if(this.getAttribute("som-initdate")){
        this._initdate.textContent = this.getAttribute("som-initdate");
      }

      if(this.getAttribute("som-enddate")){
        this._enddate.textContent = this.getAttribute("som-enddate");
      }

      if(this.getAttribute("som-background")){
        this._box.firstElementChild.style.backgroundColor = this.getAttribute("som-background");
      }

      if(this.getAttribute("som-info")){
        this._info.textContent = this.getAttribute("som-info");
      }
    }

    // set position of box
    moveTo(x, y) {
      this._box.style.top = y+"px";
      this._box.style.left = x+"px";
    }

    show(){
      this.open = true;
      this._dialog.show();
    }

    close(){
      this.open = false;
      this._dialog.close();
    }

  }

  customElements.define("som-worklogdialog", SOM_WorklogDialogComponent);
</script>
