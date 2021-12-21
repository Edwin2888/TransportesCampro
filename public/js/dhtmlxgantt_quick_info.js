/*
@license

dhtmlxGantt v.4.1.0 Stardard
This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
gantt.config.quickinfo_buttons = ["icon_delete", "icon_edit"], gantt.config.quick_info_detached = !0, gantt.config.show_quick_info = !0, gantt.attachEvent("onTaskClick", function(t) {
        return gantt.showQuickInfo(t), !0
    }),
    function() {
        for (var t = ["onEmptyClick", "onViewChange", "onLightbox", "onBeforeTaskDelete", "onBeforeDrag"], e = function() {
                return gantt._hideQuickInfo(), !0
            }, n = 0; n < t.length; n++) gantt.attachEvent(t[n], e)
    }(), gantt.templates.quick_info_title = function(t, e, n) {
        return n.text.substr(0, 50)
    }, gantt.templates.quick_info_content = function(t, e, n) {
        return n.details || n.text
    }, gantt.templates.quick_info_date = function(t, e, n) {
        return gantt.templates.task_time(t, e, n)
    }, gantt.templates.quick_info_class = function(t, e, n) {
        return ""
    }, gantt.showQuickInfo = function(t) {
        if (t != this._quick_info_box_id && this.config.show_quick_info) {
            this.hideQuickInfo(!0);
            var e = this._get_event_counter_part(t);
            e && (this._quick_info_box = this._init_quick_info(e, t), this._quick_info_task = t, this._quick_info_box.className = gantt._prepare_quick_info_classname(t), this._fill_quick_data(t), this._show_quick_info(e),
                this.callEvent("onQuickInfo", [t]))
        }
    }, gantt._hideQuickInfo = function() {
        gantt.hideQuickInfo()
    }, gantt.hideQuickInfo = function(t) {
        var e = this._quick_info_box;
        this._quick_info_box_id = 0;
        var n = this._quick_info_task;
        if (this._quick_info_task = null, e && e.parentNode) {
            if (gantt.config.quick_info_detached) return this.callEvent("onAfterQuickInfo", [n]), e.parentNode.removeChild(e);
            e.className += " gantt_qi_hidden", "auto" == e.style.right ? e.style.left = "-350px" : e.style.right = "-350px", t && e.parentNode.removeChild(e), this.callEvent("onAfterQuickInfo", [n]);
        }
    }, gantt.event(window, "keydown", function(t) {
        27 == t.keyCode && gantt.hideQuickInfo()
    }), gantt._show_quick_info = function(t) {
        var e = gantt._quick_info_box;
        if (gantt.config.quick_info_detached) {
            e.parentNode && "#document-fragment" != e.parentNode.nodeName.toLowerCase() || gantt.$task_data.appendChild(e);
            var n = e.offsetWidth,
                a = e.offsetHeight,
                i = this.getScrollState(),
                s = this.$task.offsetWidth + i.x - n;
            e.style.left = Math.min(Math.max(i.x, t.left - t.dx * (n - t.width)), s) + "px", e.style.top = t.top - (t.dy ? a : -t.height) - 25 + "px"
        } else e.style.top = "20px",
            1 == t.dx ? (e.style.right = "auto", e.style.left = "-300px", setTimeout(function() {
                e.style.left = "-10px"
            }, 1)) : (e.style.left = "auto", e.style.right = "-300px", setTimeout(function() {
                e.style.right = "-10px"
            }, 1)), e.className += " gantt_qi_" + (1 == t.dx ? "left" : "right"), gantt._obj.appendChild(e)
    }, gantt._prepare_quick_info_classname = function(t) {
        var e = gantt.getTask(t),
            n = "gantt_cal_quick_info",
            a = this.templates.quick_info_class(e.start_date, e.end_date, e);
        return a && (n += " " + a), n
    }, gantt._init_quick_info = function(t, e) {
        var n = gantt.getTask(e);
        //alert(JSON.stringify(n));
        if ("boolean" == typeof this._quick_info_readonly && this._is_readonly(n) !== this._quick_info_readonly && (gantt.hideQuickInfo(!0), this._quick_info_box = null), this._quick_info_readonly = this._is_readonly(n), !this._quick_info_box) {
            var a = this._quick_info_box = document.createElement("div");
            this._waiAria.quickInfoAttr(a);
            var i = gantt._waiAria.quickInfoHeaderAttrString(),
                s = '<div class="gantt_cal_qi_title" ' + i + '><div class="gantt_cal_qi_tcontent"></div><div  class="gantt_cal_qi_tdate"></div></div><div class="gantt_cal_qi_content"></div>';
            s += '<div class="gantt_cal_qi_controls">';
            for (var r = gantt.config.quickinfo_buttons, o = {
                    icon_delete: !0,
                    icon_edit: !0
                }, l = 0; l < r.length; l++)
                if (!this._quick_info_readonly || !o[r[l]]) {
                    var i = gantt._waiAria.quickInfoButtonAttrString(gantt.locale.labels[r[l]]);
                    s += '<div class="gantt_qi_big_icon ' + r[l] + '" title="' + gantt.locale.labels[r[l]] + '" ' + i + "><div class='gantt_menu_icon " + r[l] + "'></div><div>" + gantt.locale.labels[r[l]] + "</div></div>"
                }
            s += "</div>", a.innerHTML = s;
            var _ = function(t) {
                t = t || event, gantt._qi_button_click(t.target || t.srcElement);
            };
            gantt.event(a, "click", _), gantt.event(a, "keypress", function(t) {
                t = t || event;
                var e = t.which || event.keyCode;
                (13 == e || 32 == e) && setTimeout(function() {
                    gantt._qi_button_click(t.target || t.srcElement)
                }, 1)
            }), gantt.config.quick_info_detached && gantt.event(gantt.$task_data, "scroll", function() {
                gantt.hideQuickInfo()
            })
        }
        return this._quick_info_box
    }, gantt._qi_button_click = function(t) {
        var e = gantt._quick_info_box;
        if (t && t != e) {
            var n = t.className;
            if (-1 != n.indexOf("_icon")) {
                var a = gantt._quick_info_box_id;
                gantt.$click.buttons[n.split(" ")[1].replace("icon_", "")](a);
            } else gantt._qi_button_click(t.parentNode)
        }
    }, gantt._get_event_counter_part = function(t) {

        for (var e = gantt.getTaskNode(t), n = 0, a = 0, i = e; i && "gantt_task" != i.className;) n += i.offsetLeft, a += i.offsetTop, i = i.offsetParent;
        var s = this.getScrollState();
        if (i) {
            var r = n + e.offsetWidth / 2 - s.x > gantt._x / 2 ? 1 : 0,
                o = a + e.offsetHeight / 2 - s.y > gantt._y / 2 ? 1 : 0;
            return {
                left: n,
                top: a,
                dx: r,
                dy: o,
                width: e.offsetWidth,
                height: e.offsetHeight
            }
        }
        return 0
    }, gantt._fill_quick_data = function(t) {
        var e = gantt.getTask(t),
            n = gantt._quick_info_box;

        

        gantt._quick_info_box_id = t;
        var a;
        if(e.opc == 4 || e.opc == 5)
        {
            if(e.opc == 4)
            {
                a = { 
                    content: "<b>MANIOBRA:</b> " + '<span onclick="verOT(this)" data-ot="' + e.text +'" style="color:blue;cursor:pointer;">' + e.text + '</span><br>' ,
                    date: gantt.templates.quick_info_date(e.start_date, e.end_date, e)
                };
            }
            else
            {
                a = {
                    content: "<b>PROYECTO:</b> " + gantt.templates.quick_info_title(e.start_date, e.end_date, e),
                    date: gantt.templates.quick_info_date(e.start_date, e.end_date, e)
                };
            }
        }
        else
        {
             if(e.opc == 3)
             {
                a = { 
                    content: "<b>RESTRICCIÓN:</b> " +  gantt.templates.quick_info_title(e.start_date, e.end_date, e),
                    date: gantt.templates.quick_info_date(e.start_date, e.end_date, e)
                };
             }
             else
             {
                if(e.dato1 != undefined)
                {
                    a = {
                        content: "<b>PROYECTO:</b> " + gantt.templates.quick_info_title(e.start_date, e.end_date, e),
                        date: gantt.templates.quick_info_date(e.start_date, e.end_date, e)
                    };
                }
                else
                {
                    a = {
                        content: "<b>MANIOBRA:</b> " + '<span onclick="verOT(this)" data-ot="' + e.text +'" style="color:blue;cursor:pointer;">' + e.text + '</span><br>' ,
                        date: gantt.templates.quick_info_date(e.start_date, e.end_date, e)
                    };
                }    
             }
            
        }
        
        
        i = n.firstChild.firstChild;
        i.innerHTML = a.content;
        var s = i.nextSibling;
        s.innerHTML = a.date, gantt._waiAria.quickInfoHeader(n, [a.content, a.date].join(" "));
        var r = n.firstChild.nextSibling;

        if(e.opc == 4 || e.opc == 5)
        {
            var html = "";
            html += "Total Restricciones: " + (parseInt(e.valor1) + parseInt(e.valor2) + parseInt(e.valor3)) + "<br>";
            html += "Restricciones abiertas: " + e.valor1 + "<br>";
            html += "Restricciones ignoradas: " + e.valor2 + "<br>";
            html += "Restricciones cerradas: " + e.valor3 + "<br>";
          
            r.innerHTML = html;
        }
        else
        {
            if(e.opc == 3)
            { 
                var html = "";
                if(e.id_orden != "0")
                {
                    html += "OT: " + '<span onclick="verOT(this)" data-ot="' + e.id_orden +'" style="color:blue;cursor:pointer;">' + e.id_orden + '</span><br>';
                }

                html += "Impacto: " + e.impacto + "<br>";
                html += "Responsable: " + e.respon + "<br>";

                var tipoE = "POR INICIAR";
                if(e.priority == 2)
                    tipoE = "ANULADA";

                if(e.priority == 3)
                    tipoE = "LEVANTADA";

                if(e.priority == 4)
                    tipoE = "EN PROCESO";

                if(e.priority == 3)
                    tipoE = "EN PROCESO < 7 DÍAS";

                
                if(e.priority == 1)
                    html += "Estado: <span class='abierta' style='color:white;padding:3px;background:red'>POR INICIAR</span><br>";

                if(e.priority == 2)
                    html += "Estado: <span class='anulada' style='color:white;padding:3px;;background:black'>ANULADA</span><br>";

                if(e.priority == 3)
                    html += "Estado: <span class='cerrada' style='color:white;padding:3px;;background:rgb(0,143,65)'>LEVANTADA</span><br>";

                if(e.priority == 4)
                    html += "Estado: <span class='cerrada' style='color:white;padding:3px;;background:blue'>EN PROCESO</span><br>";

                if(e.priority == 5)
                    html += "Estado: <span class='cerrada' style='color:black;padding:3px;;background:yellow'>EN PROCESO < 7 DÍAS</span><br>";

                if(e.priority == 3) //Cierre
                {
                    html += "Fecha cierre: " + e.fechaC.split(" ")[0] + "<br>"; 
                    html += "Evidencia cierre: <a target='blank_' href='http://201.217.195.43" + e.evidencia + "'>Ver</a><br>";   
                } 

                if(e.esta == "A" || e.esta == "P")
                {
                    var proyecto = e.id_proyecto;
                    var orden = e.id_orden;
                    var res = e.r_s;
                    html += '<a target="_blank" href="../../cerrarresticciones/' + proyecto + '/' + orden + '/' + res + '" class="btn_cerrar">Cerrar restricción</a>';
                }

                r.innerHTML = html;
            }
            else
            {
                if(e.dato1 != undefined)
                {
                    r.innerHTML = (e.dato1 != undefined ? "Total maniobras:" + (parseInt(e.dato3) + parseInt(e.dato2)) + "<br>" : "") + (e.dato3 != undefined ? "Total maniobras programadas:" + e.dato3 + "<br>" : "") + (e.dato2 != undefined ? "Total maniobras ejecutadas:" + e.dato2 + "<br>" : "");    
                }else
                {
                    var html = "";
                    html += "Proyecto: " + e.pry + "<br>";
                    html += "WBS: " + e.wbs + "<br>";
                    html += "NODOS: " + e.nodos + "<br>";
                    html += "GOM: " + e.gom + "<br>";
                    html += "DESCARGO: " + (e.descargo == 0 ? '' : e.descargo) + "<br>";
                    html += "Hora inicio maniobra: " + e.horaini + "<br>";
                    html += "Hora fin maniobra: " + e.horafin + "<br>";
                    html += "Valor ejecución: $" + (e.valor == "" ? "0" : e.valor.replace(".0000","")) + "<br>";

                    r.innerHTML = html;
                } 
            }
        }
        
        

        //gantt.templates.quick_info_content(e.start_date, e.end_date, e)
        //alert(JSON.stringify(e));
        //alert(JSON.stringify(n));
    };
//# sourceMappingURL=../sources/ext/dhtmlxgantt_quick_info.js.map