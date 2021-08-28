<?php
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToCopyFile;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use League\Flysystem\StorageAttributes;

class FileHandler
{
  private static function adapter()
  {
    $adapter = new LocalFilesystemAdapter(
      $_SERVER["DOCUMENT_ROOT"] . "/",
      // Customize how visibility is converted to unix permissions
      PortableVisibilityConverter::fromArray([
        'file' => [
          'public' => 0644,
          'private' => 0604,
        ],
        'dir' => [
          'public' => 0755,
          'private' => 7604,
        ]
      ]),
      // Write flags
      LOCK_EX,
      // How to deal with links, either DISALLOW_LINKS or SKIP_LINKS
      // Disallowing them causes exceptions when encountered
      LocalFilesystemAdapter::DISALLOW_LINKS
    );

    return new Filesystem($adapter, ["visibility" => "public"]);
  }

  public static function write_file($path, $contents, $config = ["visibility" => "public"])
  {
    try {
      self::adapter()->write($path, $contents, $config);
      return true;
    } catch (FilesystemException | UnableToWriteFile $exception) {
      exit("Unable to write file: " . $exception);
    }
  }

  public static function read_file($path)
  {
    try {
      $response = self::adapter()->read($path);
      return $response;
    } catch (FilesystemException | UnableToReadFile $exception) {
      exit("Unable to read file: " . $exception);
    }
  }

  public static function delete_file($path)
  {
    try {
      self::adapter()->delete($path);
      return true;
    } catch (FilesystemException | UnableToDeleteFile $exception) {
      exit("Unable to delete file: " . $exception);
    }
  }

  public static function delete_dir($path)
  {
    try {
      self::adapter()->deleteDirectory($path);
      return true;
    } catch (FilesystemException | UnableToDeleteDirectory $exception) {
      exit("Unable to delete directory: " . $exception);
    }
  }

  public static function create_dir($path, $config = ["visibility" => "public"])
  {
    try {
      self::adapter()->createDirectory($path, $config);
      return true;
    } catch (FilesystemException | UnableToCreateDirectory $exception) {
      exit("Unable to create directory: " . $exception);
    }
  }

  public static function move_file($source, $destination, $config = ["visibility" => "public"])
  {
    try {
      self::adapter()->move($source, $destination, $config);
      return true;
    } catch (FilesystemException | UnableToMoveFile $exception) {
      exit("Unable to move file: " . $exception);
    }
  }

  public static function copy_file($source, $destination, $config = ["visibility" => "public"])
  {
    try {
      self::adapter()->copy($source, $destination, $config);
    } catch (FilesystemException | UnableToCopyFile $exception) {
      exit("Unable to copy file: " . $exception);
    }
  }

  public static function lists($path = "."): array
  {
    try {
      return
        self::adapter()
        ->listContents($path)
        ->sortByPath()
        ->map(fn (StorageAttributes $attributes) => $attributes->path())
        ->toArray();
    } catch (FilesystemException | UnableToReadFile $exception) {
      exit("Unable to listing directories: " . $exception);
    }
  }

  public static function mime_content_type($path)
  {
    $detector = new FinfoMimeTypeDetector();
    return $detector->detectMimeTypeFromFile($path);
  }
}