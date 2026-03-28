/******/ (function() { // webpackBootstrap
/*!***************************************!*\
  !*** ./components/select2/select2.js ***!
  \***************************************/
!function (e, s) {
  s.behaviors.select2 = {
    attach: function attach(s, t) {
      e("[data-select2] .form-select", s).each(function () {
        e(this).find(".form-select").hasClass("select2-hidden-accessible") || e(this).select2({
          minimumResultsForSearch: -1,
          width: "100%"
        });
      });
    }
  };
}(jQuery, Drupal);
/******/ })()
;
//# sourceMappingURL=select2.js.map