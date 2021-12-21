<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>IRO</title>
      <link rel="stylesheet" type="text/css" href="css/pdfs/iro.css">
 
  </head>
  <body>
    <main>
      <div id="header">
        <table style="border-color:black;height:20px;" border="1" cellpadding="0" cellspacing="0">
        <tr> 
        <td style="width:20mm">
          <img src="img/enel.png" style="position: absolute;width:13mm;top:0mm;margin-left:3mm">
        </td>
        <td style="width:550px;background:#ccc"><p style="width:100%;text-align:center;font-size:11px;font-weight:bold;padding:0px;margin:0px;position:relative;top:1px;">IDENTIFICACIÓN RIESGOS OPERACIONALES <br> TRABAJOS REITERATIVOS - IRO</p></td> 
        <td style="background:#ccc"> <p style="text-align: center;padding:0px;margin:0px;">RG02-IN296</p> <p style="text-align: center;padding:0px;margin:0px;">Version1</p> <p style="text-align: center;padding:0px;margin:0px;">08/04/2011</p></td>
        </tr>
        </table>        
      </div>
      
      <div id="ejecuciondetrabajos">
          @include('pdf.seccionesIro.ejecucionTrabajos') 
      </div>
      
      <div id="identificaciondelriesgo">
         @include('pdf.seccionesIro.identificacionRiesgos')
      </div>

      <div id="tiporiesgo" style="margin-top:25px;">
         @include('pdf.seccionesIro.tipoRiesgo')
      </div>

      <div style="page-break-before:always;"></div>

      <div id="header" style="position:relative;top:25px;">
        <table style="border-color:black;height:20px;" border="1" cellpadding="0" cellspacing="0">
        <tr> 
        <td style="width:20mm">
          <img src="img/enel.png" style="position: absolute;width:13mm;top:0mm;margin-left:3mm">
        </td>
        <td style="width:550px;background:#ccc"><p style="width:100%;text-align:center;font-size:11px;font-weight:bold;padding:0px;margin:0px;position:relative;top:1px;">IDENTIFICACIÓN RIESGOS OPERACIONALES <br> TRABAJOS REITERATIVOS - IRO</p></td> 
        <td style="background:#ccc"> <p style="text-align: center;padding:0px;margin:0px;">RG02-IN296</p> <p style="text-align: center;padding:0px;margin:0px;">Version1</p> <p style="text-align: center;padding:0px;margin:0px;">08/04/2011</p></td>
        </tr>
        </table>        
      </div>

      <div id="tiporiesgo2" style="position:relative;top:-10px;">
          @include('pdf.seccionesIro.tipoRiesgo2')
      </div>
     
     </main>
  </body>
</html>

