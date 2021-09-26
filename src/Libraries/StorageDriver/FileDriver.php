<?php

namespace Jakmall\Recruitment\Calculator\Libraries\StorageDriver;

use Exception;
use Jakmall\Recruitment\Calculator\Libraries\File;

class FileDriver
{
    private $filename = 'src/storage/mesinhitung.log';
    private $file;

    public function __construct()
    {
        if (!file_exists($this->filename)) {
            touch($this->filename);
        }
        $this->file = new File($this->filename);
    }

    public function readAll(): array
    {
        return array_map(function ($el) {
            $el = explode("|", $el);
            $operations = explode("=", $el[2]);
            return ['id' => $el[0], 'command' => $el[1], 'operation' => $operations[0], 'result' => $operations[1]];
        }, $this->file->readToArray());
    }

    public function read($id): array
    {
        $arrays = array_map(function ($el) {
            $el = explode("|", $el);
            $operations = explode("=", $el[2]);
            return ['id' => $el[0], 'command' => $el[1], 'operation' => $operations[0], 'result' => $operations[1]];
        }, $this->file->readToArray());
        $key = array_search((int)$id, array_column($arrays, 'id'));
        if ($key === false) {
            return [];
        }
        return [$arrays[$key]];
    }

    public function write($content, $command): bool
    {
        $log_array = $this->file->readToArray();
        $latest_id = max(array_column(array_map(function ($el) {
            $el = explode("|", $el);
            return ['id' => $el[0]];
        }, $log_array), 'id'));
        $log = $latest_id + 1;
        $log .= "|$command";
        $log .= "|$content";
        array_push($log_array, $log);
        return $this->file->write(implode(PHP_EOL, $log_array));
    }

    public function clearAll(): bool
    {
        return $this->file->write("");
    }

    public function clear($id): bool
    {
        $arrays = array_map(function ($el) {
            $el = explode("|", $el);
            $operations = explode("=", $el[2]);
            return ['id' => $el[0], 'command' => $el[1], 'operation' => $operations[0], 'result' => $operations[1]];
        }, $this->file->readToArray());
        $array = array_filter($arrays, function ($e) use ($id) {
            return ($e['id'] !== $id);
        });
        if (count($array) === 0) {
            return $this->clearAll();
        }
        return $this->file->write(implode(PHP_EOL, array_map(function ($el) {
            return "{$el['id']}|{$el['command']}|${el['operation']}={$el['result']}";
        }, $array)));
    }
}
