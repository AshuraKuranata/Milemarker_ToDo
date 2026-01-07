# To-Do List App

## Description
Build a simple Todo Application using Vue.js and Laravel that showcases your full-stack development skills.

Tech Stack Requirements:

Frontend: Vue.js
Backend: Laravel (PHP)
Database: Your choice (MySQL, PostgreSQL, or SQLite)

## CORE REQUIREMENTS

### Frontend Features:

* Create, read, update, and delete (CRUD) todo items
* Mark todos as complete/incomplete
* Clean, responsive UI design
* Basic form validation

### Backend Features:

* RESTful API endpoints for all CRUD operations
* Database migrations and models
* Basic input validation and error handling
* Proper HTTP status codes

### Bonus Points (Optional):

* User authentication
* Todo categories or tags
* Due dates with sorting
* Search/filter functionality
* Automated tests

## Development

Laravel and Vue.js are different technologies than I'm normally familiar with, so I began with learning the fundamentals through the initial creation of a "Chirper" app to understand the basics of setting up an app through Laravel.

In that, I found that they did not actually utilize Vue.js to build their application, but the pre-setup to create a new Laravel project has a placeholder.  I will utilize this initial set up framework to get the fundamentals set.

To start off, I began with identifying all the key areas of the project that I'll be working on:

Routes: To build the routing for all CRUD operations
Models: To build the database models for the todo items
Controllers: To build the controllers for the todo items
Pages: Creating the pages for population rather than views to keep as a SPA
Components: Building various components for the todo items, including Navbar, TodoItem, TodoForm, etc.

With this out of the way, I'll begin with some basic building blocks of the site page to ensure that it is working and viewable:

* Created Home.vue, updated web.php to route to Home.vue
* Created components folder in pages to handle all components related to the SPA
* Created Navbar.vue for navigation bar to add to Home.vue and other component pages

### Model & Controller Build
Once I built these out, I started my work on the Models.  Being a to-do list with users, I decided to utilize 3 models:
* Pre-built user model for eventual user authentication and authorization, also save view
* TodoList model to handle connecting to a user and housing the to-do tasks
* Todo model to house all to-dos within

For these models, I had to ensure that the relationships between User, TodoList, and Todo was successfully set up, where multiple lists could belong to a user, a user could have multiple lists, and a list could have multiple todos and vice versa.

Once the models and migrations were set up, I then began work on the controllers to handle the CRUD operations for the todo items.  To start off, I focused on building out the routes and controllers in the routes/web.php.  This took some work to translate my understanding working in Django with PostgreSQL into understanding how Laravel handles the routing and controllers, but the learning curve was in understanding how to call the same route and controller actions in Laravel like Django.

Once the controllers and routes were updated and in place from what I could tell, I began work on the pages and components to handle the display and functionality of the todo items.  To start off, I focused on building out the Home.vue page to handle the display of the todo lists and the creation of new todo lists with a dummy user.  Doing this allowed me to focus on ensuring that the CRUD operations were working correctly and that the data was being displayed correctly before I began to focus on the user authentication and authorization.

### CRUD for Todo Lists & Tasks
I knew that I wanted the todo-lists and to-dos to display on the user's page when they were logged in, so I focused on building the list to display in a card format with the tasks listing under them.  This was a bigger task for me to understand given my familiarity on using React.js over Vue.js.  In researching this space, I realized that most of the management of the scripting had to occur in the <script setup lang="ts"></script> that pretty much handled all the functional routing that I wanted the page to handle when the template was added to the app.ts into the app.blade.php through Inertia, much like in React.js where you add the code prior to your "return" statement of what would actually display for the html.

Once figured out, I was able to set up pulling the data from the routes and controllers to the front-end and successfully have the data show up on Home.vue page with proper CRUD functionality.

### User Authentication & Authorization - linking and protecting CRUD routes through middleware
From there, I wanted to get the user functionality into place.  I had completed the Laravel tutorial courses to better understand how Laravel handles authentication and authorization through its base model, so I followed the authentication and authorization code structure to implement for this project.

