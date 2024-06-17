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
    }

    article {
      width: 100%;
      height: 100%;
      border-width: medium;
      border-style: solid;
    }

  </style>

  <dialog>
    <section>
      <article>
        <span>&rarr;</span><span class="username"></span>
        <pre></pre>
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
    }
    connectedCallback(){
      // set border color for user
      let bcolor = "#0000";
      if(this.getAttribute("som-user")){
        let bcolor = getDeterministicColor(this.getAttribute("som-user"));
        this._username.textContent = this.getAttribute("som-user");
      }
      this._box.firstElementChild.style.borderColor = bcolor;

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
