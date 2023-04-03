document.addEventListener('DOMContentLoaded', () => {
  const breadcrumb = document.querySelector( '.spf-breadcrumb' );

  if ( !breadcrumb ) {
    return;
  }

  let links = breadcrumb.querySelectorAll( 'a' );

  // Filter out hidden links on the mobile view
  links = Array.from( links ).filter(
      ( link ) => getComputedStyle( link ).getPropertyValue( 'display' ) !== 'none'
  );

  if ( links.length < 3 ) {
    return breadcrumb.classList.remove('is-hidden');
  }

  let length = links.length;

  while (length > 1) {
    length--;

    const currentLink = links[length];
    const previousArrow = currentLink.previousElementSibling;

    breadcrumb.removeChild(currentLink);

    if (length > 1) {
      breadcrumb.removeChild(previousArrow);
    }
  }

  const popup = document.createElement('div');
  popup.classList.add('spf-breadcrumb-popup');

  const popupWrapper = document.createElement('div');
  popupWrapper.classList.add('spf-breadcrumb-popup-wrapper');
  popupWrapper.appendChild(popup);

  const dots = document.createElement('span');
  dots.classList.add('spf-breadcrumb-dots');
  dots.innerText = '...';
  dots.tabIndex = 0;

  const dotsWrapper = document.createElement('span');
  dotsWrapper.classList.add('spf-breadcrumb-dots-wrapper');
  dotsWrapper.appendChild(dots);
  dotsWrapper.appendChild(popupWrapper);

  links.shift();
  const arrow = breadcrumb.querySelector('.spf-breadcrumb-sep');

  while (links.length) {
    popup.appendChild(links.shift());

    if (links.length) {
      popup.appendChild(arrow.cloneNode(true));
    }
  }

  breadcrumb.insertBefore(dotsWrapper, arrow.nextSibling);
  breadcrumb.classList.remove('is-hidden');
});
