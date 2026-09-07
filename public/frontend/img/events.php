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
  text-align: center;
  font-family: arial;
  background-color:white;
}

.title {
  color: grey;
  font-size: 18px;
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
					<h1>Events</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Events</a>
						</li>
					</ul>
                    <h3 name="game_app" value="<?php echo $fetch_info['game_applied']?>"><?php if ($fetch_info['game_applied'] >= '3'){ ?>You Applied For Maximum Game <?php   } ?></h3>
				</div>
			</div>
            <div class="container">    
             <div class="row">
            <form method="post">
             <?php
                $dis='';     
                 $number=$fetch_info['game_applied'];
                 if($number>=1){
                     $dis='disabled';
                 }
                include'../connection.php';
                $selectquery= "select * from event";
                $query= mysqli_query($con,$selectquery);
                $nums= mysqli_num_rows($query);
                while($res= mysqli_fetch_array($query)){
                 $event_id_no=$res['event_id'];
                ?>     
                <div class="card my-2" style="width: 18rem;">
                  <div class="card-body">
                    <h3 class="card-title me-auto"><?php echo $res['event_name'];?></h3>
                      <span style="display:none" name="events_id"><?php echo $res['event_id'];?></span>
                    <h6 class="card-subtitle mb-2 text-muted">Event Category:- <?php echo $res['category']; ?></h6>
                    <p class="card-text">About the Game:-<?php echo $res['event_name']; ?></p>
                      <p class="card-text">Last date :-<?php echo $res['end_date']; ?></p>
                    <button type="submit" class="btn btn-primary" name="enroll_now" <?php if ($fetch_info['game_applied'] >= '3'){ ?> disabled <?php   } ?>onclick='window.location.reload(true);' >Enroll Now</button>
                  </div>
                </div>
               <?php     
                 }
               ?> 
              </form>    
             </div>
            </div>
            
            
		</main>
		<!-- MAIN -->
        
	</section>
	<!-- CONTENT -->
    



<script src="script.js"></script>

</body>
</html>

<?php
    include'../connection.php';
        if(isset($_POST['enroll_now']))
        {
        $event_id=$event_id_no;
        $applied_id=$fetch_info['id'];
        $request_status='pending';
        $game_no=$fetch_info['game_applied'];
        $game_no++;    
        $insert_query ="insert into event_applied(event_id,applied_id,request_status) VALUES ('$event_id', '$applied_id', '$request_status')";
        $res= mysqli_query($con,$insert_query);
            
        $std_update="update usertable set game_applied='$game_no' where id='$applied_id' ";
        $query= mysqli_query($con,$std_update);    
        if($res && $query){ 
                ?>
                 <script>
                    alert('insert successfull');
                </script>  
                <?php
            }else{
                 ?>
                <script>
                    alert('not insert successfull');
                </script>
                <?php
            }
    }
?>




