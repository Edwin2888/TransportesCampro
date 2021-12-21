@extends('template.index')

@section('title')
    <?php if($tipod==0){
            echo "Gestor de Materiales";
          }else{
            echo "Gestor de Descargos";
          } ?>
@stop

@section('title-section')
    <?php if($tipod==0){
            echo "Gestor de Materiales";
          }else{
            echo "Gestor de Descargos";
          } ?>
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/carrusel.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/styleCarrusel.css">
@stop

@section('content')
    <style type="text/css">
    .selected_1
    {
        background: #5bc0de;
    }
    #menuizquierda{
        width:100%;
        max-width: 300px; 
        float: left;        
       /* background: #1D2127;*/
            background: #0060ac;
    }
    #imboxderecha{
        width: calc(100% - 300px); 
        float: left;
    }
    .contenizq{
            margin:17px;
         /* background: #1D2127;*/
            background: #0060ac;
            border-right: 1px solid #242830;
            color: #abb4be;
    }
    .contenizq a.menu-item.active {
        background: #F28D01;
        border-radius: 3px;
    }
    
    .contenizq{
        color:transparent;
    }
    /*
    .contenizq a.menu-item.active:hover {
        background: #F28D01;
        border-radius: 3px;
        color: #000000;
    }*/
    .contenizq a.menu-item:hover {
        background: #F28D01;
        border-radius: 3px;
        color: #000000;
    }
    .contenizq a.menu-item {
        /*color: #abb4be;*/
        color:#ffffff;
        display: block;
        /* margin: 0 -39px 0 -35px; */
        padding: 10px 27px 10px 27px;
        text-decoration: none;
    }
    .contenizq hr.separator {
        background-image: -webkit-linear-gradient(left, transparent, rgba(0, 0, 0, 0.4), transparent);
        background-image: -moz-linear-gradient(left, transparent, rgba(0, 0, 0, 0.4), transparent);
        background-image: -ms-linear-gradient(left, transparent, rgba(0, 0, 0, 0.4), transparent);
        background-image: -o-linear-gradient(left, transparent, rgba(0, 0, 0, 0.4), transparent);
        margin: 20px -35px 20px;
    }
.sidebar-widget .widget-header {
    position: relative;
    margin: 0;
}    
.sidebar-widget .widget-header .widget-toggle {
    font-size: 2.7rem;
    line-height: 1.3rem;
    color: #465162;
    position: absolute;
    right: 0;
    top: 0;
    cursor: pointer;
    text-align: center;
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
    -webkit-transition-property: -webkit-transform;
    -moz-transition-property: -moz-transform;
    transition-property: transform;
    -webkit-transition-duration: 0.2s;
    -moz-transition-duration: 0.2s;
    transition-duration: 0.2s;
    -webkit-transition-timing-function: linear;
    -moz-transition-timing-function: linear;
    transition-timing-function: linear;
}




.mailbox .mailbox-actions {
    border-top: 1px solid #EFEFEF;
    border-bottom:  1px solid #EFEFEF;
    padding-left: 40px;
    padding-right: 40px;
    
    padding-top: 14px;
    padding-bottom: 14px
}

 .mailbox-actions ul a.item-action.text-danger:hover {
    color: #a82824 !important;
}
.mailbox .mailbox-actions ul a.item-action:hover {
    color: #57636C;
    text-decoration: none;
}
.mailbox-actions ul a.item-action {
    background: #FFF;
    border-radius: 100px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2);
    color: #B4BBC1;
    display: inline-block;
    height: 30px;
    line-height: 2.9rem;
    position: relative;
    width: 30px;
    text-align: center;
    -webkit-transition-property: color;
    -moz-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    -moz-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: cubic-bezier(0.2, 0.6, 0.25, 1);
    -moz-transition-timing-function: cubic-bezier(0.2, 0.6, 0.25, 1);
    transition-timing-function: cubic-bezier(0.2, 0.6, 0.25, 1);
    -webkit-transition-delay: 0.1s;
    -moz-transition-delay: 0.1s;
    transition-delay: 0.1s;
}
 .mailbox-actions ul a {
    color: #171717;
    text-decoration: none;
    font-size: 1.5rem;
}
.text-danger {
    color: #dc3545 !important;
}

