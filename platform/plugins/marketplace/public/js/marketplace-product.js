/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************************************************!*\
  !*** ./platform/plugins/marketplace/resources/assets/js/marketplace-product.js ***!
  \*********************************************************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var MarketplaceProductManagement = /*#__PURE__*/function () {
  function MarketplaceProductManagement() {
    _classCallCheck(this, MarketplaceProductManagement);
  }

  _createClass(MarketplaceProductManagement, [{
    key: "init",
    value: function init() {
      $(document).on('click', '.approve-product-for-selling-button', function (event) {
        event.preventDefault();
        $('#confirm-approve-product-for-selling-button').data('action', $(event.currentTarget).prop('href'));
        $('#approve-product-for-selling-modal').modal('show');
      });
      $(document).on('click', '#confirm-approve-product-for-selling-button', function (event) {
        event.preventDefault();

        var _self = $(event.currentTarget);

        _self.addClass('button-loading');

        $.ajax({
          type: 'POST',
          cache: false,
          url: _self.data('action'),
          success: function success(res) {
            if (!res.error) {
              Botble.showSuccess(res.message);
              $('.approve-product-warning').fadeOut(500);
              window.location.reload();
            } else {
              Botble.showError(res.message);
            }

            _self.removeClass('button-loading');

            _self.closest('.modal').modal('hide');
          },
          error: function error(res) {
            Botble.handleError(res);

            _self.removeClass('button-loading');
          }
        });
      });
    }
  }]);

  return MarketplaceProductManagement;
}();

$(document).ready(function () {
  new MarketplaceProductManagement().init();
});
/******/ })()
;