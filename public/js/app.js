(function () {
  var app = new Vue({
    el: '.app-content',
    data: {
      tvShows: [],
      tvShow: {
        id: '',
        title: '',
        description: '',
        imageUrl: ''
      }
    },
    ready: function() {
      // load TV shows here using vue-resource
    }
  });
})();
