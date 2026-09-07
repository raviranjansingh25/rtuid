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


.cont{
    position:absolute;
    top:110px;
     left:240px;
}

.id-main{
    
    width:700px;
    height:400px;
    background:white;
     background-repeat: no-repeat;
     background-size: 700px 400px;
     position:relative;
}
 
 .cont-dp{
   width: 182px;
    height: 182px;
    position: absolute;
    top: 162px;
    left: 83px;
   
    border-radius:50%;
     
 }
 
 .cont-dp img{
     border-radius:50%;
 }
 
 .cont-in{
     position:absolute;
     top:170px;
     left:280px;
 }
 
 .id-sec{
     background:#fcc603;
     height:100px;
     padding-top:30px;
 }
 
 .id-sec h2{
     color:black;
     text-align:center;
 }
 






    
</style>

	<!-- CONTENT -->
		<section id="content" style="width:100%;">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link"></a>
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
  <img src="../images/<?php echo $fetch_info['passport'] ?>" width="181" height="181">
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
					<h1>ID CARD</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">ID CARD</a>
						</li>
					</ul>
				</div>
			<a href="#" class="btn-download">
					<i class='bx bxs-cloud-download' > </i>
					<span class="text" id="generatePDF cmd" onclick="CreatePDFfromHTML()">Download PDF</span>
				</a>
			</div>
			
			
               <div class="id-main html-content" id="contentt" style="background-image:url('89.png')">
                   <div class="cont-dp">
                       <img src="../images/<?php echo $fetch_info['passport'] ?>" width="181" height="181">
                   </div>
                   <div class="cont-in">
                      <div> NAME:- <?php echo $fetch_info['name'] ?> <?php echo $fetch_info['lastname'] ?></div>
                       <div>FATHER'S NAME:- <?php echo $fetch_info['fathername'] ?></div>
                       <div>SEX:- <?php echo $fetch_info['gender'] ?></div>
                      <div> DATE OF BIRTH:-<?php echo $fetch_info['dob'] ?></div>
                       <div>ID:-JRFI/<?php echo $fetch_info['category'] ?>/<?php echo $fetch_info['state'] ?>/<?php echo $fetch_info['id'] ?></div>
                       <div>STATE:-<?php echo $fetch_info['statemain'] ?></div>
                   </div>
                   </div>
                    
                     
                   <div class="id-main html-conten" style="background-image:url('2.png');margin-top:15px;">
                           <div class="cont">
                      <div> EMAIL:- <?php echo $fetch_info['email'] ?></div>
                       <div style="padding-top:40px;">ADDRESS:-<?php echo $fetch_info['street'] ?></div>
                      
                   </div> 
                            
                            
                   </div> 
                   
               
                <div id="editor"></div>
			
				<a href="#" class="btn-download">
					<i class='bx bxs-cloud-download' > </i>
					<span class="text" id="generatePDF cmd" onclick="CreatePDFfromHTM()">Download PDF</span>
				</a>
           
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
	<script type="text/javascript">
//Create PDf from HTML...
function CreatePDFfromHTML() {
    var HTML_Width = $(".html-content").width();
    var HTML_Height = $(".html-content").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(".html-content")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("Your_PDF_Name.pdf");
        $(".html-content").hide();
    });
}
</script>

<script type="text/javascript">
//Create PDf from HTML...
function CreatePDFfromHTM() {
    var HTML_Width = $(".html-conten").width();
    var HTML_Height = $(".html-conten").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(".html-conten")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("Your_PDF_Name.pdf");
        $(".html-conten").hide();
    });
}
</script>
	
	
</body>
</html>