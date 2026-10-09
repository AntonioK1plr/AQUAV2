<?php
namespace App\Services;
use chillerlan\QRCode\QRCode; use chillerlan\QRCode\QROptions;
class PaseQrService { public function svg(string $token):string {$options=new QROptions(['outputType'=>QRCode::OUTPUT_MARKUP_SVG,'scale'=>6,'imageBase64'=>false]);return (new QRCode($options))->render($token);} }