.list-unstyled {
    /* padding-left: 0; */
    list-style: none;
}
.ib {
    display: inline-block;
    /* vertical-align: top; */
}
.mr-2, .mx-2 {
    margin-right: 0.5rem !important;
}
.mailbox-email-list li {
    border-bottom: 1px solid #f7f7f7;
    height: 50px;
    line-height: 50px;
    padding: 0 40px;
    position: relative;
    font-size: 1rem;
} 
.mailbox-email-list li.unread a {
    color: #555;
    font-weight: 500;
}
.mailbox-email-list .col-sender {
    float: left;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 250px;
    white-space: nowrap;
}
.mailbox-email-list li.unread a {
    color: #555;
    font-weight: 500;
}
.mailbox-email-list .col-mail {
    bottom: 0;
    left: 290px;
    position: absolute;
    right: 40px;
    top: 0;
}
.mailbox-email-list .col-mail .mail-attachment {
    position: absolute;
    top: 0;
}
.mailbox-email-list .col-mail .mail-content {
    left: 0;
    right: 140px;
    top: 13px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.mailbox-email-list .col-mail .mail-date {
    padding-left: 80px;
    right: 0;
    top: 13px;
    width: 150px;
}
.mailbox-email-list .col-mail .mail-content,.mailbox-email-list .col-mail .mail-date,.mailbox-email-list .col-mail .mail-attachment {
    position: absolute;
    top: 0;
}
.mailbox-email-list .col-mail .mail-attachment {
    color: #BBB;
    right: 100px;
    line-height: 50px;
}










    body{
        min-height: 20px !important;
        overflow: auto; 
    }
    
/** initial setup **/
.nano {
  position : relative;
  width    : 100%;
  height   : 100%;
  overflow : hidden;
}
.nano .content {
  position      : absolute;
  overflow      : scroll;
  overflow-x    : hidden;
  top           : 0;
  right         : 0;
  bottom        : 0;
  left          : 0;
}
.nano .content:focus {
  outline: thin dotted;
}
.nano .content::-webkit-scrollbar {
  visibility: hidden;
}
.has-scrollbar .content::-webkit-scrollbar {
  visibility: visible;
}
.nano > .pane {
  background : rgba(0,0,0,.25);
  position   : absolute;
  width      : 10px;
  right      : 0;
  top        : 0;
  bottom     : 0;
  visibility : hidden\9; /* Target only IE7 and IE8 with this hack */
  opacity    : .01; 
  -webkit-transition    : .2s;
  -moz-transition       : .2s;
  -o-transition         : .2s;
  transition            : .2s;
  -moz-border-radius    : 5px;
  -webkit-border-radius : 5px;  
  border-radius         : 5px;
}
.nano > .pane > .slider {
  background: #444;
  background: rgba(0,0,0,.5);
  position              : relative;
  margin                : 0 1px;
  -moz-border-radius    : 3px;
  -webkit-border-radius : 3px;  
  border-radius         : 3px;
}
.nano:hover > .pane, .pane.active, .pane.flashed {
  visibility : visible\9; /* Target only IE7 and IE8 with this hack */
  opacity    : 0.99;
}

.nano .content { padding: 10px; }
.nano .pane   { background: #888; }
.nano .slider { background: #111; }

#tablecontentcorr td{    
    border: solid 0px transparent;
    border-bottom: 1px solid #cccccc;
    padding-top: 8px !important;
    padding-bottom: 7px !important;
    padding-left: 5px !important;
    padding-right: 5px !important;
}
.noleido{
    font-weight: bold;background: #dcdcf0;
}

#bandejanada{
    background-color:  #F28D01;
    border: 1px solid  #F28D01;
}
#lista_tipo li{
    padding-top: 2px;
    padding-bottom: 2px;
}

    </style>
<script>
    $(document).ready(function(){
        $("#sincronizando1").hide();
        $("#sincronizando2").hide();
        $("#sincronizando3").hide();
        $("#sincronizando4").hide();
    });
