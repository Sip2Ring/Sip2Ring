app.directive('autocomplete', function() {
    return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<input name="autocomplete" class="form-control input-md" type="text"/>',
        link: function(scope, element, attrs) {
            scope.$watch(attrs.list, function(value) {
                element.autocomplete({
                    source: value,
                    select: function(event, ui) {
                        scope[attrs.selection] = ui.item.value;
                        scope.$apply();
                    }
                });
            });
        }
    };
});