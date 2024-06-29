<template id="som-addworklog-template">
    <style>
        som-addworklog{
            background-color: #0005;
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        form {
            background-color: #FFFC;
            padding: 5px;
            border-radius: 5px;
        }
    </style>

    <form action="/api/addworklog" method="POST">
        @csrf
        <input type="hidden" name="worklog_id">
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

        <input type="button" value="Ingresar">
    </form> 
</template>

<script>
    let ADDWORKLOGCOMPONENT = null;
    class SOM_AddWorklogComponent extends HTMLElement {
        _fetchProyects() {
            fetch("/api/getproyectinputs")
            .then((response) => response.json())
            .then(({data}) => {
                data.forEach((proyect) => this._addProyect(proyect.id, proyect.name));
                this._selectProyect("{{ old('proyect_id') }}");
            }).catch((error) => {
                console.log("error", error);
            })
        }

        _cleanProyects(){
            while (this._input.proyect.firstChild) {
                this._input.proyect.removeChild(this._input.proyect.lastChild);
            }
            this._addProyect(0, "-");
        }
        _addProyect(value, name){
            let opt = document.createElement('option');
            opt.value = value;
            opt.innerHTML = name;
            this._input.proyect.appendChild(opt);
        }

        _save(){
            const data = {
                worklog_id: this._input.worklog.value,
                user_id: this._input.user.value,
                start: this._input.start.value,
                end: this._input.end.value,
                proyect_id: this._input.proyect.value,
                description: this._input.description.value
            };

            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            };

            fetch('/api/addworklog', options)
            .then(data => {
                location.reload();
            }).catch((error) => {
                console.log("error", error);
            });
        }

        constructor() {
            super();
            ADDWORKLOGCOMPONENT = this;

            this.open = false;

            let template = document.getElementById("som-addworklog-template");
            let templateContent = template.content;

            this._root = document.importNode(templateContent.cloneNode(true), true);

            this._input = {
                worklog: this._root.querySelectorAll("[name='worklog_id']")[0],
                user: this._root.querySelectorAll("[name='user_id']")[0],
                start: this._root.querySelectorAll("[name='start']")[0],
                end: this._root.querySelectorAll("[name='end']")[0],
                proyect: this._root.querySelectorAll("[name='proyect_id']")[0],
                description: this._root.querySelectorAll("[name='description']")[0]
            }

            this._root.querySelectorAll("input[type='button']")[0].addEventListener("click", (evt) => {
                this._save();
            }),

            this._cleanProyects();
            this._fetchProyects();
        }

        connectedCallback(){
            this.appendChild(this._root);

            this.addEventListener("click", (evt) => {
                if(evt.target === this){
                    this.close();
                }
            });
        }

        _selectProyect(proyect_id){
            for (let index = 0; index < this._input.proyect.children.length; index++) {
                if(this._input.proyect.children.item(index).value == proyect_id){
                    this._input.proyect.children.item(index).selected = true;
                    break;
                }
            }
        }

        setAttributes({start=null, end=null, proyect_id=null, description=null, user_id=null, worklog_id=null}){
            if(start){
                this._input.start.value = start;
            }
            if(end){
                this._input.end.value = end;
            }
            if(proyect_id){
                this._selectProyect(proyect_id);
            }
            if(description){
                this._input.description.value = description;
            }
            if(user_id){
                this._input.user.value = user_id;
            }

            this._input.worklog.value = worklog_id; // always like to override worklog id
        }

        show(){
            this.open = true;
            this.style.display = null;
        }

        close(){
            this.open = false;
            this.style.display = "none";
        }

    }

    customElements.define("som-addworklog", SOM_AddWorklogComponent);
</script>