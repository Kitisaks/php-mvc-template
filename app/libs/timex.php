<?php
namespace App\Libs;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Carbon\Carbon;

class Timex
{
  private static function _locale()
  {
    switch (MODE) {
      case 'DEV':
        $ip = '182.232.197.124';
        break;
      
      case 'DEV':
        $ip = Utils::get_client_ip();
        break;
    }
    return Utils::get_location_from_ip($ip)->countryCode;
  }

  public static function now()
  {
    return Carbon::now()->locale(self::_locale());
  }

  public static function iso_format($datetime)
  {
    return 
      Carbon::parse($datetime)
      ->locale(self::_locale())
      ->isoFormat('LLL');
  }

  public static function from_now($datetime)
  { 
    return 
      Carbon::parse($datetime)->diffForHumans(self::now());
  }

  public static function add($datetime, $num, $type)
  {
    return $datetime->add($num, $type);
  }
}