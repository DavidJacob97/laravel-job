<nav class="navbar navbar-expand-md navbar-dark bg-dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">Job Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav me-auto mb-2 mb-md-0">
              <li class="nav-item">
                  <a class="nav-link" href="/notifications"><i class="fas fa-bell"></i>
                    <span class="badge bg-danger" id="nav-notifications"></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/home">Home</a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" href="/jobs?all=0">My Jobs</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="/jobs?all=1">All Jobs</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/customers">Customers</a>
                </li>      
                <li class="nav-item">
                  <a class="nav-link" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </div>
          </div> 
      </nav>

      <script type="text/javascript">
  function updateNotifications() {
      $.ajax({
        url: '/notifications/count',
        dataType: 'json',
        success: function(data) {
          data = parseInt(data);
          $('#nav-notifications').html(data);
          if (data == 0) {
            $('#nav-notifications').hide();
          }else{
            $('#nav-notifications').show();
          }
          console.log('Notifications '+data);
        },
        complete: function() {
          setTimeout(updateNotifications, 10000);
        }
      });
  }

  $(document).ready(function(){
    console.log('hello');

    //call to get the amount of notifications and changes to the new amount of notifications
      updateNotifications();

  });

</script>

