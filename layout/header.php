<nav class="navbar navbar-expand-lg bg-info navbar-dark">
	<div class="container">
		<a href="./" class="navbar-brand navbar-header" style="font-size: 3rem;">FINAL PROJECT</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto h4">
			<li class="nav-item ">	
			<a class="nav-link" href="./">TASK</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="absence.php">ABSENCE</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="account.php">ACCOUNT</a>

			</li>
			<li class="nav-item">
			<a class="btn btn-danger nav-link text-light" href="logout.php">LOGOUT</a>
			</li>
		</ul>
		</div>
	</div>
</nav>


<script>
    let current_url = document.location;
    document.querySelectorAll(".nav-item a").forEach(function(e){
       if(e.href == current_url){
          e.classList += " active";
       }
    });
</script>