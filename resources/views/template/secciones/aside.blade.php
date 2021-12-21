<div class="side-menu"  id="menu_cam"  style="display:none;z-index:10" >

    	<nav class="navbar navbar-default" role="navigation" >
        <!-- Brand and toggle get grouped for better mobile display -->

        	<div class="navbar-header"  >

            <div class="brand-wrapper" >
                <div class="brand-name-wrapper">
                    <a class="navbar-brand" href="#" data-toggle="modal" data-target="#form_reestablecer" style="height:50px;">
                        ALEJANDRA QUINTERO
                    </a>
                </div>
            </div>

            <ul class="nav navbar-nav">
                 <li>
                    <form action="http://localhost:8000/iniCampro" method="POST">
                        <input type="hidden" value="VALOR" name="user" />
                        <input type="hidden" value="VALOR" name="proy" />
                        <input type="hidden" value="{{config('app.server_transportes')}}/redes/ordenes/home" name="ruta" />
                        <button style="color: #FFF;padding: 10px;background: transparent;border: 0px;    font-size: 12px;"><i class="fa fa-tag" aria-hidden="true"></i> Gestionar Proyectos</button>
                    </form>
                 </li>
                 
            </ul>

            <ul class="nav navbar-nav" >
            	<li class="panel panel-default" id="dropdown">
                        <a data-toggle="collapse" href="#dropdown-ac">
                            <i class="fa fa-puzzle-piece" ></i> COLABORATIVOS Y DE GESTIÓN <span class="caret"></span>
                        </a>

                        <!-- Dropdown ac  -->
                        <div id="dropdown-ac" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ul class="nav navbar-nav">
                                         <li>
                                         	<a href='http://nncc-cam.com' target="_blank">Gestión de Requerimientos</a>"
                                         </li>

                                </ul>
                            </div>
                        </div>
                    </li>';

            </ul>
            </div>
        </nav>
</div>