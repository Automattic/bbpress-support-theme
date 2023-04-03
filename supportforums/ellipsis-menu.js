document.addEventListener("DOMContentLoaded", () => {
  /**
   * Setups a click handler for opening and closing an ellipsis menu
   *
   * @param {HTMLElement} element
   */
  const setupEllipsisMenu = (element) => {
    // Handle clicking the ellipsis trigger element
    const onEllipsisClick = (event) => {
      element.classList.toggle("spf-more-actions--open");
    };
    element.addEventListener("click", onEllipsisClick);

    // Handle closing when clicking outside of the trigger element
    const onClickOutside = (event) => {
      const isInside = element.contains(event.target);
      if (!isInside) {
        element.classList.remove("spf-more-actions--open");
      }
    };
    window.addEventListener("click", onClickOutside);
  };

  // Setup handlers for dynamic page content and actions
  document
    .querySelectorAll("[data-ellipsis-menu]")
    .forEach(setupEllipsisMenu);
});