</script>
    
    <main>
        <div class="panel-body wbs mailbox" style="padding-left: 0px;padding-right: 0px;    padding-bottom: 0px;">
            <div class="dv-clear " style="height:45px;"></div>
            <div id="menuizquierda" class="nano   nanoizq" >
                <div class="contenizq content">
                    <div class="inner-menu-content ">
                        <a  class="btn btn-block btn-primary btn-md pt-sm pb-sm text-md" id="bandejanada" style="cursor: auto;">
                                <i class="fa fa-envelope mr-xs"></i>
                                Bandeja
                        </a>

                        <ul class="list-unstyled mt-xl pt-md" id="lista_tipo">
                                <!--
                                <li>
                                        <a href="mailbox-folder.html" class="menu-item active">Inbox <span class="label label-primary text-weight-normal pull-right">43</span></a>
                                </li> -->
                                <li>
                                    <a  onclick="modbandeja(this,event,0)" data-dato="0" class="menu-item active" style="cursor: pointer;">Todos</a>
                                </li>
                                <li>
                                    <a  onclick="modbandeja(this,event,1)" data-dato="1" class="menu-item" style="cursor: pointer;">Sin Leer</a>
                                </li>
                                <li>
                                    <a  onclick="modbandeja(this,event,2)" data-dato="2" class="menu-item" style="cursor: pointer;">Leidos</a>
                                </li>
                        </ul>

                        <hr class="separator" />
							
							
               
                </div>
            </div>
            </div>
            <div id="imboxderecha" style="" class="mailbox"  >
                
                <!-- //////////////////////////////////////// -->
               
                <div class="mailbox-actions">
                    
                    <div class="col-md-3">
                        <label>Tipo de Proyecto</label>
                        <select id="tipoproyecto" name="tipoproyecto"  onchange="cambia()" class="form-control" >
                            <option value="0">Seleccione Tipo de Proyecto</option>
                            <?php 
                                   foreach($tipopro as $tipo){  ?>
                                    <option value="<?= $tipo->id_proyecto ?>"><?= $tipo->des_proyecto ?></option>                                   
                            <?php }  ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label> Proyecto</label>
                        <input type="text" name="proyecto" id="proyecto"  onchange="cambia()" class="form-control" >
                    </div>
                    
                    <div class="col-md-2">
                        <label> Fecha Inicial</label>
                        <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" >

                            <input class="form-control" size="10" style="height:30px;" type="text" onchange="cambia()"  value="" name="fecha1" id="fecha1" placeholder="dd/mm/aaaa" required>
                            <!-- <span class="input-group-addon"><i class="fa fa-times"></i></span> -->
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <label> Fecha Final</label>
                        <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" >

                            <input class="form-control" size="10" style="height:30px;" type="text" onchange="cambia()" value="" name="fecha2" id="fecha2" placeholder="dd/mm/aaaa" required>
                            <!-- <span class="input-group-addon"><i class="fa fa-times"></i></span> -->
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    
                    <div class="col-md-1" style="    padding-top: 25px;    padding-left: 0px;  padding-right: 0px;">
                        <ul class="list-unstyled m-0 pt-3 pb-3">
                                <!--<li class="ib mr-2">
                                        <div class="btn-group">
                                                <a href="#" class="item-action fa fa-chevron-down" data-toggle="dropdown"></a>

                                                <ul class="dropdown-menu" role="menu">
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">All</a></li>
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">None</a></li>
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Read</a></li>
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Unread</a></li>
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Starred</a></li>
                                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Unstarred</a></li>
                                                </ul>
                                        </div>
                                </li>-->
                                <li class="ib mr-2">
                                    <a class="item-action fa fa-refresh" onclick="actualizar()"></a>
                                </li>
                                 <li class="ib mr-2">
                                    <a class="item-action fa fa-bars" onclick="ocultarmenu()"></a>
                                </li>
                                <!--
                                <li class="ib mr-2">
                                        <a class="item-action fa fa-tag" href="#"></a>
                                </li>
                                <li class="ib">
                                        <a class="item-action fa fa-times text-danger" href="#"></a>
                                </li>-->
                        </ul>
                    </div>  
                    
                    <div style="clear:both;width: 0px;"></div>
                </div>
                <!-- //////////////////////////////////////// -->
                <div style="position: relative;">
                    <div class="contienecorreoeant" style="width:100%;overflow-x:hidden;">
                      <table id="tablecontentcorr" style="overflow-x:hidden;width: 1543px;    background: #f1f1f1;">
                                <thead>
                                    <tr>                                        
                                        <td style="width: 121px;text-align: center;">
                                            Orden
                                        </td>        
                                        <?php if($tipod==0){ ?>
                                        <td style="width: 121px;text-align: center;">
                                            Despachos
                                        </td> 
                                        <?php } ?>
                                        <td style="width: 200px;text-align:center;">
                                            Tipo Proyecto
                                        </td>                                   
                                        <td style=" width: 242px;  overflow-x: hidden;    text-align: center;">
                                            Proyecto
                                        </td>                                   
                                        <td style="width: 115px;text-align: center;">
                                            Fecha Ejecución
                                        </td>                                   
                                        <td style="width: 115px;text-align: center;">
                                            Fecha Final
                                        </td>                                   
                                        <td style="width: 100px; text-align: center;">
                                            H Cierre
                                        </td>                                
                                        <td style="width: 100px; text-align: center;">
                                            H Corte
                                        </td>                                
                                        <td style="width: 400px;text-align: center;" >
                                            Observaciones
                                        </td>                              
                                        <td style="width: 150px; text-align: center;">
                                            Fecha
                                        </td>
                                    </tr>
                                </thead>
                            </table> 
                    </div>    
                    <div id="div-cargando" style="display: none;width: 100%; position: absolute; left: 0px;    top: 0px; background: rgba(175, 169, 169, 0.4); opacity: 0.5;   z-index: 9999; ">
                        <img src="{{url('/')}}/img/loader6.gif" id="cargandocorreos" class="loading" alt="Loading..." style="padding-left: calc(50% - 15px);">
                    </div>
                    <div class="contienecorreoe nanod nanoder mailbox-email-list" style="overflow-y: auto;">
                        <div class="content">
                           <!-- <ul id="contenli" class="list-unstyled"></ul> -->
                            <table id="tablecontentcorr" style="overflow-x: scroll;width: 1543px;">    
                                <tbody id="contenli" ></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- //////////////////////////////////////// -->
            </div>
        </div>

    </main>
    

