<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "iniCampro",
        "consultaProyectos",
        "saveSupervision",
        "descargaExcelKitsMaterial",
        "consultaSaveInformacionValidadorMovil",
        "consultarmoviltransporte",
        "fotografiasUpload",
        "savemoviltransporte",
        "fotografiasUploadTransporte",
        "consultaWebServiceMovil",
        "envioNotificacionesSSL",
        "insertaFotosMovil",
        "consultaVersionApp",
        "cargaFotograficoMovil",
        "biometricUser",
        "biometricConsultaUser",
        "biometricSaveUser",
        "transporte/ws/appTecnico",
        'transversal/ags/saveWSMovil',
        'envioNotificacionesSSLExterno',
        "transversal/supervision/wsMovilConsultaPlanSuper",
        "transversal/supervision/wsMovilSavePlanSuper",
        "transversal/supervision/getOrdenesPlan",
        "transversal/supervision/getInfoOrdenIns",
        "validaConexion"
    ];
}
