angular.module('app.routes', [])

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
    
  

      .state('alema', {
    url: '/Alema',
    templateUrl: 'templates/membre/alema.html',
    controller: 'alemaCtrl'
  })

  .state('menu.photos', {
    url: '/Photos',
    views: {
      'side-menu21': {
        templateUrl: 'templates/membre/photos.html',
        controller: 'photosCtrl'
      }
    }
  })

  .state('menu.actualites', {
    url: '/Actualites',
    views: {
      'side-menu21': {
        templateUrl: 'templates/membre/actualites.html',
        controller: 'actualitSCtrl'
      }
    }
  })

  .state('menu.commentaires', {
    url: '/Commentaires',
    views: {
      'side-menu21': {
        templateUrl: 'templates/membre/commentaires.html',
        controller: 'commentaireCtrl'
      }
    }
  })

  .state('menu.informations', {
    url: '/Information',
    views: {
      'side-menu21': {
        templateUrl: 'templates/membre/information.html'
      }
    }
  })

  .state('menu', {
    url: '/side-menu21',
    abstract: true,
    templateUrl: 'templates/membre/menu.html',
    controller: 'menuCtrl'
  })

  .state('compte', {
    url: '/pageCompte',
    templateUrl : 'templates/membre/compte.html',
    controller: 'compteCtrl'
  })
  
  .state('connexion', {
    url: '/Connexion',
    templateUrl: 'templates/membre/connexion.html',
    controller: 'connexionCtrl'
  })

  .state('mesSejours', {
    url: '/MesSejours',
    templateUrl: 'templates/membre/mesSejours.html',
    controller: 'mesSJoursCtrl'
  })

  .state('inscription', {
    url: '/Inscription',
    templateUrl: 'templates/membre/inscription.html',
    controller: 'inscriptionCtrl'
  })


  .state('menu.sejour', {
    url: '/Sejour',
    cache: false,
    views: {
      'side-menu21': {
        templateUrl: 'templates/membre/sejour.html',
        controller: 'sJourCtrl'
      }
    }
  })

  .state('menuGen', {
    url: '/side-menuGen',
    abstract: true,
    templateUrl: 'templates/general/menu_gen.html',
    controller: 'menuGenCtrl'
  })

  .state('menuGen.accueil_gen', {
    url: '/Accueil_gen',
    views: {
      'side-menuGen': {
        templateUrl: 'templates/general/accueil_gen.html',
        controller: 'accueilGenCtrl'
      }
    }
  })

  .state('menuGen.actualites_gen', {
    url: '/Actualies_gen',
    views: {
      'side-menuGen': {
        templateUrl : 'templates/general/actualites_gen.html'
      }
    }
  })

  .state('menuGen.contact_gen', {
    url: '/Contact_gen',
    views: {
      'side-menuGen': {
        templateUrl : 'templates/general/contact_gen.html'
      }
    }
  })

  .state('menuGen.video_gen', {
    url: '/Video_gen',
    views: {
      'side-menuGen': {
        templateUrl : 'templates/general/video_gen.html'
      }
    }
  })

$urlRouterProvider.otherwise('/Accueil_gen')

  

});