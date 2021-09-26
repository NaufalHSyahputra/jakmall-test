<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryController
{
    private $log;
    public function __construct(CommandHistoryManagerInterface $log)
    {
        $this->log = $log;
    }
    public function index(Request $request)
    {
        $this->log->setDriver($request->query('driver', 'composite'));
        $arrays = $this->log->findAll();
        if (count($arrays) <= 0) { 
            return Response::create([], Response::HTTP_OK);
        }
        $arrays = array_map(function ($el) { 
            preg_match_all('!\d+!', $el['operation'], $matches);
            return [
                'id' => $el['id'],
                'command' => $el['command'],
                'operation' => $el['operation'],
                'input' => $matches[0],
                'result' => $el['result']
            ];
        }, $arrays);
        return Response::create($arrays, Response::HTTP_OK);
    }

    public function show(Request $request, string $id)
    {
        $this->log->setDriver($request->query('driver', 'composite'));
        $arrays = $this->log->find($id);
        if (count($arrays) <= 0) { 
            return Response::create([], Response::HTTP_OK);
        }
        $arrays = array_map(function ($el) { 
            preg_match_all('!\d+!', $el['operation'], $matches);
            return [
                'id' => $el['id'],
                'command' => $el['command'],
                'operation' => $el['operation'],
                'input' => $matches[0],
                'result' => $el['result']
            ];
        }, $arrays);
        return Response::create($arrays, Response::HTTP_OK);
    }

    public function remove(string $id)
    {
        $this->log->setDriver('composite');
        $this->log->clear($id);
        return Response::create([], Response::HTTP_NO_CONTENT);
    }
}
