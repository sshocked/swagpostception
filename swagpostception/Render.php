<?php
declare(strict_types=1);

namespace swagpostception;


/**
 * Class Render
 * @package swagpostception
 */
class Render
{

    public const REQUEST_GET = 'GET';
    public const REQUEST_POST = 'POST';
    public const REQUEST_PUT = 'PUT';
    public const REQUEST_DELETE = 'DELETE';

    protected const PARAM_NAME = 'name';
    protected const PARAM_DESCRIPTION = 'description';
    protected const PARAM_PARAMS = 'params';
    protected const PARAM_URL = 'url';

    protected const PARAM_CEPT_CEPTNAME = 'ceptname';
    protected const PARAM_CEPT_METHODS = 'methods';

    protected const TEMPLATE_POST = '/templates/template_post.txt';
    protected const TEMPLATE_CEPT = '/templates/template_cept.txt';

    /**
     * @var
     */
    protected $template;

    /**
     * @param object $list
     * @param array $methods
     * @return string
     */
    public function renderCept( $list, array $methods): string {
        $this->template = file_get_contents(__DIR__ . self::TEMPLATE_CEPT);
        $this->renderParam(self::TEMPLATE_POST, $list->name);
        $this->renderParam(self::PARAM_CEPT_METHODS, implode('', $methods));

        return $this->template;
    }

    /**
     * @param object $request
     * @return string
     * @throws \Exception
     */
    public function renderMethod ( $request): string
    {
        if(empty($request->request->method)) {
            throw new \Exception('Empty method type');
        }
        if(empty($this->getMethodTemplate($request->request->method))) {
            return '';
        }

        $this->template = file_get_contents($this->getMethodTemplate($request->request->method));
        $this->renderParam(self::PARAM_NAME, $request->name);
        $this->renderParam(self::PARAM_DESCRIPTION, $request->description);
        $this->renderParam(self::PARAM_URL, $request->url->raw);
        $this->renderParam(self::PARAM_PARAMS, (array) $request->request->body->raw);

        return $this->template;
    }

    /**
     * @param string $name
     * @param $value
     */
    protected function renderParam(string $name, $value)
    {
        $this->template = str_replace("%$name%", $value, $this->template);
    }

    /**
     * @param string $methodType
     * @return string
     * @throws \Exception
     */
    protected function getMethodTemplate (string $methodType): string
    {
        $map = [
            self::REQUEST_POST => __DIR__ . self::TEMPLATE_POST
        ];
        if(empty($methodType) || !is_string($methodType) || !isset($map[$methodType])) {
            return '';
            //throw new \Exception('Wrong method type');
        }

        return $map[$methodType];
    }


}