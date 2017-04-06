Alema's mobile application
============================

Presentation of the project:
----------------------------

This project was conducted by a team of 7 developpers for the association Alema. <br/>
This application can be run on IOS and Android, it will be available on the stores soon. <br/>

### Alema

Alema is a french association which propose holidays camp for children and langages trips.<br/>
http://www.alema.asso.fr/

This application will be used for:<br/>
* Communicate informations to the parents during the trip, sending photos, etc.<br/>
* Giving informations about the future trips <br/>

So each trip's director can publish photos and commentary on the trip's page, and the parents can see and like this content.




Organisation of the repository:
--------------------------------

This repository contains two folder:<br/>
* Alema contains the code of the mobile application <br/>
* api_Alema contains the code of the server

### The Application

The appliaction has been developed using Ionic, which is a framework using AngularJS to make hybrid mobile applications.<br/>

The source code is in "www"<br/>
* www/css contains the css code
* www/images containes the images of the application
* www/js contains the "background of the application: the controllers, the links, ...
* www/lib contains some ionic librairies
* www/templates contains the pages of the front-end of the application. There are two folders:
  *general for the pages accessible by every users
  *membre for the pages accessible only by connected users


### The server

------------------------------------------------------------------------------------------

Application mobile Alema (Français)
=====================================

* Dossier Alema : application<br/>
* Dossier api_alema : debut de l’api en localhost (à changer app/config/parameters.yml afin de mettre ses paramètre de connexion)

lien vers comment faire une api : https://zestedesavoir.com/tutoriels/1280/creez-une-api-rest-avec-symfony-3/developpement-de-lapi-rest/