<script type="text/javascript" src="{{url('/')}}/js/jquery.endless-scroll.js"></script>

<script type="text/javascript" >
/*! nanoScrollerJS v0.7.2 (c) 2013 James Florentino; Licensed MIT */

(function(e,t,n){"use strict";var r,i,s,o,u,a,f,l,c,h,p,d,v,m,g,y,b,w,E,S,x;S={paneClass:"pane",sliderClass:"slider",contentClass:"content",iOSNativeScrolling:!1,preventPageScrolling:!1,disableResize:!1,alwaysVisible:!1,flashDelay:1500,sliderMinHeight:20,sliderMaxHeight:null,documentContext:null,windowContext:null},y="scrollbar",g="scroll",l="mousedown",c="mousemove",p="mousewheel",h="mouseup",m="resize",u="drag",w="up",v="panedown",s="DOMMouseScroll",o="down",E="wheel",a="keydown",f="keyup",b="touchmove",r=t.navigator.appName==="Microsoft Internet Explorer"&&/msie 7./i.test(t.navigator.appVersion)&&t.ActiveXObject,i=null,x=function(){var e,t,r;return e=n.createElement("div"),t=e.style,t.position="absolute",t.width="100px",t.height="100px",t.overflow=g,t.top="-9999px",n.body.appendChild(e),r=e.offsetWidth-e.clientWidth,n.body.removeChild(e),r},d=function(){function a(r,s){this.el=r,this.options=s,i||(i=x()),this.$el=e(this.el),this.doc=e(this.options.documentContext||n),this.win=e(this.options.windowContext||t),this.$content=this.$el.children("."+s.contentClass),this.$content.attr("tabindex",0),this.content=this.$content[0],this.options.iOSNativeScrolling&&this.el.style.WebkitOverflowScrolling!=null?this.nativeScrolling():this.generate(),this.createEvents(),this.addEvents(),this.reset()}return a.prototype.preventScrolling=function(e,t){if(!this.isActive)return;if(e.type===s)(t===o&&e.originalEvent.detail>0||t===w&&e.originalEvent.detail<0)&&e.preventDefault();else if(e.type===p){if(!e.originalEvent||!e.originalEvent.wheelDelta)return;(t===o&&e.originalEvent.wheelDelta<0||t===w&&e.originalEvent.wheelDelta>0)&&e.preventDefault()}},a.prototype.nativeScrolling=function(){this.$content.css({WebkitOverflowScrolling:"touch"}),this.iOSNativeScrolling=!0,this.isActive=!0},a.prototype.updateScrollValues=function(){var e;e=this.content,this.maxScrollTop=e.scrollHeight-e.clientHeight,this.contentScrollTop=e.scrollTop,this.iOSNativeScrolling||(this.maxSliderTop=this.paneHeight-this.sliderHeight,this.sliderTop=this.contentScrollTop*this.maxSliderTop/this.maxScrollTop)},a.prototype.createEvents=function(){var e=this;this.events={down:function(t){return e.isBeingDragged=!0,e.offsetY=t.pageY-e.slider.offset().top,e.pane.addClass("active"),e.doc.bind(c,e.events[u]).bind(h,e.events[w]),!1},drag:function(t){return e.sliderY=t.pageY-e.$el.offset().top-e.offsetY,e.scroll(),e.updateScrollValues(),e.contentScrollTop>=e.maxScrollTop?e.$el.trigger("scrollend"):e.contentScrollTop===0&&e.$el.trigger("scrolltop"),!1},up:function(t){return e.isBeingDragged=!1,e.pane.removeClass("active"),e.doc.unbind(c,e.events[u]).unbind(h,e.events[w]),!1},resize:function(t){e.reset()},panedown:function(t){return e.sliderY=(t.offsetY||t.originalEvent.layerY)-e.sliderHeight*.5,e.scroll(),e.events.down(t),!1},scroll:function(t){if(e.isBeingDragged)return;e.updateScrollValues(),e.iOSNativeScrolling||(e.sliderY=e.sliderTop,e.slider.css({top:e.sliderTop}));if(t==null)return;e.contentScrollTop>=e.maxScrollTop?(e.options.preventPageScrolling&&e.preventScrolling(t,o),e.$el.trigger("scrollend")):e.contentScrollTop===0&&(e.options.preventPageScrolling&&e.preventScrolling(t,w),e.$el.trigger("scrolltop"))},wheel:function(t){var n;if(t==null)return;return n=t.delta||t.wheelDelta||t.originalEvent&&t.originalEvent.wheelDelta||-t.detail||t.originalEvent&&-t.originalEvent.detail,n&&(e.sliderY+=-n/3),e.scroll(),!1}}},a.prototype.addEvents=function(){var e;this.removeEvents(),e=this.events,this.options.disableResize||this.win.bind(m,e[m]),this.iOSNativeScrolling||(this.slider.bind(l,e[o]),this.pane.bind(l,e[v]).bind(""+p+" "+s,e[E])),this.$content.bind(""+g+" "+p+" "+s+" "+b,e[g])},a.prototype.removeEvents=function(){var e;e=this.events,this.win.unbind(m,e[m]),this.iOSNativeScrolling||(this.slider.unbind(),this.pane.unbind()),this.$content.unbind(""+g+" "+p+" "+s+" "+b,e[g])},a.prototype.generate=function(){var e,t,n,r,s;return n=this.options,r=n.paneClass,s=n.sliderClass,e=n.contentClass,!this.$el.find(""+r).length&&!this.$el.find(""+s).length&&this.$el.append('<div class="'+r+'"><div class="'+s+'" /></div>'),this.pane=this.$el.children("."+r),this.slider=this.pane.find("."+s),i&&(t={right:-i},this.$el.addClass("has-scrollbar")),t!=null&&this.$content.css(t),this},a.prototype.restore=function(){this.stopped=!1,this.pane.show(),this.addEvents()},a.prototype.reset=function(){var e,t,n,s,o,u,a,f,l;if(this.iOSNativeScrolling){this.contentHeight=this.content.scrollHeight;return}return this.$el.find("."+this.options.paneClass).length||this.generate().stop(),this.stopped&&this.restore(),e=this.content,n=e.style,s=n.overflowY,r&&this.$content.css({height:this.$content.height()}),t=e.scrollHeight+i,u=this.pane.outerHeight(),f=parseInt(this.pane.css("top"),10),o=parseInt(this.pane.css("bottom"),10),a=u+f+o,l=Math.round(a/t*a),l<this.options.sliderMinHeight?l=this.options.sliderMinHeight:this.options.sliderMaxHeight!=null&&l>this.options.sliderMaxHeight&&(l=this.options.sliderMaxHeight),s===g&&n.overflowX!==g&&(l+=i),this.maxSliderTop=a-l,this.contentHeight=t,this.paneHeight=u,this.paneOuterHeight=a,this.sliderHeight=l,this.slider.height(l),this.events.scroll(),this.pane.show(),this.isActive=!0,e.scrollHeight===e.clientHeight||this.pane.outerHeight(!0)>=e.scrollHeight&&s!==g?(this.pane.hide(),this.isActive=!1):this.el.clientHeight===e.scrollHeight&&s===g?this.slider.hide():this.slider.show(),this.pane.css({opacity:this.options.alwaysVisible?1:"",visibility:this.options.alwaysVisible?"visible":""}),this},a.prototype.scroll=function(){if(!this.isActive)return;return this.sliderY=Math.max(0,this.sliderY),this.sliderY=Math.min(this.maxSliderTop,this.sliderY),this.$content.scrollTop((this.paneHeight-this.contentHeight+i)*this.sliderY/this.maxSliderTop*-1),this.iOSNativeScrolling||this.slider.css({top:this.sliderY}),this},a.prototype.scrollBottom=function(e){if(!this.isActive)return;return this.reset(),this.$content.scrollTop(this.contentHeight-this.$content.height()-e).trigger(p),this},a.prototype.scrollTop=function(e){if(!this.isActive)return;return this.reset(),this.$content.scrollTop(+e).trigger(p),this},a.prototype.scrollTo=function(t){if(!this.isActive)return;return this.reset(),this.scrollTop(e(t).get(0).offsetTop),this},a.prototype.stop=function(){return this.stopped=!0,this.removeEvents(),this.pane.hide(),this},a.prototype.destroy=function(){return this.stopped||this.stop(),this.pane.length&&this.pane.remove(),r&&this.$content.height(""),this.$content.removeAttr("tabindex"),this.$el.hasClass("has-scrollbar")&&(this.$el.removeClass("has-scrollbar"),this.$content.css({right:""})),this},a.prototype.flash=function(){var e=this;if(!this.isActive)return;return this.reset(),this.pane.addClass("flashed"),setTimeout(function(){e.pane.removeClass("flashed")},this.options.flashDelay),this},a}(),e.fn.nanoScroller=function(t){return this.each(function(){var n,r;(r=this.nanoscroller)||(n=e.extend({},S,t),this.nanoscroller=r=new d(this,n));if(t&&typeof t=="object"){e.extend(r.options,t);if(t.scrollBottom)return r.scrollBottom(t.scrollBottom);if(t.scrollTop)return r.scrollTop(t.scrollTop);if(t.scrollTo)return r.scrollTo(t.scrollTo);if(t.scroll==="bottom")return r.scrollBottom(0);if(t.scroll==="top")return r.scrollTop(0);if(t.scroll&&t.scroll instanceof e)return r.scrollTo(t.scroll);if(t.stop)return r.stop();if(t.destroy)return r.destroy();if(t.flash)return r.flash()}return r.reset()})}})(jQuery,window,document);

