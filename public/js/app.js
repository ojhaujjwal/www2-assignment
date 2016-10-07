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
      },
      reviews: [],
      reviewForm: false,
      review: {
        id: null,
        title: '',
        description: '',
        rating: ''
      },
      errors: []
    },
    mounted: function() {
      var _this = this;
      this.$nextTick(function () {
        _this.loadTvShows(function() {
          // load the first tv show
          if (_this.tvShows.length) {
            _this.loadTvShow(_this.tvShows[0].id);
          }
        });
      });
    },
    methods: {
      toggleNewReview: function () {
        this.review.id = null;
        this.reviewForm = true;
      },
      toggleEditReview: function (index) {
        this.review = this.reviews[index];
        this.review.index = index;
        this.reviewForm = true;
      },
      cancelNewReview: function () {
        this.reviewForm = false;
      },
      deleteReview: function (index) {
        var _this = this;
        return this.resource({
          method: 'delete',
          url: '/api/reviews/' + _this.reviews[index].id,
          onSuccess: function () {
            _this.reviews.splice(index, 1);
          },
          onError: function (error) {
            _this.errors = [error.data.message];
          }
        });
      },
      escapeHtml: function (html) {
        var txt = document.createElement('textarea');
        txt.innerHTML= html;
        return txt.value;
      },
      resource: function (options) {
        return this.$http[options.method || 'get'](options.url || '/', options.data || {})
          .then(function (response) {
            if (options.onSuccess && typeof options.onSuccess === 'function')
              return options.onSuccess(response.data);
          }, function (error) {
            if (options.onError && typeof options.onError === 'function')
              return options.onError(error);
          });
      },
      loadTvShows: function (cb) {
        var _this = this;
        this.resource({
          url: '/api/tv-shows',
          onSuccess: function (data) {
            _this.tvShows = data;
            if (typeof cb !== 'undefined') cb();
          }
        })
      },
      loadTvShow: function ($tvShowId) {
        var _this = this;
        this.resource({
          url: '/api/tv-shows/' + $tvShowId,
          onSuccess: function (data) {
            // first escape description and then replace \n with br tag
            data.description = _this.escapeHtml(data.description).replace(/\n/g, '<br />');
            _this.tvShow = data;
          }
        });
        this.loadReviews($tvShowId);
      },
      loadReviews: function (tvShowId) {
        var _this = this;
        this.resource({
          url: '/api/tv-shows/' + tvShowId + '/reviews',
          onSuccess: function (reviews) {
            _this.reviews = reviews.map(function(review) {
              review.review = _this.escapeHtml(review.review).replace(/\n/g, '<br />');
              return review;
            });
          }
        });
      },
      saveReview: function() {
        var _this = this;
        if (_this.review.id) {
          return this.resource({
            method: 'put',
            url: '/api/reviews/' + _this.review.id,
            data: _this.review,
            onSuccess: function (review) {
              var index = _this.review.index;
              _this.reviews[index] = review;
              _this.reviewForm = false;
            },
            onError: function (error) {
              _this.errors = error.data;
            }
          });
        }
        this.resource({
          method: 'post',
          url: '/api/tv-shows/' + _this.tvShow.id + '/reviews',
          data: _this.review,
          onSuccess: function (review) {
            _this.reviews.push(review);
            _this.reviewForm = false;
          },
          onError: function (error) {
            _this.errors = error.data;
          }
        });
      }
    }
  });
})();
