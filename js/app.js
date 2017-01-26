//app
var app = angular.module('imgApp',[]);
//controller
app.controller('uploadCtrl',function($scope,$http){
    //init
    $scope.imageFile = "";
    //preview
    $scope.$watch("imageFile", function (imageFile){
        $scope.imageFileSrc = undefined;
        if(!imageFile || !imageFile.type.match("image.*")){
            return;
        }
        var reader = new FileReader();
        reader.onload = function(){
            $scope.$apply(function(){
                $scope.imageFileSrc = reader.result;
            });
        };
        reader.readAsDataURL(imageFile);
    });
    //onclick
    $scope.btnClick = function(){
        //formdata
        var fd = new FormData();
        fd.append('imageFile',$scope.imageFile);
        //post
        $http.post('upload.php',fd,{
            transformRequest: null,
            headers: {'Content-type':undefined}
        })
        .success(function(res){
            console.log(res);
            $scope.response = res;
        });
    }
});
//directive
app.directive('fileModel',function($parse){
    return{
        restrict: 'A',
        link: function(scope,element,attrs){
            var model = $parse(attrs.fileModel);
            element.bind('change',function(){
                scope.$apply(function(){
                    model.assign(scope,element[0].files[0]);
                });
            });
        }
    };
});