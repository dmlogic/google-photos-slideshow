<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Slideshow</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/70bfe3d00e.js" crossorigin="anonymous"></script>
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
			background-image: url(photos/323/AMPTTLcCFsDyGh-R8cenM4vun0DyNrNBYJCc8Mxhv8W7SESpJ_rE9JUaoWsH2g2xr_vmsr_YYHllqCj2QlFpNlS7DSCTNV5vUg.jpg);
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
	<div id="slideshow" class="slideshow"></div>
	<div class="caption">
		<div id="title" class="title">My title</div>
		<div id="date" class="date">My Date</div>
	</div>
	<div class="controls">
		<i id="previous" class="control fa-solid fa-caret-left hidden"></i>
		<i id="toggle" class="control fa-solid fa-pause"></i>
		<i id="next" class="control fa-solid fa-caret-right hidden"></i>
	</div>
	<div class="loading">
		<i class="fa-solid fa-wifi fa-bounce"></i>
	</div>
	<script>
		var photoLog = [],
			controls = document.querySelector(".controls"),
			loading = document.querySelector(".loading"),
			toggle = document.querySelector("#toggle"),
			previous = document.querySelector("#previous"),
			movementTimer;

		function toggleControls() {
			if (typeof movementTimer !== "undefined") {
				clearTimeout(movementTimer);
			}
			controls.style.visibility = "visible"
			movementTimer = setTimeout(function() {
				controls.style.visibility = "hidden"
			}, 2000);
		}

		window.addEventListener("mousemove", toggleControls);
	</script>
</body>

</html>
