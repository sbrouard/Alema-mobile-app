angular.module('app.controllers', ['ionic', 'ui.router'])
 
.run(function($rootScope){
	$rootScope.sejour = '';
	$rootScope.login = '';
}) 

.controller('tabCtrl', ['$scope', '$stateParams', '$state', '$rootScope', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $state, $rootScope) {
	$scope.goGen = function(){
		console.log("Général");
		$state.go('menuGen.accueil_gen');
	};

	$scope.goMembre = function(){
		console.log("Membre");
		console.log($rootScope.login);
		if($rootScope.login == '' || $rootScope.login === undefined)
			$state.go('alema');
		else
			$state.go('mesSejours');
	};
}])

.controller('alemaCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])
   
.controller('photosCtrl', ['$scope', '$rootScope', '$ionicModal', '$ionicSlideBoxDelegate',// The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $rootScope, $ionicModal, $ionicSlideBoxDelegate) {
	$scope.photos = [
		{id: 1, src: 'img/photo1.jpeg', nbJaime : 32, delete: 'no'},
		{id: 2, src: 'img/photo2.jpeg', nbJaime : 14, delete: 'no'},
		{id: 3, src: 'img/photo3.jpeg', nbJaime : 13, delete: 'no'},
		{id: 4, src: 'img/photo4.jpeg', nbJaime : 11, delete: 'no'},
		{id: 5, src: 'img/photo1.jpeg', nbJaime : 10, delete: 'no'},
		{id: 6, src: 'img/photo2.jpeg', nbJaime : 6, delete: 'no'},
		{id: 7, src: 'img/photo3.jpeg', nbJaime : 34, delete: 'no'},
		{id: 8, src: 'img/photo4.jpeg', nbJaime : 1, delete: 'no'},
		{id: 9, src: 'img/photo1.jpeg', nbJaime : 2, delete: 'no'},
		{id: 10, src: 'img/photo2.jpeg', nbJaime : 8, delete: 'no'},
		{id: 11, src: 'img/photo3.jpeg', nbJaime : 6, delete: 'no'},
		{id: 12, src: 'img/photo4.jpeg', nbJaime : 3, delete: 'no'},
		{id: 13, src: 'img/photo1.jpeg', nbJaime : 13, delete: 'no'},
		{id: 14, src: 'img/photo2.jpeg', nbJaime : 8, delete: 'no'},
		{id: 15, src: 'img/photo3.jpeg', nbJaime : 10, delete: 'no'},
		{id: 16, src: 'img/photo3.jpeg', nbJaime : 10, delete: 'no'},
		{id: 17, src: 'img/photo3.jpeg', nbJaime : 10, delete: 'no'}
	];

	 $scope.showImages = function(index) {
	  $scope.activeSlide = index;
      $scope.showModal('templates/membre/afficheImage.html', 'img');
     };

     $scope.showModal = function(templateUrl, rub) {
		$ionicModal.fromTemplateUrl(templateUrl, {
			scope: $scope,
			animation: 'slide-in-up'
		}).then(function(modal) {
			if(rub == 'img'){
				$scope.modalImg = modal;
				$scope.modalImg.show();
			}
			else{
				$scope.modalCom = modal;
				$scope.modalCom.show();
			}
		});
	}

	$scope.closeModal = function(rub) {
		if(rub == 'img'){
			$scope.modalImg.hide();
			$scope.modalImg.remove();
		}
		else{
			$scope.modalCom.hide();
			$scope.modalCom.remove();
		}
	};

	$scope.showCommentaires = function(index) {
      $scope.showModal('templates/membre/affichageCommentaire.html', 'com');
     };
	
	$scope.deleteImg = function(){
		for (var i = 0; i < $scope.photos.length; i++) {
			if($scope.photos[i].delete == 'yes'){
				console.log($scope.photos[i].id);
			}
		}
	}

	$scope.deleteCom = function(){
		for (var i = 0; i < $scope.commentary.length; i++) {
			if($scope.commentary[i].delete == 'yes'){
				console.log($scope.commentary[i].id);
			}
		}
	}
}])

