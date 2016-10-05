(function () {
  var app = new Vue({
    el: '#create-form',
    data: {
      tvShow: {
        title: '',
        description: ''
      },
      errors: {}
    },
    methods: {
      create: function () {
        var _this = this;
        this.$http
          .post('/api/tv-shows', this.tvShow)
          .then(function () {
            window.location='/';
          }, function (error) {
            _this.errors = error.data;
          })
      }
    }
  });
})();
