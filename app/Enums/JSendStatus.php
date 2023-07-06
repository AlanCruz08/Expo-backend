<?php

namespace App\Enums;

enum JSendStatus: string
{
	case Exito = 'success';
	case Error = 'error';
	case Falla = 'fail';
}