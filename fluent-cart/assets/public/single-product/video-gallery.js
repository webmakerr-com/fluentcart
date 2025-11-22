(function () {
    const videoWrappers = Array.from(document.querySelectorAll('[data-fluent-cart-product-video]'));
    if (!videoWrappers.length) {
        return;
    }

    const mainThumb = videoWrappers[0].closest('.fct-product-gallery-thumb');
    const galleryImage = mainThumb ? mainThumb.querySelector('img[data-fluent-cart-single-product-page-product-thumbnail]') : null;
    const videoThumbButtons = Array.from(document.querySelectorAll('[data-fluent-cart-video-thumb-button]'));

    const buildEmbedUrl = (rawUrl) => {
        if (!rawUrl) {
            return '';
        }

        const url = new URL(rawUrl, window.location.origin);

        if (url.hostname.includes('youtu.be')) {
            const videoId = url.pathname.replace('/', '');
            url.hostname = 'www.youtube.com';
            url.pathname = `/embed/${videoId}`;
            url.search = '';
        } else if (url.hostname.includes('youtube.com') && url.searchParams.get('v')) {
            const videoId = url.searchParams.get('v');
            url.pathname = `/embed/${videoId}`;
            url.search = '';
        } else if (url.hostname.includes('vimeo.com')) {
            const segments = url.pathname.split('/').filter(Boolean);
            if (segments.length) {
                const videoId = segments.pop();
                url.hostname = 'player.vimeo.com';
                url.pathname = `/video/${videoId}`;
                url.search = '';
            }
        }

        url.searchParams.set('autoplay', '1');
        url.searchParams.set('mute', '1');
        url.searchParams.set('playsinline', '1');

        return url.toString();
    };

    const setVideoPressedState = (isPressed) => {
        videoThumbButtons.forEach((button) => {
            button.setAttribute('aria-pressed', isPressed ? 'true' : 'false');
        });
    };

    const hideVideo = () => {
        videoWrappers.forEach((wrapper) => wrapper.classList.remove('is-active'));
        if (galleryImage) {
            galleryImage.classList.remove('is-hidden');
        }
        setVideoPressedState(false);
    };

    const loadVideo = (wrapper) => {
        if (wrapper.dataset.videoLoaded === 'true') {
            return;
        }
        const videoUrl = wrapper.dataset.videoUrl;
        if (!videoUrl) {
            return;
        }

        const iframe = document.createElement('iframe');
        iframe.className = 'fct-product-video-iframe';
        iframe.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('loading', 'lazy');
        iframe.src = buildEmbedUrl(videoUrl);
        wrapper.replaceChildren(iframe);
        wrapper.dataset.videoLoaded = 'true';
    };

    const showVideo = () => {
        const wrapper = videoWrappers[0];
        if (!wrapper) {
            return;
        }
        wrapper.classList.add('is-active');
        loadVideo(wrapper);
        if (galleryImage) {
            galleryImage.classList.add('is-hidden');
        }
        setVideoPressedState(true);
    };

    window.addEventListener('load', () => {
        videoWrappers.forEach((wrapper) => loadVideo(wrapper));
    });

    document.addEventListener('click', (event) => {
        const videoButton = event.target.closest('[data-fluent-cart-video-thumb-button]');
        if (videoButton) {
            event.preventDefault();
            showVideo();
            return;
        }

        const imageThumb = event.target.closest('[data-fluent-cart-thumb-control-button]');
        if (imageThumb && !imageThumb.matches('[data-fluent-cart-video-thumb-button]')) {
            hideVideo();
        }
    });
})();
