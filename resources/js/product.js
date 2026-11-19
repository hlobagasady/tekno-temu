const mainMedia = document.getElementById('mainMedia');
const mainVideo = document.getElementById('mainVideo');
const gallery = document.querySelector('.gallery');
const mediaButtons = [...document.querySelectorAll('.thumb')];
const imageButtons = mediaButtons.filter((button) => button.hasAttribute('data-media'));

let activeImageIndex = 0;
let autoSlideTimer;
let videoIsActive = false;

function stopAutoSlide() {
    clearInterval(autoSlideTimer);
}

function startAutoSlide() {
    stopAutoSlide();

    if (videoIsActive || imageButtons.length < 2) return;

    autoSlideTimer = setInterval(() => {
        activeImageIndex = (activeImageIndex + 1) % imageButtons.length;
        showImage(imageButtons[activeImageIndex], false);
    }, 4000);
}

function setActiveButton(button) {
    mediaButtons.forEach((item) => item.classList.toggle('active', item === button));
}

function showImage(button, restartAutoSlide = true) {
    activeImageIndex = imageButtons.indexOf(button);
    videoIsActive = false;
    mainMedia.src = button.dataset.media;
    mainMedia.hidden = false;
    mainVideo.pause();
    mainVideo.hidden = true;
    setActiveButton(button);

    if (restartAutoSlide) startAutoSlide();
}

imageButtons.forEach((button, index) => {
    button.onclick = () => {
        activeImageIndex = index;
        showImage(button);
    };
});

document.querySelectorAll('[data-video]').forEach((button) => {
    button.onclick = () => {
        videoIsActive = true;
        stopAutoSlide();
        mainVideo.src = button.dataset.video;
        mainVideo.hidden = false;
        mainMedia.hidden = true;
        setActiveButton(button);
        mainVideo.play().catch(() => {});
    };
});

gallery.onmouseenter = stopAutoSlide;
gallery.onmouseleave = startAutoSlide;
gallery.onfocusin = stopAutoSlide;
gallery.onfocusout = startAutoSlide;

document.querySelectorAll('.thumb-video').forEach((video) => {
    video.addEventListener('loadeddata', () => {
        video.currentTime = 0;

        try {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            video.poster = canvas.toDataURL('image/jpeg', 0.8);
        } catch {
            // Keep the fallback poster when the video is hosted on another origin.
        }
    }, { once: true });
});

if (imageButtons[0]) {
    showImage(imageButtons[0], false);
    startAutoSlide();
}

