# Model

The following diagram shows the tables present in the database

![Database model](/database/database.png "Diagram")

# Users Table
  - `id` *(increments)*
  - `email` *(string)*
  - `password` *(string)*

# Proyects Table
  - `id` *(increments)*
  - `name` *(string)*
  - `color` *(string)* representative color of proyect
  - `preview` *(string)* representative image url from proyect

# Worklogs Table
  - `id` *(increments)*
  - `fk_user` *(foreign)* assigned user of done tasks
  - `fk_proyect` *(foreign)* assigned proyects of tasks
  - `description` *(string)* 
  - `start` *(datetime)* init time of tasks
  - `end` *(datetime)* end time of tasks

# tasks Table
  - `id` *(increments)*

# taskLists Table: always a proyect has at last one task list to save individual tasks
  - `id` *(increments)*
  - `name` *(string)*
  - `color` *(string)* representative color of proyect
