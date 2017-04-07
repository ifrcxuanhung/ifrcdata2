define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var projectsModel = Backbone.Model.extend({
    defaults: {
      score: 50
    },
    initialize: function(){
    }

  });
  return projectsModel;

});
