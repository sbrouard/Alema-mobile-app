// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('app', ['ionic', 'app.controllers', 'app.routes', 'app.directives','app.services'])

.config(function($ionicConfigProvider, $sceDelegateProvider){
  

  $sceDelegateProvider.resourceUrlWhitelist([ 'self','*://www.youtube.com/**', '*://player.vimeo.com/video/**']);

})

.config(function ($sceDelegateProvider) {
    $sceDelegateProvider.resourceUrlWhitelist(['self', new RegExp('^(http[s]?):\/\/(w{3}.)?youtube\.com/.+$')]);
})

.config(['$ionicConfigProvider', function($ionicConfigProvider) {

    $ionicConfigProvider.tabs.position('bottom'); // other values: top

}])


.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);
      var push = PushNotification.init({
           android: {
             senderID: "861949500652"//, //project token number (12 digit) from https://console.developers.google.com
             // forceShow: "true", //force show push notification when app is in foreground on Android only.
           },
           ios: {
             /*senderID: "861949500652",*/ //If using GCM for ios, project token number (12 digit) from https://console.developers.google.com
             /*gcmSandbox: 'true',*/ //If using GCM for ios
             alert: 'true',
             badge: 'true',
             sound: 'true',
           },
           windows: {}
         });

         PushNotification.hasPermission(function (permissionResult) {
           if (permissionResult.isEnabled) {
             $log.debug("has permission for push notification");

             /*Register device with GCM/APNs*/
             push.on('registration', function (data) {
               // data.registrationId
               $log.debug("data.registrationId: " + data.registrationId);          
             });

             push.on('notification', function (data) {
               // data.message,
               // data.title,
               // data.count,
               // data.sound,
               // data.image,
               // data.additionalData
               $log.debug(JSON.stringify(data));
             });

             push.on('error', function (e) {
               // e.message
               $log.debug("e.message: " + e.message);
               //alert(e.message);
             });
           }
         });

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
       }
     );
  })

.directive('disableSideMenuDrag', ['$ionicSideMenuDelegate', '$rootScope', function($ionicSideMenuDelegate, $rootScope) {
    return {
        restrict: "A",  
        controller: ['$scope', '$element', '$attrs', function ($scope, $element, $attrs) {

            function stopDrag(){
              $ionicSideMenuDelegate.canDragContent(false);
            }

            function allowDrag(){
              $ionicSideMenuDelegate.canDragContent(true);
            }

            $rootScope.$on('$ionicSlides.slideChangeEnd', allowDrag);
            $element.on('touchstart', stopDrag);
            $element.on('touchend', allowDrag);
            $element.on('mousedown', stopDrag);
            $element.on('mouseup', allowDrag);

        }]
    };
}])