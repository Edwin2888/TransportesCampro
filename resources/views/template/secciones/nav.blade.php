<nav class="navbar navbar-default navbar-fixed-top" >
      <b class="section-title"> <i class="fa fa-book" aria-hidden="true"></i> @yield('title-section') <br></b>
      <div class="container" >
        <div class="navbar-header">
            <button class="btn btn-primary btn-sm men-min" style="background:#0060AC;border:0px;display:none;" type="button"  id="menu-hambur"><i
                                    class="fa fa-align-justify"></i></button>

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
                <a href="{{config('app.Campro')[2]}}/campro/home" style="    color: white;    font-size: 19px;    cursor: pointer;    background: transparent;    border: 0px;
                position: absolute; left:0px;   top: 16px; line-height: none; text-decoration: none; left:10px;">CAMPRO</a>                
 


        </div>
        <div id="navbar" class="navbar-collapse collapse" >
          <ul class="nav navbar-nav">
          </ul>
          <ul class="nav navbar-nav navbar-right" style="position: relative;left: -15px;">

            <li>
                <a href="{{config('app.Campro')[2]}}/campro/home" style="color: white;    font-size: 15px;    cursor: pointer;    background: transparent;    border: 0px;
          position: relative;    top: 5px; line-height: none; text-decoration: none; left:10px;">Regresar</a>   

            </li>

           <!-- <li><a href="../home">Home</a></li>

            <li><a href="https://www.dropbox.com/sh/v6qv8b63vz8xl7x/AACGsrcgxqQ0ChK5fQaSL6yOa?dl=0">Ayuda</a></li>

            <li><a href="../">Salir</a></li>
              <li><a href="#" data-toggle="modal" data-target="#form_reestablecer" title="Cambiar Contraseña"><i class="fa fa-lock"></i></a></li> -->

 <li><img src="{{url('/')}}/img/logo.png" width="152" height="60"></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
</nav>