.controller('commentaireCtrl', ['$scope', '$stateParams','$http', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $http) {
	$scope.commentary = [
		{id: 1, pseudo: 'Toto', date: '28/11/2016', text: 'Coucou les gens', delete: 'no'},
		{id: 2, pseudo: 'Toto', date: '29/11/2016', text: 'Vous allez bien?', delete: 'no'},
		{id: 3, pseudo: 'Mamie toto', date: '29/11/2016', text: 'Oui ça va', delete: 'no'},
		{id: 4, pseudo: 'Toto', date: '28/11/2016', text: 'Coucou les gens', delete: 'no'},
		{id: 5, pseudo: 'Toto', date: '29/11/2016', text: 'Vous allez bien?', delete: 'no'},
		{id: 6, pseudo: 'Mamie toto', date: '29/11/2016', text: 'Oui ça va', delete: 'no'},
		{id: 7, pseudo: 'Toto', date: '28/11/2016', text: 'Coucou les gens', delete: 'no'},
		{id: 8, pseudo: 'Toto', date: '29/11/2016', text: 'Vous allez bien?', delete: 'no'},
		{id: 9, pseudo: 'Mamie toto', date: '29/11/2016', text: 'Oui ça va', delete: 'no'},
		{id: 10, pseudo: 'Toto', date: '28/11/2016', text: 'Coucou les gens', delete: 'no'},
		{id: 11, pseudo: 'Toto', date: '29/11/2016', text: 'Vous allez bien?', delete: 'no'},
		{id: 12, pseudo: 'Mamie toto', date: '29/11/2016', text: 'Oui ça va', delete: 'no'},
		{id: 13, pseudo: 'Toto', date: '30/11/2016', text: 'Trop bien le séjour', delete: 'no'}
	];
	$scope.deleteCom = function(){
		for (var i = 0; i < $scope.commentary.length; i++) {
			if($scope.commentary[i].delete == 'yes'){
				// console.log($scope.commentary[i].id);
				var url = 'https://alema.yoannbourgery.com/comments/' + $scope.commentary[i].id ; 
				$http.delete(url,$scope.commentary[i].id);
			}
		}
	};

	$scope.postCom = function(){
		var url = 'https://alema.yoannbourgery.com/trips/' + $scope.$id +'/comments'; 
		var text = document.getElementById("textCommentaire").value;
		$http.post(url,text);
	};

	$scope.GetComSej = function(){
		var tab = [];
		var url = 'https://alema.yoannbourgery.com/trips/' + $scope.$id +'/comments'; 
		for (var i = 0; i < $scope.commentary.length; i++) {
			var c = $scope.commentary[i];
			tab[i].push(c.id,c.pseudo,$scope.$id,$scope.sejour,c.text,c.date);
		}
		$http.get(url,tab);
	};


}])

.controller('actualitSCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])
   
.controller('menuCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])
   
.controller('connexionCtrl', ['$scope', '$stateParams', '$state', '$rootScope',// The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $state, $rootScope) {
	$scope.data = {};
	$scope.login = function(){
		$rootScope.login = $scope.data.user;
		console.log($rootScope.login);
		$state.go('mesSejours');
	};
}])
   
.controller('mesSJoursCtrl', ['$scope', '$stateParams', '$rootScope', '$state',// The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $rootScope, $state) {
	$scope.change = function(sej){
		$rootScope.sejour = sej;
	};

}])
   
.controller('inscriptionCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])
   
.controller('sJourCtrl', ['$scope', '$stateParams', '$rootScope',  // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $rootScope) {
	console.log('ok');
	$scope.sejour = $rootScope.sejour;
	console.log($scope);
}])

.controller('compteCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])

.controller('accueilGenCtrl', ['$scope', '$stateParams', '$http',// The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams, $http) {
		// $scope.user = {login: 'test@pass.fr', password: 'test'};
		// $http({
	 //    method: 'POST',
	 //    url: 'http://rest-api.local/auth-tokens',
	 //    data: $scope.user,
	 //    headers: {
	 //     	'X-Auth-Token': '28BV+HQEd47L9zyxHusu7Cv+TkZGNWtAAJt3mV6sDGznhyS8krSmp5b3cl6sKMmdXQw='
	 //     }
	 //  })
	 //  .success(function (data, status, headers, config) {
	 //    console.log(data);
	 //  })
	  // .error(function (data, status, headers, config) {
	  // 	console.log(config);
	  //   console.log(data);
	  // });
}])   

.controller('menuGenCtrl', ['$scope', '$stateParams', // The following is the constructor function for this page's controller. See https://docs.angularjs.org/guide/controller
// You can include any angular dependencies as parameters for this function
// TIP: Access Route Parameters for your page via $stateParams.parameterName
function ($scope, $stateParams) {


}])