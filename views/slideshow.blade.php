<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Slideshow</title>
	<!-- EXTERNAL SERVICES  -->
	<!-- Fonts and Icons is all we go outside for -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/70bfe3d00e.js" crossorigin="anonymous"></script>
	<!-- CSS -->
	<!--
		TVs have a long shelf life which likely means browsers that are not compatible with modern JS packaging.
		Not interested in Babeling this so we're avoid Tailwind etc and going old-school basic CSS
	 -->
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		html {
			font-family: 'Open Sans', sans-serif;
			height: 100%;
			font-size: 100%;
			color: #FFF;
		}

		body {
			height: 100%;
			width: 100%;
			overflow: hidden;
		}

		.slideshow {
			height: 100%;
			width: 100%;
			background-position: center;
			background-size: cover;
			background-repeat: no-repeat;
		}

		.caption {
			position: fixed;
			left: 4rem;
			bottom: 4rem;
			text-shadow: 2px 2px 2px #000;
		}

		.title {
			font-size: 2em;
			margin-bottom: 0.2em;
		}

		.date {
			font-size: 1.2em;
			font-style: italic;
		}

		.controls {
			position: fixed;
			right: 4rem;
			bottom: 4rem;
			font-size: 5em;
			background: rgba(0, 0, 0, 0.4);
			border-radius: 50%;
			padding: 20px;
			visibility: hidden;
		}

		.control {
			cursor: pointer;
			margin: 0 0.2em;
			text-shadow: 2px 2px 2px #000;
			display: inline-block;
			padding: 4px;
		}

		.control:hover {
			color: #14cbeb;
		}

		.loading {
			position: fixed;
			left: 50%;
			top: 50%;
			width: 50%;
			height: 50%;
			margin-left: -25%;
			text-align: center;
			color: #FFF;
			text-shadow: 2px 2px 2px #000;
			font-size: 5em;
			visibility: hidden;
		}

		.hidden {
			visibility: hidden;
			;
		}
	</style>
</head>

<body>
	<div class="slideshow"></div>
	<div class="caption">
		<div class="title"></div>
		<div class="date"></div>
	</div>
	<div class="controls">
		<i id="previous" data-adjustment="1" class="control fa-solid fa-caret-left hidden"></i>
		<i id="toggle" class="control fa-solid fa-pause"></i>
		<i id="next" data-adjustment="-1" class="control fa-solid fa-caret-right hidden"></i>
	</div>
	<div class="loading">
		<i class="fa-solid fa-wifi fa-bounce"></i>
	</div>
	<!-- SLIDESHOW LOGIC -->
	<script>
		/**
		 * As with CSS, we want to avoid modern build tools and indeed some modern JS.
		 * Keeping it simple and old-school as working widely is better than bleeding edge in this case
		 * At time of writing my "Smart TV" is Chrome 38 😬
		 */
		var photoHistory = [],
			photoHistoryPosition = 0,
			photoDelay = <?=config('photos.delay')?> ,
			controls = document.querySelector(".controls"),
			loading = document.querySelector(".loading"),
			toggle = document.querySelector("#toggle"),
			historyBack = document.querySelector("#previous"),
			historyForward = document.querySelector("#next"),
			imageElement = document.querySelector(".slideshow"),
			titleElement = document.querySelector(".title"),
			dateElement = document.querySelector(".date"),
			movementTimer,
			slideshowTimer,
			nextImage;

		function toggleControls() {
			if (typeof movementTimer !== "undefined") {
				clearTimeout(movementTimer);
			}
			controls.style.visibility = "visible"
			movementTimer = setTimeout(function() {
				controls.style.visibility = "hidden"
			}, 2000);
		}

		function performApiCall(path) {
			try {
				var xhr = new XMLHttpRequest()
				xhr.open('GET', path, false)
				xhr.send()

				if (xhr.readyState == 4 && xhr.status == 200) {
					return JSON.parse(xhr.responseText)
				} else if (xhr.status <= 599 && xhr.status >= 403) {
					throw 'AUTH_ERROR'
				} else if (xhr.status <= 599 && xhr.status >= 400) {
					throw 'SERVER_ERROR'
				} else {
					throw 'NETWORK_ERROR'
				}
			} catch (e) {
				throw 'NETWORK_ERROR'
			}
		}

		function primeNextImage(withData) {
			nextImage = withData;
			img = new Image();
			img.src = nextImage.url;
		}

		function changeImageDisplay(imageData) {
			imageElement.style.backgroundImage = "url('" + imageData.url + "')";
			titleElement.innerText = imageData.album_title;
			dateElement.innerText = imageData.date_taken;
		}

		function displayPrimedImage() {
			changeImageDisplay(nextImage)
		}

		function getNextImage() {
			if (nextImage) {
				addToHistory(nextImage);
			}
			result = performApiCall('/photo/random');
			primeNextImage(result);
			slideshowTimer = setTimeout(slideTimerFinished, photoDelay);
		}

		function slideTimerFinished() {
			displayPrimedImage();
			getNextImage()
		}

		function displayFirstImage() {
			result = performApiCall('/photo/random');
			primeNextImage(result);
			displayPrimedImage();
			getNextImage()
		}

		function pauseSlideshow() {
			clearTimeout(slideshowTimer);
			slideshowTimer = null;
			toggle.classList.remove('fa-pause');
			toggle.classList.add('fa-play');
		}

		function resumeSlideshow() {
			toggle.classList.remove('fa-play');
			toggle.classList.add('fa-pause');
			if (nextImage) {
				displayPrimedImage(nextImage);
			}
			getNextImage()
		}

		function toggleClicked() {
			if (slideshowTimer) {
				pauseSlideshow();
				return;
			}
			resumeSlideshow();
		}

		function addToHistory(imageData) {
			if (photoHistoryPosition === 0) {
				photoHistory.unshift(imageData);
				if (photoHistory.length > 50) {
					photoHistory.shift();
				}
			}
			updateHistoryButtonDisplay()
		}

		function updateHistoryButtonDisplay() {
			if (photoHistory.length > 1 && photoHistoryPosition !== (photoHistory.length - 1)) {
				historyBack.classList.remove("hidden")
			} else {
				historyBack.classList.add("hidden")
			}
			if (photoHistory.length > 1 && photoHistoryPosition > 0) {
				historyForward.classList.remove("hidden")
			} else {
				historyForward.classList.add("hidden")
			}
		}

		function historyClicked(event) {
			pauseSlideshow();
			nextImage = null;
			photoHistoryPosition = photoHistoryPosition + (parseInt(event.target.getAttribute('data-adjustment')));
			changeImageDisplay(photoHistory[photoHistoryPosition])
			updateHistoryButtonDisplay();
		}

		window.addEventListener("mousemove", toggleControls);
		toggle.addEventListener("click", toggleClicked);
		historyBack.addEventListener("click", historyClicked);
		historyForward.addEventListener("click", historyClicked);

		displayFirstImage();
	</script>
</body>

</html>
