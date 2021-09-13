<?php
namespace App\Libs;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use \FtpClient\FtpClient;

class FtpHandler
{
  private static $_adapter = [
    'hostname' => 'getnaprog.com',
    'username' => 'db@getnaprog.com',
    'password' => '@Fluke160941',
    'mode' => 'passive', //passive or active
    'ssl' => false,
    'port' => 21,
    'timeout' => 30
  ];

  private static function _connect()
  {
    $ftp = new FtpClient();
    $ftp->connect(self::$_adapter['hostname'], self::$_adapter['ssl'], self::$_adapter['port'], self::$_adapter['timeout']);
    $ftp->login(self::$_adapter['username'], self::$_adapter['password']);
    if (self::$_adapter['mode'] === 'passive')
      $ftp->pasv(true);
    else if (self::$_adapter['mode'] === 'active')
      $ftp->pasv(false);
    return $ftp;
  }

  public static function help(): array
  {
    return self::_connect()->help();
  }

  public static function list(string $path = '')
  {
    return self::_connect()->scanDir($path);
  }

  public static function size(string $path = '')
  {
    return self::_connect()->dirSize($path). ' byte';
  }

  public static function upload_file(string $source, string $target, int $mode = FTP_BINARY)
  {
    if (self::_connect()->putAll($source, $target, $mode))
      return true;
    else
      return false;  
  }
  
}