$(".nano").nanoScroller();
$(".nanod").nanoScroller();



function cambiaalto(){
    
    $(".nanoizq").height( ($( window ).height()-60) );
    $(".nanoder").height( ($( window ).height()-90-83-10) );
    $("#div-cargando").height( ($( window ).height()-90-83-10) );
    $( "#cargandocorreos" ).css( "padding-top", ((($( window ).height()-60-83)-15)/2)+"px" );
    
    $(".nano").nanoScroller();
    //$(".nanod").nanoScroller();
    
}
$( window ).resize(function() {
    cambiaalto();
});
cambiaalto();


/*
$(document).ready(function() {
	var win = $(".nanod ");

	// Each time the user scrolls
	win.scroll(function() {  console.log("scrolea "+($(document).height() )+" "+$(".nanod ").height() +" "+win.scrollTop());
		// End of the document reached?
		if ($(document).height() - $(".nanod ").height() == win.scrollTop()) {
			//$('#loading').show();
                        console.log("entro");
			$.ajax({
				url: 'get-post.php',
				dataType: 'html',
				success: function(html) {
					$('#posts').append(html);
					$('#loading').hide();
				}
			});
		}
	});
});*/



// using some custom options


 $(function() {

      $('.nanod').scrollTop(101);
      $('.nanod').endlessScroll({
        //pagesToKeep: 5,
        //inflowPixels: 100,
        fireDelay: 1000,
        scrollDirection: 'next',
        content: function(i, p, d) {
           
          cargardatos();
          return ;
        }
      });
      
      $("#bandejanada").on('click',function(event){
          event.preventDefault();
          event.stopPropagation();
      });
      
      
      $(".contienecorreoe").scroll(function() { // console.log("=>"+$(".contienecorreoe").scrollLeft());
                   $(".contienecorreoeant").scrollLeft($(".contienecorreoe").scrollLeft());
      });
    
    });

