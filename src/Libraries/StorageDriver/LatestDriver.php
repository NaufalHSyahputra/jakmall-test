<?php

namespace Jakmall\Recruitment\Calculator\Libraries\StorageDriver;

use Exception;
use Jakmall\Recruitment\Calculator\Libraries\File;

class LatestDriver
{
    private $filename = 'src/storage/latest.log';
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
        $new_arrays = $this->moveValueByIndex($arrays, $key, 0);
        $this->file->write(implode(PHP_EOL, array_map(function ($el) {
            return "{$el['id']}|{$el['command']}|${el['operation']}={$el['result']}";
        }, $new_arrays)));
        return [$new_arrays[0]];
    }

    public function write($content, $command): bool
    {
        $log_array = $this->file->readToArray();
        $new_arrays = array_map(function ($el) { 
            $el = explode("|", $el);
            return ['id' => (int)$el[0], 'text' => "|{$el[1]}|{$el[2]}"];
        }, $this->file->readToArray());
        $duplicate_key = $this->findDuplicate($new_arrays, $content, $command);
        if ($duplicate_key !== -1 && $duplicate_key !== count($log_array)-1) { 
            unset($log_array[$duplicate_key]);
        }
        $latest_id = max(array_column($new_arrays, 'id'));
        $log = $latest_id + 1;
        $log .= "|$command";
        $log .= "|$content";
        array_unshift($log_array, $log);
        if (count($log_array) > 10) {
            array_pop($log_array);
        }
        return $this->file->write(implode(PHP_EOL, $log_array));
    }

    private function findDuplicate($new_arrays, $content, $command): int { 
        $log = "|$command";
        $log .= "|$content";
        $found = -1;
        foreach ($new_arrays as $i => $array) { 
            if ($array['text'] === $log) { 
                $found = $i;
                break;
            }
        }
        return $found;
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

    private function moveValueByIndex(array $array, $from = null, $to = null)
    {
        if (null === $from) {
            $from = count($array) - 1;
        }

        if (!isset($array[$from])) {
            throw new Exception("Offset $from does not exist");
        }

        if (array_keys($array) != range(0, count($array) - 1)) {
            throw new Exception("Invalid array keys");
        }

        $value = $array[$from];
        unset($array[$from]);

        if (null === $to) {
            array_push($array, $value);
        } else {
            $tail = array_splice($array, $to);
            array_push($array, $value);
            $array = array_merge($array, $tail);
        }

        return $array;
    }
}
