<?php

namespace LionFiles;

use LionRequest\Response;

class FILES {

	public static string $url_path = "storage/upload_files/";

	public function __construct() {

	}

	public static function imageSize(string $path, string $data_path, string $imgSize): object {
		$data_file = getimagesize("{$path}{$data_path}");

		$union = "{$data_file[0]}x{$data_file[1]}";
		if ($union != $imgSize) {
			return Response::error("The file '{$data_path}' does not have the requested dimensions '{$imgSize}'");
		}

		return Response::success("File '{$data_path}' meets requested dimensions '{$imgSize}'");
	}

	public static function size(string $path, int $size): object {
		$path = self::replace($path);
		$file_size_kb = filesize($path) / 1024;

		if ($file_size_kb > $size) {
			return Response::error("The file '{$path}' is larger than the requested size");
		}

		return Response::success("The file '{$path}' meets the requested size");
	}

	public static function view(string $path): array|object {
		$path = self::replace($path);
		$list = scandir($path, 1);
		$data = [];

		for ($i = 0; $i < (count($list) - 2); $i++) {
			array_push($data, "{$path}{$list[$i]}");
		}

		return $data;
	}

	public static function remove(string $path): object {
		if (!unlink($path)) {
			return Response::error("The file '{$path}' has not been removed");
		}

		return Response::success("The file '{$path}' has been deleted");
	}

	public static function exist(string $path): object {
		if (!file_exists($path)) {
			return Response::error("The file/folder '{$path}' does not exist");
		}

		return Response::success("The file/folder '{$path}' exists");
	}

	public static function rename(string $file, ?string $indicative = null): string {
		if ($indicative != null) {
			return self::replace($indicative) . "-" . md5(hash('sha256', uniqid())) . "." . self::getExtension($file);
		} else {
			return md5(hash('sha256', uniqid())) . "." . self::getExtension($file);
		}
	}

	public static function upload(string $tmp_name, string $name, ?string $path = null): object {
		$path = $path === null ? self::$url_path : $path;
		$path = self::replace($path);
		self::folder($path);

		if (!move_uploaded_file($tmp_name, "{$path}{$name}")) {
			return Response::error("The file '{$name}' was not loaded");
		}

		return Response::success("The file '{$name}' was uploaded");
	}

	public static function getExtension(string $path): string {
		return (new \SplFileInfo($path))->getExtension();
	}

	public static function getName(string $path): string {
		return (new \SplFileInfo($path))->getBasename("." . self::getExtension($path));
	}

	public static function getBasename(string $path): string {
		return (new \SplFileInfo($path))->getBasename();
	}

	public static function folder(?string $path = null): object {
		$path = self::replace($path === null ? self::$url_path : $path);

		$requestExist = self::exist($path);
		if ($requestExist->status === 'error') {
			if (mkdir($path, 0777, true)) {
				return Response::success("Directory '{$path}' created");
			} else {
				return Response::error("Directory '{$path}' not created");
			}
		}

		return Response::success($requestExist->message);
	}

	public static function validate(array $files, array $exts): object {
		foreach ($files as $key_file => $file) {
			$file_extension = self::getExtension($file);

			if (!in_array($file_extension, $exts)) {
				return Response::error("The file '{$file}' does not have the required extension");
				break;
			}
		}

		return Response::success("files have required extension");
	}

	public static function replace(string $value): string {
		$value = str_replace("??", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("??", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("?????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("?????", "??", $value);
		$value = str_replace("????", "??", $value);
		$value = str_replace("?????", "??", $value);
		$value = str_replace("&aacute;", "??", $value);
		$value = str_replace("&eacute;", "??", $value);
		$value = str_replace("&iacute;", "??", $value);
		$value = str_replace("&oacute;", "??", $value);
		$value = str_replace("&uacute;", "??", $value);
		$value = str_replace("&ntilde;", "??", $value);

		return $value;
	}

}