   
//intialise fb srcipt
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '2224921381157888',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v3.2'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


        function fblogin(){
         
            FB.api('/me', function(response) {
                //console.log(JSON.stringify(response));
            });
            FB.getLoginStatus(function(response) {
               //console.log(response.status);
               
            });
        
            FB.login(function(response) {
                FB.api('/me', { locale: 'en_US', fields: 'name, email, gender, birthday' }, function(response) {
                  //console.log(response);
                  if(response.email == undefined){
                      alert('Unable to get your email! Please try again.');
                  } else if(response.name == undefined){
                      alert('Name not found! Please try again.');
                  } else {
                      $.ajax({
                        method: 'POST',
                        url: 'http://localhost/socialmedia/login/fb',
                        data: {email:response.email, name:response.name, gender:response.gender, fbid:response.id, authType: 'FB'},
                        dataType: 'json',
                        async: false,
                        success: function(data){
							console.log('test1');

                            if(data.error == undefined){
								
                               // console.log(data);
                                //return false;
                                //window.onbeforeunload = null;
                              location.href = "http://localhost/socialmedia/login/user_profile";
                            } else {
                                alert(data.error);
                            }
                        },
                        error: function(data)
                        {
							
					
                            alert('Something went wrong! Please try again later.');
                           
                        }
                    });
                  }
                });
            }, {scope: 'public_profile,email', auth_type: 'rerequest' });
        }


        function fblogout(){
          FB.getLoginStatus(function(response) {
          if (response && response.status === 'connected') {
              FB.logout(function(response) {
                  
                  window.location.reload();
              });
          }
        });
        }
  
