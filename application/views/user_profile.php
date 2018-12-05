



<div class="card">
  <img src="img.jpg" alt="John" style="width:100%">
 <h1><?php echo $profile['name'];?></h1>
  <b>Email:</b><?php echo $profile['email'];?></p>
  
  <a href="#"><i class="fa fa-dribbble"></i></a>
  <a href="#"><i class="fa fa-twitter"></i></a>
  <a href="#"><i class="fa fa-linkedin"></i></a>
  <a href="#"><i class="fa fa-facebook"></i></a>
  <p><a href="" onclick="fblogout()">Logout</button></p>
</div>

<script>
function fblogout(){
          FB.getLoginStatus(function(response) {
          if (response && response.status === 'connected') {
              FB.logout(function(response) {
                  <?php $this->session->unset_userdata('logged_in');?>
                location.href='http://localhost/socialmedia/google';
              });
          }
        });
        }
</script>