function agregaregistro(datos){
    var noleido="noleido";
    
    
    //console.log("aja |"+<?= $tipod ?>+"|"+datos['lectura_grupo_uno']+"|"+datos['lectura_grupo_dos']+"|");
    if(<?= $tipod ?>==0 && datos['lectura_grupo_uno']==1){
        noleido="";
    }else if(<?= $tipod ?>==1 && datos['lectura_grupo_dos']==1){
        noleido=""; 
    }      
    
    
    <?php if($tipod==0){ ?>
    var despachos="";
     /*var br="";
    //for(var i =0;i<datos['despachos'].length;i++){
       for (var i in datos['despachos']) {
            despachos += br+'<a onclick="leido(this)" href="{{config('app.Campro')[2]}}/campro/inventarios/rds/despachos.php?id_documento='+datos['despachos'][i]+'" target="_blank" > '+datos['despachos'][i]+'</a>';
            br="<br>";
        }*/
        
        if(datos['despachos']!=null && datos['despachos']!='null'){
            despachos=datos['despachos'].replace("&lt;", "<").replace(/&lt;/g, "<").replace(/&gt;/g,">").replace("lt;br>","");
        }
    <?php } ?>
    //console.log("despachos # =>"+datos['despachos']);
    //console.log("despa |"+despachos+"|");
    /*
    var html ='<li class="unread">';
        html+='  <a href="mailbox-email.html">';
        html+='		<div class="col-sender">';
        html+='		<div class="checkbox-custom checkbox-text-primary ib">';
        html+='			<input type="checkbox" id="mail1">';
        html+='			<label for="mail1"></label>';
        html+='		</div>';
        html+='		<p class="m-0 ib">Okler Themes</p>';
        html+='	</div>';
        html+='	<div class="col-mail">';
        html+='		<p class="m-0 mail-content">';
        html+='				<span class="subject">Check out our new Porto Admin theme! &nbsp;–&nbsp;</span>';
        html+='					<span class="mail-partial">We are proud to announce that our new theme Porto Admin is ready, wants to know more about it?</span>';
        html+='             </p>';
        html+='		<p class="m-0 mail-date">11:35 am</p>';
        html+='	</div>';
        html+='   </a>';
        html+='</li>'; */
       // console.log(datos);
    var html ='<tr class="'+noleido+'">';                                        
        html+='    <td style="width: 121px;text-align: center;">';
        html+='      <a onclick="leido(this)" href="<?= Request::root() ?>/redes/leer/'+datos['id_proyecto']+'/'+datos['id_orden']+'/<?= $tipod ?>" target="_blank" > '+datos['id_orden']+'</a>';
        html+='    </td>';    
        <?php if($tipod==0){ ?>
            html+='    <td style="width: 121px;text-align: center;"  >';
            html+='       '+despachos;
            html+='    </td>';    
        <?php } ?>
        html+='    <td style="width: 200px;text-align:left;">';
        html+='        '+datos['tipoproyecto'];
        html+='    </td>';                                   
        html+='    <td style=" width: 242px;  overflow-x: hidden;    text-align: left;">';
        html+='        '+datos['proyecto'];
        html+='    </td>';                                   
        html+='    <td style="width: 115px;text-align: center;">';
        html+='        '+datos['fecha_ejecucion'];
        html+='    </td>';                                  
        html+='    <td style="width: 115px;text-align: center;">';
        html+='        '+datos['fecha_ejecucion_final'];
        html+='    </td>';                                   
        html+='    <td style="width: 100px; text-align: center;">';
        html+='        '+datos['hora_cierre'].replace(".0000000", "");
        html+='    </td>';                                
        html+='    <td style="width: 100px; text-align: center;">';
        html+='        '+datos['hora_corte'].replace(".0000000", "");
        html+='    </td>';                                
        html+='    <td style="width: 400px;text-align: left;" >';
        html+='        '+datos['observaciones'];
        html+='    </td>';                              
        html+='    <td style="width: 150px; text-align: center;">';
        html+='        '+datos['fecha'];
        html+='    </td>';
        html+='</tr>';
        $("#contenli").append(html);
}

