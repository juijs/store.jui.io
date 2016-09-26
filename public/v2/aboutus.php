<?php include_once '../../bootstrap.php' ?>
<?php include_once 'common.php'; 

$page_id = 'aboutus';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>JENNIFER UI: Store</title>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript" src="/v2/js/main.js"></script>
	
	
	<link href="/v2/css/<?php echo $theme ?>.css" rel="stylesheet" />
<style>

p#start
{
	position: relative;
	width: 16em;
	font-size: 200%;
	font-weight: 400;
	margin: 20% auto;
	color: #4ee;
}

@-webkit-keyframes intro {
	0% { opacity: 1; }
	90% { opacity: 1; }
	100% { opacity: 0; }
}

@-moz-keyframes intro {
	0% { opacity: 1; }
	90% { opacity: 1; }
	100% { opacity: 0; }
}

@-ms-keyframes intro {
	0% { opacity: 1; }
	90% { opacity: 1; }
	100% { opacity: 0; }
}

@-o-keyframes intro {
	0% { opacity: 1; }
	90% { opacity: 1; }
	100% { opacity: 0; }
}

@keyframes intro {
	0% { opacity: 1; }
	90% { opacity: 1; }
	100% { opacity: 0; }
}

hr {
	border:0px;
	border-top:1px solid #48cfad;
}

h1
{
	font-size: 42px;
	text-align: center;
	color: #434a54;
	font-weight:500;
	z-index: 1;
	margin-top:50px;
}

h1 sub
{
	display: block;
	letter-spacing: 0;
	line-height: 0.8em;
}

/* the interesting 3D scrolling stuff */
#titles
{
	width: 660px;
	height: 320px;
	font-size: 20px;
	margin:0 auto;
	text-align: justify;
	overflow: hidden;
	-webkit-transform-origin: 50% 100%;
	-moz-transform-origin: 50% 100%;
	-ms-transform-origin: 50% 100%;
	-o-transform-origin: 50% 100%;
	transform-origin: 50% 100%;
	-webkit-transform: perspective(300px) rotateX(0deg);
	-moz-transform: perspective(300px) rotateX(0deg);
	-ms-transform: perspective(300px) rotateX(0deg);
	-o-transform: perspective(300px) rotateX(0deg);
	transform: perspective(300px) rotateX(0deg);
}

#titles:after
{
	position: absolute;
	content: ' ';
	left: 0;
	right: 0;
	top: 0;
	bottom: 0;
	pointer-events: none;
}

#titles p
{
	text-align: justify;
	margin: 0.8em 0;
}

#titles p.center
{
	text-align: center;
}

#titles a
{
	color: #ff6;
	text-decoration: underline;
}

#titlecontent
{
	position: absolute;
	top: 100%;
	font-size:14px;
	line-height:2;
	text-align:center;
	color:rgba(0,0,0,0.6);
	-webkit-animation: scroll 30s linear 0s infinite;
	-moz-animation: scroll 30s linear 0s infinite;
	-ms-animation: scroll 30s linear 0s infinite;
	-o-animation: scroll 30s linear 0s infinite;
	animation: scroll 30s linear 0s infinite;
	background-image: -webkit-linear-gradient(bottom, rgba(255,255,255,1) 0%, transparent 100%);
	background-image: -moz-linear-gradient(bottom, rgba(255,255,255,1) 0%, transparent 100%);
	background-image: -ms-linear-gradient(bottom, rgba(255,255,255,1) 0%, transparent 100%);
	background-image: -o-linear-gradient(bottom, rgba(255,255,255,0.7) 0%, transparent 100%);
	background-image: linear-gradient(bottom, rgba(255,255,255,0.7) 0%, transparent 100%);
}

.title {
  font-size: 12px;
  font-weight: bold;
  letter-spacing: 1px;
  text-align: center;
  color: #434a54;
  margin-top:12px;
}

/* animation */
@-webkit-keyframes scroll {
	0% { top: 100%; }
	100% { top: -170%; }
}

@-moz-keyframes scroll {
	0% { top: 100%; }
	100% { top: -170%; }
}

@-ms-keyframes scroll {
	0% { top: 100%; }
	100% { top: -170%; }
}

@-o-keyframes scroll {
	0% { top: 100%; }
	100% { top: -170%; }
}

@keyframes scroll {
	0% { top: 100%; }
	100% { top: -170%; }
}

.our-team {
	width: 660px;
	margin:0 auto;
	margin-top:50px;
	margin-bottom:100px;
}

.our-team .line-text {
	position:relative;
	border-top: 1.5px solid #48cfad;
	box-sizing:border-box;
	height:30px;
}

.our-team .line-text h2 {
	position:absolute;
	top:-22px;
	left:50%;
	width:100px;
	height:30px;
	margin-left: -60px;
	font-size:12px;
	font-weight:bold;
	display:inline-block;
	padding:5px 20px;
	background-color:white;
}

.our-team .group {
	display:flex;
}

.our-team .group .item {
	flex: 1; 
	font-size:12px;
	color:#242222;
	box-sizing:border-box;
	text-align:center;
	line-height:2;
    opacity: 0.8;
}

.our-team .group .item .email a {
	color:#48cfad;
	text-decoration:none;
}
</style>


</head>
<body class="jui flat">

	<div class="t">

		<?php include_once "nav.php" ?>


	<div class="content-container list" style="text-align:center">
		<hr />

		<div class="title">ABOUT</div>
		
		<h1>Welcome. You had us at hello</h1>

		<div id="titles">
			<div id="titlecontent">

				<p >
					Thanks.
				</p>
			

			</div>
		</div>

		<div class="our-team">
			<div class="line-text">
				<h2>OUR TEAM</h2>
			</div>

			<div class="group">
				<div class="item">
						<div class="job">PROGRAMMER</div>
						<div class="name">Alvin Hong</div>
						<div class="email">
							<a href="mailto:alvin@jennifersoft.com">alvin@jennifersoft.com</a>
						</div>
				</div>
				<div class="item">
						<div class="job">PROGRAMMER</div>
						<div class="name">Jayden Park</div>
						<div class="email">
							<a href="mailto:jayden@jennifersoft.com">jayden@jennifersoft.com</a>
						</div>
				</div>
				<div class="item">
						<div class="job">UX / UI DESIGN</div>
						<div class="name">Yoha choi</div>
						<div class="email">
							<a href="mailto:yoha@jennifersoft.com">yoha@jennifersoft.com</a>
						</div>
				</div>
			</div>
		</div>


	</div>

	<?php include_once "footer.php" ?>

</div>
<?php include_once "modals.php" ?>
</body>
</html>
