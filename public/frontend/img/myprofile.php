<?php require ('header.php') ?>

<style>
     .dropdown {
  position: relative;
  display: inline-block;
  left:-30px;
}

.dropdown-content {
  display: none;
  position: absolute;
    right:10px;
  background-color: #f9f9f9;
  min-width: 140px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  padding: 12px 16px;
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;

  font-family: arial;
  background-color:white;
}

.title {
  color: grey;
  font-size: 18px;
}

.card p
{
    margin-left:82px;
}




    
</style>

	<!-- CONTENT -->
	<section id="content" style="width:100%;">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
		
			<a href="#" class="profile">
			
				<div class="dropdown">
  	<img src="../images/<?php echo $fetch_info['passport'] ?>">
  <div class="dropdown-content">
  <p><a href="myprofile.php" >MY PROFILE</a></p>
  <p><a href="../logout-user.php" class="logout">LOGOUT</a></p>
  </div>
</div>
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>MY PROFILE</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">MY PROFILE</a>
						</li>
					</ul>
				</div>
			
			</div>
           <div class="card" >
        <img src="../images/<?php echo $fetch_info['passport'] ?>" width="181" height="181" style="margin:auto;padding-top:5px;">
  <p class="title"><?php echo $fetch_info['name'] ?> <?php echo $fetch_info['lastname'] ?></p>
  <p>FATHER NAME:-<?php echo $fetch_info['fathername'] ?></p>
  <p>SEX:-<?php echo $fetch_info['gender'] ?></p>
   <p>ID:-JRFI/<?php echo $fetch_info['category'] ?>/<?php echo $fetch_info['state'] ?>/<?php echo $fetch_info['id'] ?></p>
 
 
 
</div>
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>