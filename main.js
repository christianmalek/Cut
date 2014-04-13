var app = angular.module('app', []);

app.factory('Cutter', function($http){
	var factory = {};
	factory.cut = function(url){
		return $http.post('../api/' + url);
	}
	factory.expand = function(key){
		return $http.get('../api/' + key);
	}
	return factory;
});

function urlCutController($scope, Cutter){
	$scope.domain = 'http://www.yourDomain.com';
	$scope.cutter = Cutter;
	$scope.cuttedUrl = '';
	$scope.longUrl = '';
	$scope.invalidURL = false;
	
	$scope.cut = function(){
		$scope.cutter.cut($scope.urlToCut).success(function(data){
			$scope.cuttedUrl = data;
			$scope.longUrl = $scope.urlToCut;
			$scope.error = false;
		}).error(function(){
			$scope.error = true;
		});
	}
}