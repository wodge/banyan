/******/ (function() { // webpackBootstrap
/*!*******************************************************!*\
  !*** ./components/tiles-gallery/src/tiles-gallery.js ***!
  \*******************************************************/
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
(function ($, Drupal) {
  'use strict';

  /**
   * TilesGallery 'class'.
   *
   * @param {jQuery} $wrapper
   *   Main wrapper of tiles gallery..
   * @param {object} settings
   *   Object with settings to override default with.
   *
   * @constructor
   */
  function TilesGallery($wrapper, settings) {
    /**
     * Default settings.
     */
    this.defaultSettings = {
      itemSelector: '.tiles-gallery-item',
      sizerSelector: '.tiles-gallery__sizer',
      parentSelector: '.tiles-gallery-parent',
      captionSelector: '.tiles-gallery-item__caption',
      captionTitleSelector: '.tiles-gallery-item__caption-title',
      captionSubtitleSelector: '.tiles-gallery-item__caption-subtitle',
      videoSelector: '.video-embed',
      standardImageSelector: '.tiles-gallery-item--standard img'
    };

    /**
     * Apply custom settings.
     *
     * @type {object}
     */
    this.settings = $.extend(true, this.defaultSettings, settings || {});

    /**
     * Main wrapper.
     *
     * @type {jQuery}
     */
    this.$wrapper = $wrapper;

    /**
     * Parent item.
     *
     * @type {jQuery}
     */
    this.$parent = this.$wrapper.closest(this.settings.parentSelector);

    /**
     * Item with masonry initialized.
     *
     * @type {jQuery}
     */
    this.$masonry = null;
    this.initMasonry();
    this.prepareTilesCaptions();
  }

  /**
   * Init masonry.
   */
  TilesGallery.prototype.initMasonry = function () {
    this.$masonry = this.$wrapper.masonry({
      itemSelector: this.settings.itemSelector,
      columnWidth: this.settings.sizerSelector,
      percentPosition: true
    });
    this.bindMasonryEvents();
    this.$masonry.masonry('layout');
  };

  /**
   * Bind masonry events.
   */
  TilesGallery.prototype.bindMasonryEvents = function () {
    var self = this;
    this.$masonry.on('layoutComplete', function () {
      self.$parent.css('min-height', self.$wrapper.height());
      self.resizeVideos();
    });
  };

  /**
   * Resize videos.
   */
  TilesGallery.prototype.resizeVideos = function () {
    var $videos = this.$wrapper.find(this.settings.videoSelector);
    var imageHeight = this.$wrapper.find(this.settings.standardImageSelector).height();
    $videos.each(function () {
      $(this).css('height', imageHeight + 'px');
    });
  };

  /**
   * Prepare tiles captions.
   */
  TilesGallery.prototype.prepareTilesCaptions = function () {
    var self = this;
    this.$wrapper.find(this.settings.itemSelector).each(function () {
      var $image = $(this).find('img');
      if ($image.length == 0) {
        $(this).find(self.settings.captionSelector).remove();
        return;
      }
      var _$image$attr$split = $image.attr('alt').split('/', 2),
        _$image$attr$split2 = _slicedToArray(_$image$attr$split, 2),
        title = _$image$attr$split2[0],
        subtitle = _$image$attr$split2[1];
      if (title !== undefined) {
        $(this).find(self.settings.captionTitleSelector).text(title);
      }
      if (subtitle !== undefined) {
        $(this).find(self.settings.captionSubtitleSelector).text(subtitle);
      }
    });
  };

  /**
   * A jQuery interface.
   *
   * @param {object} settings
   *   Object with settings to override defaults with.
   *
   * @returns {jQuery}
   */
  TilesGallery.jQueryInterface = function (settings) {
    return this.each(function () {
      new TilesGallery($(this), settings);
    });
  };
  $.fn.tilesGallery = TilesGallery.jQueryInterface;

  /**
   * Main behavior for tiles gallery.
   */
  Drupal.behaviors.tilesGallery = {
    attach: function attach(context) {
      $(once('tiles-gallery', '.tiles-gallery', context)).tilesGallery();
    }
  };
})(jQuery, Drupal);
/******/ })()
;
//# sourceMappingURL=tiles-gallery.js.map