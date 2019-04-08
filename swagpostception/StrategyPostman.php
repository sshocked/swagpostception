<?php
declare(strict_types=1);

namespace swagpostception;


use swagpostception\interfaces\Generateable;

/**
 * Class StrategyPostman
 * @package swagpostception
 */
class StrategyPostman extends StrategyBase implements Generateable
{

    /**
     * From postman to codeception strategy
     */
    public const STRATEGY = 'postman';

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->data->info->name;
    }

    /**
     * @param object $data
     * @return bool
     * @throws \Exception
     */
    public function generate ( $data): bool
    {
        parent::generate($data);
        /** @todo replace StdClass by Structs */
        foreach ($this->data->item as $list) {
            echo PHP_EOL . 'List' . $list->name;
            $this->processCollectionFolder($list);
        }

        return true;
    }

    /**
     * @param array $list
     * @throws \Exception
     */
    protected function processCollectionFolder($list): void
    {
        $directory = $this->directory . '/' . $list->name;
        if(!mkdir($directory, 0750, true)) {
            throw new \Exception('Directory creation failed ' . $directory);
        }
        $methods = [];
        foreach ($list->item as $request) {
            echo PHP_EOL . 'Request: ' . $request->name;
            $methods[] = $this->render->renderMethod($request);
        }

        $result = $this->render->renderCept($list, $methods);
        echo PHP_EOL . 'Result: ' . file_put_contents($directory . $list->name . '.php', $result);
        echo PHP_EOL . 'END' ;
    }


}