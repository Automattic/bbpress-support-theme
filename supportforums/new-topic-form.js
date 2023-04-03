/*
 * For tags component
 */
document.addEventListener('DOMContentLoaded', () => {
  const tagContainer = document.querySelector('.spf-topic-form__tag-wrapper');

  // Non-HEs won't see the tags component
  if (!tagContainer) {
    return;
  }

  const input = document.querySelector('.spf-topic-form__tag-input');
  const hiddenInput = document.querySelector('#bbp_topic_tags');
  let tags = [];

  // Syncs spf's input.value with the bbp's input.value (on the topic edit form)
  if (hiddenInput.value) {
    tags = hiddenInput.value.split(',');
    renderTags();
  }

  input.addEventListener('keydown', (e) => {
    // Adds new tags by the comma or enter key
    if (e.key === ',' || e.key === 'Enter') {
      const tag = input.value.trim();

      // To avoid empty and duplicated tags
      if (tag && !tags.includes(tag)) {
        tags.push(tag);
        renderTags();
      }

      input.value = '';
      e.preventDefault();

      // Removes tags by the bckspace key
    } else if (e.key === 'Backspace' && !input.value && tags.length) {
      tags.pop();
      renderTags();
    }
  });

  // Removes tags by the delete button
  tagContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('spf-topic-form__tag-button')) {
      tags = tags.filter((tag) => e.target.parentNode.dataset.tag !== tag);
      renderTags();

      // After tags delection, setting focus to the input element for better UX
      input.focus();
    }
  });

  input.addEventListener('input', () => {
    // To omit comma characters
    input.value = input.value.replace(',', '');
  });

  // Activates the focus effect of the tags component
  input.addEventListener('focus', () => {
    tagContainer.classList.add('is-active');
  });

  // Deactivates the focus effect of the tags component
  input.addEventListener('blur', () => {
    tagContainer.classList.remove('is-active');

    // Syncs bbp's input.value with the spf's input.value 
    lastTag = input.value.trim();
    hiddenInput.value = lastTag ? [...tags, lastTag].join(',') : tags.join(',');
  });

  tagContainer.addEventListener('focus', (e) => {
    // When focusing the tags component (but not a delete button), setting focus to the input element for better UX
    if (!e.target.classList.contains('spf-topic-form__tag-button')) {
      input.focus();
    }
  });

  function renderTags() {
    // Cleans all tags
    document.querySelectorAll('.spf-topic-form__tag').forEach((tag) => {
      tagContainer.removeChild(tag);
    });

    // And then renders them
    [...tags].reverse().forEach((tag) => {
      tagContainer.prepend(createTag(tag));
    });
  }

  function createTag(label) {
    const div = document.createElement('div');
    div.setAttribute('class', 'spf-topic-form__tag');
    div.setAttribute('data-tag', label);

    const span = document.createElement('span');
    span.setAttribute('class', 'spf-topic-form__tag-label');
    span.innerHTML = label;

    const button = document.createElement('button');
    button.setAttribute('class', 'spf-topic-form__tag-button');
    button.setAttribute('type', 'button');

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'spf-topic-form__tag-icon');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('width', '24');
    svg.setAttribute('height', '24');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute(
      'd',
      'M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z'
    );

    svg.appendChild(path);
    button.appendChild(svg);
    div.appendChild(span);
    div.appendChild(button);

    return div;
  }
});

/*
 * For submission state & error handlings
 */
document.addEventListener('DOMContentLoaded', () => {
  const title = document.querySelector('#bbp_topic_title');
  const form = document.querySelector('#new-post');
  const topicSubmitBtn = document.querySelector('#bbp_topic_submit');
  const replySubmitBtn = document.querySelector('#bbp_reply_submit');

  // Reply form has no the title field, let's skip the error handling
  if (replySubmitBtn) {
    form.addEventListener('submit', () => {
      replySubmitBtn.classList.add('is-busy');
    });

    return;
  }

  // Intialize the state of the submit button
  topicSubmitBtn.disabled = true;

  // To cover the case:
  // 1. Submitted the form and then clicking the "Back" button of the browser
  // 2. Back to the new topic form and then the browser autofills the title field (after the DOM has loaded)
  // 3. Hence, the submit button should be enabled
  setTimeout(() => {
    topicSubmitBtn.disabled = !title.value;
  }, 500);

  title.addEventListener('input', (e) => {
    // The title field shouldn't be empty
    topicSubmitBtn.disabled = !e.target.value.trim();
  });

  form.addEventListener('submit', () => {
    topicSubmitBtn.classList.add('is-busy');
  });
});