While reading up on authentication and authorization, I learned that you could protect the routes through middleware using the auth route so it would require and active user profile loaded in before allowing access to any of the controllers or routes.

In doing so, this allowed the code to now be set up in such a way that I could reconfigure the components of the ToDo list and items based on the protections set up wiht the middleware routes so only registered users in the platform can access the SQLite database.

### Non-User Hero Component
Once I took a look at the page, I realized that I still wanted to make the non-user logged in page have something in it over that of the user logged in page.  I did some research to look at some other examples of heroes and found one that I liked and wanted to generate a Hero display to encourage action to log in or register into the app with some cards that show what this app provides.  This was generated as its own component and added to the Home.vue page to display for guests.

### Changing layout of Todo Lists and Todos
Once I took a second look at the app with the proper functionality out there, I realized that the layout of the to-do lists and items could be improved.  I wanted to have the titles of the to-do list expand and collapse to have a cleaner view that would display the to-do lists for a user and how many tasks were in them, but not have it immediately expanded.  I changed the display to use an accordion format so the component of tasks would display on clicking the title.

Once that was done, I looked at the tasks and realized I forgot to add in the buttons for editing the to-do items and the to-do lists.  I reviewed my routes and controllers and connected the edit and delete buttons to the proper routes and controllers based on the data pulled for each element to use my 'put' routes.

From there, I considered the possibility of creating sections of "complete" and "incomplete" tasks into different card sections of the to-do list, but realized that would probably be overengineering the list look and that I was thinking of adding in sorting and filtering anyway, which would ultimately handle a user's engagement with a to-do list.

### Sorting & Filtering Functionality
I knew that the data and elements were already engaged in the app, so now it was a matter creating some display functionality to sort and filter the data.  I decided to create dropdown lists to handle the kinds of sorting and filtering that I wanted a user to be able to do (sort by: due dates, priority, created date) and (filter by: completed, incomplete, all tasks).

Implementation of this took place by using sorting and filtering through switch case checks of task data fields to determine whether to keep or remove tasks from the list that will eventually display.  This was managed in its own sortedList object that takes the associated filter or sort options based on the selections and apply accordingly to update the display.

While implementing, I ran into an issue with filtering with incomplete, complete for the tasks.  What I fould was that the boolean value was not being properly converted as I was using fuzzy matching for the boolean true and false, so I looked into options and learned that there's a way to "cast" the boolean value in the model to retain it as a boolean when being brought to the front end.  This successfully fixed the error on the filter functionality. 

## Final Thoughts
With this completed, I have successfully built the to-do app with the following functionality:

* User Authentication and Authorization
* CRUD for User, TodoList, and Todo models
* Linked model relationships
* Sorting and Filtering Functionality

Some improvements that I can see to build into the project:
* Better stylistic design for user experience and engagement
* Categories & tags for to-do lists
* Notes on to-do items
* Due date notifications
* User's home page display of upcoming/due/overdue tasks
* User's profile page to edit account information
* Automated tests for development

It was a challenge to focus on learing two new languages via Laravel and Vue.js, but I'm grateful for the opportunity to focus on building a full-stack application using new technologies that I've had little experience utilizing before.  I hope to continue building and developing and working on this project is giving me inspiration to use these prompts and ideas to consider building other versions and variations to expand and improve on this idea.

## Change Log

### 1.6.2026
Request to change the deployment to be https secure utilizing Vercel (https://vercel.com/) used by the team.

Researched into deployment and found that it would be a relatively simple process to deploy to Vercel given the Laravel framework and the Vercel support for Laravel.

This required implementing a vercel.json file with commands to run in Vercel, so researching and finding the codes required to handle deployment into Vercel.

When setting up project, specific area for environment variables is required in site deployment for git project connected.  Quick copy paste fixed.

#### Bug
Ran into issues with the database management as SQLite is not well-suited for Vercel.  With an agnostic model schema already developed, determined to shift into PostgreSQL server managed by my AWS EC2 instance.  Set up database in EC2 server with security group commands in place for routing requests.

Testing for updates to Vercel in uploading to deploy properly with new configurations.