var pagina=1;
function cambia(){
    pagina=1;
}
function modbandeja(elemtn,event,dato){
    event.preventDefault();
    event.stopPropagation();
    $(elemtn).parent().parent().find('a.active').removeClass('active');
    $(elemtn).addClass('active');
    actualizar();    
}
function actualizar(){
    pagina=1;
    cargardatos();
}
function cargardatos(){
    var tipo='<?= $tipod ?>';
    var bandeja=$("#lista_tipo").find('a.active').data('dato');
    var tipoproyecto=$("#tipoproyecto").val();
    var proyecto=$("#proyecto").val();
    var fecha1="";
    if($("#fecha1").val().trim().length > 0 ){
        var f = document.querySelector("#fecha1").value.split("/");
        fecha1 = f[2] + "-" + f[1] + "-" + f[0];
    }
    var fecha2="";
    if($("#fecha2").val().trim().length > 0 ){
        var f = document.querySelector("#fecha2").value.split("/");
        fecha2 = f[2] + "-" + f[1] + "-" + f[0];
    }
     
    
    $("#div-cargando").show('slow');
    $(".loading").show('slow');
     $.ajax({
            type: 'POST',
            url: "<?= Request::root() ?>/redes/gestorlista",
            dataType: "json",
            data: {                
                _token:'<?= csrf_token() ?>',
                tipo:tipo,////0 materiales //1 descargos 
                bandeja:bandeja,//0 inbox (todo en orden de fecha) 1 no leidos 2 leidos 
                tipoproyecto:tipoproyecto,//0 sin especificar
                proyecto:proyecto,//'' sin especificar
                fecha1:fecha1,//'' sin especificar
                fecha2:fecha2,//'' sin especificar 
                pagina:pagina
            },
            dataType: "json",
            success: function(data,textStatus) {  
                if(data.length > 0){
                    if(pagina==1){
                        $("#contenli").empty();
                    }                  
                    
                    for(var i=0;i<data.length;i++){
                        agregaregistro(data[i]);
                    }
                    pagina++;
                   // mensajes("Exito","Proceso finalizado satisfactoriamente.\n",1);                                 
                }else{
                    if(pagina==1){
                        $("#contenli").empty();
                    }                                   
                }                                
            }, 
            error: function(data) {
                    mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);  
            }
     }).always(function() {         
            $("#div-cargando").hide('slow');
            $(".loading").hide('slow');
     });  

              
}

actualizar();


function ocultarmenu(){

   if( $("#menuizquierda").is(":visible") ){
      $("#menuizquierda").hide('slow'); 
      $("#imboxderecha").css("width", "100%");
   }else{       
      $("#menuizquierda").show('slow'); 
      $("#imboxderecha").css("width", "calc(100% - 300px)");
   }
}
function leido(elemento){
    
    $(elemento).parent().parent().removeClass('noleido');
    
}
</script>	
@stop