/*
 * For Jetpack Search UI customizations, e.g. tabs, UI re-ordering, etc.
 */

document.addEventListener('DOMContentLoaded', () => {
  const { __ } = wp.i18n;

  const supportIds = {
    en: 9619154,
    el: 9619154,
    es: 110643074,
    'pt-br': 69197545,
    de: 20614491,
    fr: 9620355,
    he: 12084301,
    ja: 26068228,
    it: 150300509,
    nl: 150381433,
    ru: 22718864,
    tr: 150881074,
    ar: 151398564,
    sv: 151398260,
  };
  const supportId = getId(supportIds, supportIds['en']);

  const forumsIds = {
    en: isTest() ? 139681555 : 142208464,
    el: 142050744,
    es: 142203819,
    'pt-br': 142211264,
    de: 142208975,
    fr: 142208882,
    he: 142051025,
    ja: 142382769,
    it: 142210970,
    nl: 142211597,
    ru: 142211736,
    tr: 142211501,
    ar: 139271360,
    sv: 142050859,
  };
  const forumsId = getId(forumsIds, forumsIds['en']);

  const tabAll = document.createElement('button');
  tabAll.className = 'supportforums-search-tab';
  tabAll.textContent = __('All results');
  const tabSupport = document.createElement('button');
  tabSupport.className = 'supportforums-search-tab';
  tabSupport.textContent = __('Guides');
  const tabForums = document.createElement('button');
  tabForums.className = 'supportforums-search-tab';
  tabForums.textContent = __('Forum topics');
  const tabs = document.createElement('div');
  tabs.className = 'supportforums-search-tabs';
  tabs.append(tabAll);
  tabs.append(tabSupport);
  tabs.append(tabForums);

  const noResultsTitle = document.createElement('h2');
  noResultsTitle.textContent = __('No results found');

  let searchResultsView;
  let searchResultsTitle;
  let supportInput;
  let forumsInput;
  let prevHiddenFilter;

  function isTest() {
    const hostname = new URL(location.href).hostname;
    return hostname.startsWith('test');
  }

  function getId(ids, defaultId) {
    // Getting locale from the first pathname of the URL, e.g. https://wordpress.com/[fr]/forums
    const locale = new URL(location.href).pathname
      .split('/')
      .filter(Boolean)[0];

    return ids[locale] || defaultId;
  }

  function getBlogFilter() {
    return [
      ...document.querySelectorAll(
        '.jetpack-instant-search__search-filters > div'
      ),
    ].find((filter) => filter.id.endsWith('blogId'));
  }

  function updateInputs(target) {
    // A blog filter option will be rendered only there're related search results from the site
    // so we need to declare the options as undefined before updating for the code logic purposes
    supportInput = undefined;
    forumsInput = undefined;

    target
      .querySelectorAll(
        '.jetpack-instant-search__search-filter-list:last-child input'
      )
      .forEach((input) => {
        if (input.disabled) return;

        if (input.name == forumsId) forumsInput = input;
        if (input.name == supportId) supportInput = input;
      });
  }

  function hideClearFiiterBtnForBlogFilter() {
    const clearFilterBtn = document.querySelector(
      '.jetpack-instant-search__clear-filters-link'
    );

    if (!clearFilterBtn) return;

    const searchParams = new URLSearchParams(location.search);

    if (searchParams.has('topic-tag') || searchParams.has('month_post_date')) {
      clearFilterBtn.classList.add('is-shown');
    } else {
      clearFilterBtn.classList.remove('is-shown');
    }
  }

  function reorderSubtitles() {
    document
      .querySelectorAll(
        '.jetpack-instant-search__search-result-expanded__footer'
      )
      .forEach((subtitle) => {
        subtitle.parentNode
          .querySelector('.jetpack-instant-search__search-result-title')
          .after(subtitle);
      });
  }

  function setCurrentTab(tab) {
    tabAll.classList.remove('is-active');
    tabSupport.classList.remove('is-active');
    tabForums.classList.remove('is-active');

    if (tab === 'all') tabAll.classList.add('is-active');
    if (tab === 'support') tabSupport.classList.add('is-active');
    if (tab === 'forums') tabForums.classList.add('is-active');
  }

  function showNoResultsView() {
    searchResultsView.classList.add('no-results');
    searchResultsTitle.classList.add('is-hidden');
    noResultsTitle.classList.remove('is-hidden');
  }

  function uncheckSupport() {
    if (supportInput?.checked) supportInput.click();
  }

  function checkSupport() {
    if (supportInput?.checked === false) supportInput.click();
  }

  function uncheckForums() {
    if (forumsInput?.checked) forumsInput.click();
  }

  function checkForums() {
    if (forumsInput?.checked == false) forumsInput.click();
  }

  tabAll.addEventListener('click', () => {
    setCurrentTab('all');
    uncheckSupport();
    uncheckForums();

    // To cover the edge case:
    // 1. Only have search results from one site
    // 2. Switching tab to the non search results tab
    // 3. Switching back to the "All" tab
    // 4. No search results view isn't cleared
    searchResultsTitle.classList.remove('is-hidden');
    noResultsTitle.classList.add('is-hidden');
    searchResultsView.classList.remove('no-results');
  });

  tabSupport.addEventListener('click', () => {
    setCurrentTab('support');

    // If the current applied blog filter is forums, clearing it and then setting filter to support once it exists (via `searchResultsTitleObserver`)
    if (forumsInput?.checked) {
      uncheckForums();

      // If there's the blog filter > support option, which means 1) no any applied blog filter 2) have results from the site, so just check it
    } else if (supportInput) {
      checkSupport();

      // If there's no the blog filter > support option, show no results view to the user
    } else {
      showNoResultsView();
    }
  });

  tabForums.addEventListener('click', () => {
    setCurrentTab('forums');

    if (supportInput?.checked) {
      uncheckSupport();
    } else if (forumsInput) {
      checkForums();
    } else {
      showNoResultsView();
    }
  });

  // To watch the state of the API changes
  const searchResultsTitleObserver = new MutationObserver(([mutation]) => {
    hideClearFiiterBtnForBlogFilter();
    reorderSubtitles();

    const { nodeValue: wording } = mutation.target;

    // If API is loading, show original search results title instead of our no results title
    if (wording.endsWith('…') || wording.endsWith('...')) {
      searchResultsTitle.classList.remove('is-hidden');
      noResultsTitle.classList.add('is-hidden');

      return;
    }

    searchResultsView.classList.remove('no-results');

    const blogFilter = getBlogFilter();

    updateInputs(blogFilter);

    // If the active tab is support and the related filter option exists, which means there're results from the site, so check it
    // Otherwise, show no results view to the user
    if (tabSupport.classList.contains('is-active')) {
      if (supportInput) {
        checkSupport();
      } else {
        showNoResultsView();
      }
    }

    if (tabForums.classList.contains('is-active')) {
      if (forumsInput) {
        checkForums();
      } else {
        showNoResultsView();
      }
    }

    prevHiddenFilter.classList.remove('supportforums-search-is-hidden');
    blogFilter.classList.add('supportforums-search-is-hidden');
    prevHiddenFilter = blogFilter;
  });

  // To wait for the search modal to be rendered (after 1st API fetched)
  new MutationObserver((mutationList, observer) => {
    for (const mutation of mutationList) {
      if (
        mutation.target.classList?.contains(
          'jetpack-instant-search__search-results-primary'
        )
      ) {
        // Blog filter needs to be enabled via /wp-admin/widgets.php, the element ID is dynamically generated
        const blogFilter = getBlogFilter();
        // It's used to display the state the API changes, we observe it to handle the logic of switching blog filter, UI changes, etc.
        searchResultsTitle = document.querySelector(
          '.jetpack-instant-search__search-results-title'
        );

        // No must elements, no tabs!
        if (!blogFilter || !searchResultsTitle) return;

        searchResultsTitleObserver.observe(searchResultsTitle, {
          subtree: true,
          characterData: true,
        });

        updateInputs(blogFilter);

        // Aligns the active tab with the applied blog filter
        if (supportInput?.checked) {
          setCurrentTab('support');
        } else if (forumsInput?.checked) {
          setCurrentTab('forums');
        } else {
          setCurrentTab('all');
        }

        // Sets tab to "All" when the search modal is closed
        document
          .querySelector('.jetpack-instant-search__overlay-close')
          .addEventListener('click', () => {
            setCurrentTab('all');
          });

        searchResultsView = mutation.target;
        noResultsTitle.className = `${searchResultsTitle.className} is-hidden`;
        searchResultsView.prepend(noResultsTitle);
        searchResultsView.prepend(tabs);

        // Tabs exists, moving sorting options down a little bit
        document
          .querySelector('.jetpack-instant-search__search-form-controls')
          .classList.add('has-tabs');

        hideClearFiiterBtnForBlogFilter();
        reorderSubtitles();

        blogFilter.classList.add('supportforums-search-is-hidden');
        prevHiddenFilter = blogFilter;

        // All ready, stopping observing and iterating
        observer.disconnect();
        return;
      }
    }
  }).observe(document.body, { childList: true, subtree: true });
});
