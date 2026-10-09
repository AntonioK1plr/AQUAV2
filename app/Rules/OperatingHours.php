<?php
namespace App\Rules;
use Closure; use Illuminate\Contracts\Validation\ValidationRule; use Carbon\Carbon;
class OperatingHours implements ValidationRule { public function validate(string $attribute,mixed $value,Closure $fail):void { try{$d=Carbon::parse($value);$open=$d->isWeekend()?'10:00':'09:00';$close=$d->isWeekend()?'17:00':'23:00';if($d->format('H:i')<$open||$d->format('H:i')>=$close)$fail('La hora debe estar dentro del horario de atención (L-V 09:00-23:00, S-D 10:00-17:00).');if($d->lessThanOrEqualTo(now()))$fail('Selecciona una hora futura.');}catch(\Throwable){$fail('La fecha y hora no son válidas.');} } }
