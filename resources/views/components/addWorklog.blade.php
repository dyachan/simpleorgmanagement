<template id="som-addworklog-template">
    <form action="/addworklog" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

        <label for="start">inicio:</label>
        <input type="datetime-local" name="start" value="{{ old('start') }}"><br>
        <label for="end">fin:</label>
        <input type="datetime-local" name="end" value="{{ old('end') }}"><br>

        <label for="proyect">proyecto:</label>
        <select name="proyect_id">
        </select><br>

        <label for="description">descripción:</label>
        <textarea name="description" rows="4" cols="30">{{ old('description') }}</textarea>

        <input type="submit" value="Ingresar">
    </form> 
</template>

<script>
    class SOM_AddWorklogComponent extends HTMLElement {
        _fetchProyects() {
            fetch("/api/getproyectinputs")
            .then((response) => response.json())
            .then(({data}) => {
                data.forEach((proyect) => this._addProyect(proyect.id, proyect.name));
            }).catch((error) => {
                console.log("error", error);
            })
        }

        _cleanProyects(){
            while (this._proyectSelect.firstChild) {
                this._proyectSelect.removeChild(this._proyectSelect.lastChild);
            }
            this._addProyect(0, "-");
        }
        _addProyect(value, name){
            let opt = document.createElement('option');
            opt.value = value;
            opt.innerHTML = name;
            this._proyectSelect.appendChild(opt);
        }

        constructor() {
            super();

            let template = document.getElementById("som-addworklog-template");
            let templateContent = template.content;

            this._shadowRoot = this.attachShadow({ mode: "open" });
            this._shadowRoot.appendChild(templateContent.cloneNode(true));

            this._proyectSelect = this._shadowRoot.querySelectorAll("select")[0];
            this._cleanProyects();

            this._fetchProyects();
        }
    }

    customElements.define("som-addworklog", SOM_AddWorklogComponent);
</script>