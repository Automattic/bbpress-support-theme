/**
 * Support topics suggestions when creating new topics.
 */

(() => {
  const { __ } = wp.i18n;
  const suggestionsTitle = __( 'Do you want the answer to any of these questions?' );
  const suggestionsSubtitle = __( 'The following articles might help you:' );

  /**
   * Fetch suggestions from the backend API
   *
   * @param {string} query The search query string
   * @returns A list of suggestions
   */
  async function fetchSuggestionsFromApi(query) {
    return new Promise((resolve, reject) => {
      WPCOM_Proxy_Request( {
        apiNamespace: 'rest/v1.1',
        path: '/help/qanda',
        query: {
          http_envelope: "1",
          site: 'en.support.wordpress.com',
          query,
        }
      } ).done(resolve).fail(reject);
    })
  }

  /**
   * Render the suggestions list
   *
   * @param {object[]} suggestions a list of suggestions
   * @param {HTMLElement} targetElement the target element whose content will be replaced
   * with the HTML content for the suggestions list
   */
  function renderSuggestions(suggestions, targetElement) {
    if (!targetElement) {
      return;
    }

    if (!suggestions?.length) {
      targetElement.innerHTML = '';
      return;
    }

    targetElement.innerHTML = `
    <section class="suggestions" style="opacity: 0">
      <div>
        <h1>${_.escape(suggestionsTitle)}</h1>
        <h2>${_.escape(suggestionsSubtitle)}</h2>
      </div>
      <ul>
          ${ suggestions.map(suggestion => `<a target="_blank" href="${_.escape(suggestion.link)}"><li>${_.escape(suggestion.title)}</li></a>`).join('')}
      </ul>
    </section>`;

    setTimeout(() => {
      targetElement.querySelector(".suggestions").style.opacity = 1;
    }, 0);
  }


  /**
   * Handle the search input events. For each keypress, fetch suggestions from the API.
   *
   * @param {Event} event Input event
   */
  async function handleInputChange(event) {
    const query = event.target.value.trim();
    const suggestions = await fetchSuggestionsFromApi(query);
    const targetElement = document.querySelector(event.target.dataset.suggestions);
    renderSuggestions(suggestions, targetElement);
  }

  /**
   * A debounced version of the handleInputChange function
   */
  const debouncedHandleInputChange = _.debounce(handleInputChange, 500);

  /**
   * Setup the listeners for the search input
   *
   * @param {HTMLInputElement} input The input element to bind event listeners to
   */
  function setupSuggestions( input ) {
    input.addEventListener( 'input', debouncedHandleInputChange);
  }

  /**
   * Initialize the suggestions feature
   */
  document.addEventListener( 'DOMContentLoaded', () => {
    const suggestionsInputs = document.querySelectorAll( '[data-suggestions]' );
    suggestionsInputs.forEach(setupSuggestions);
  })

})();
