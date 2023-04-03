(function ($, document) {
  "use strict";

  /**
   * Setups a click handler for copying the element's data-clipboard-text to the clipboard
   *
   * @param {HTMLElement} element
   */
  const setupCopyToClipboardAction = (element) => {
    const text = element.dataset.clipboardText;
    if (text) {
      element.addEventListener("click", (event) => {
        event.preventDefault();
        navigator.clipboard?.writeText(text);
      });
    }
  };

  /**
   * Helper function for bbPress API calls
   *
   * @param {string} action the name of the backend action
   * @param {number} object The ID of the object (usually a post or a similar model)
   * @param {string} type The type of the object (such as "post", etc.)
   * @param {string} nonce The nonce for the request
   * @param {Function} next Callback to handle the API response
   */
  function bbpAjax(action, object, type, nonce, next) {
    const $data = {
      action: action,
      id: object,
      type: type,
      nonce: nonce,
    };
    $.post(bbpEngagementJS.bbp_ajaxurl, $data, next);
  }

  /**
   * Setups a click handler for toggling a favorite state for a topic
   *
   * @param {HTMLElement} element
   */
  const setupFavoriteAction = (element) => {
    /**
     * Success handler for a favorite toggle api call
     * @param {boolean} isFavorite Whether the object is now favorited or not
     */
    const onFavoriteToggleSuccess = (isFavorite) => {
      if (isFavorite) {
        element.querySelector("span").textContent =
          element.dataset.unfavoriteLabel;
        element.querySelector("img").src = element.dataset.favoriteImage;
      } else {
        element.querySelector("span").textContent =
          element.dataset.favoriteLabel;
        element.querySelector("img").src = element.dataset.unfavoriteImage;
      }
    };

    /**
     * Handler for bbPress Ajax api responses
     * @param {Object} response
     */
    const onApiResponse = (response) => {
      if (response.success) {
        const isFavorite = response.content.includes("is-favorite");
        onFavoriteToggleSuccess(isFavorite);
      } else {
        throw new Error(
          `Favorite toggle failed: ${response.status} ${response.body}`
        );
      }
    };

    // Listen to clicks and trigger API calls for updating the favorite state
    element.addEventListener("click", (event) => {
      event.preventDefault();
      bbpAjax(
        "favorite",
        element.dataset.bbpObjectId,
        element.dataset.bbpObjectType,
        element.dataset.bbpObjectNonce,
        onApiResponse
      );
    });
  };

  document.addEventListener("DOMContentLoaded", () => {
    document
      .querySelectorAll("[data-clipboard-text]")
      .forEach(setupCopyToClipboardAction);
    document.querySelectorAll("[data-favorite]").forEach(setupFavoriteAction);
  });
})(window.jQuery, document);
