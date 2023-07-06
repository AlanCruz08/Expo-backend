<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use App\Enums\JSendStatus;

trait RespuestaAPI
{
	/**
	 * Representa la base de una respuesta en formato JSend
	 *
	 * @param JSendStatus  $estatus_jsend
	 * @param int          $estatus_http
	 * @param array        $datos
	 * @param string       $mensaje
	 */
	private function respuesta_base($estatus_jsend, $estatus_http, $datos = null, $mensaje = null)
	{
		$respuesta = ['status' => $estatus_jsend];

		if ($estatus_jsend == JSendStatus::Error)
			$respuesta['message'] = $mensaje;
		else
			$respuesta['data'] = $datos;

		return response()->json($respuesta, $estatus_http);
	}

	/**
	 * Respuesta de exito de la API
	 *
	 * @param array $datos
	 * @param int   $estatus_http
	 */
	public function exito($datos = null, $estatus_http = Response::HTTP_OK)
	{ return $this->respuesta_base(JSendStatus::Exito, $estatus_http, $datos); }

	/**
	 * Respuesta de error de la API
	 *
	 * @param string $mensaje
	 * @param int    $estatus_http
	 */
	public function error($mensaje = 'Error', $estatus_http = Response::HTTP_INTERNAL_SERVER_ERROR)
	{ return $this->respuesta_base(JSendStatus::Error, $estatus_http, null, $mensaje); }

	/**
	 * Respuesta de falla de la API
	 *
	 * @param array $datos
	 * @param int   $estatus_http
	 */
	public function falla($datos = null, $estatus_http = Response::HTTP_BAD_REQUEST)
	{ return $this->respuesta_base(JSendStatus::Falla, $estatus_http, $datos); }
}