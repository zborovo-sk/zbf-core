<?php

namespace ZborovoSK\ZBFCore\MVC;

use ZborovoSK\ZBFCore\Http\Context;
use ZborovoSK\ZBFCore\ZBFApp;
use ZborovoSK\ZBFCore\Http\ResponseException;

/**
 * View provide interface for rendering templates
 */

class View
{
    /**
     * @var array $data to be rendered in template
     */
    private array $data = [];

    /**
     * @var array $tempData - temporary data e.g. for current element
     */
    private array $tempData = [];

    /**
     * @var Context $context
     */
    private Context $context;

    /**
     * @var string $layoutDir
     */
    private string $layoutsDir = 'Templates/Layouts';

    /**
     * @var string $templatesDir
     */
    private string $templatesDir = 'Templates';

    /**
     * @var string $elementsDir
     */
    private string $elementsDir = 'Templates/Elements';

    /**
     * @var string $layout
     */
    private string $layout = 'default';

    /**
     * @var string $content
     */
    private string $content = '';

    /**
     * constructor
     */
    public function __construct(Context $context){
        $this->context = $context;

        $this->setLayoutsDir('Templates/Layouts');
        $this->setTemplatesDir('Templates');
        $this->setElementsDir('Templates/Elements');
    }

    public function setLayoutsDir(string $dir): void
    {
        $this->layoutsDir = ZBFApp::getAppRoot() . '/' . $dir;
    }

    public function setTemplatesDir(string $dir): void
    {
        $this->templatesDir = ZBFApp::getAppRoot() . '/' . $dir;
    }

    public function setElementsDir(string $dir): void
    {
        $this->elementsDir = ZBFApp::getAppRoot() . '/' . $dir;
    }

    /**
     * function for getting data
     */
    public function get($key, $default = null)
    {
        // try to get from temporary data
        if (array_key_exists($key, $this->tempData)) {
            return $this->tempData[$key];
        }

        // try to get from local data
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        // try to get from context
        return $this->context->get($key, $default);
    }

    /**
     * Set Layout
     * @param string $layout
     * @return void
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Set data
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Render file
     * @param string $file
     * @param array $data
     * @return string
     */
    private function renderFile(string $file, array $data = []): string
    {
        ob_start();
        extract($data);
        include $file; //NOSONAR - this is intended
        return ob_get_clean();
    }

    /**
     * Render layout with template
     * @param string $layoutName
     * @return string
     * @throws ResponseException
     */
    public function renderLayout(string $layoutName){
        $layoutFile = $this->layoutsDir . '/' . $layoutName . '.php';
        if (!file_exists($layoutFile)) {
            throw new ResponseException(500, ['message' => 'Layout not found']);
        }

        return $this->renderFile($layoutFile, ['content' => $this->content]);
    }

    /**
     * Render template
     * @param string $templateName
     * @return string
     * @throws ResponseException
     */
    public function renderTemplate(string $templateName){
        $templateFile = $this->templatesDir . '/' . $templateName . '.php';
        if (!file_exists($templateFile)) {
            throw new ResponseException(500, ['message' => 'Template not found']);
        }

        return $this->renderFile($templateFile);
    }

    /**
     * Render element
     * @param string $elementName
     * @return string
     * @throws ResponseException
     */
    public function element(string $elementName, $data = []){
        $elementFile = $this->elementsDir . '/' . $elementName . '.php';
        if (!file_exists($elementFile)) {
            throw new ResponseException(500, ['message' => 'Element not found']);
        }

        //set/overwrit temporary data
        $this->tempData = $data;

        return $this->renderFile($elementFile);
    }

    /**
     * Render function
     */
    public function render(string $templateName, array $data = [], string $layoutName = null){
        if($layoutName){
            $this->layout = $layoutName;
        }

        if(!empty($data)){
            $this->data = $data;
        }

        //render template
        $this->content = $this->renderTemplate($templateName);

        if($this->layout == null){
            //return content only
            return $this->content;
        }

        //render layout with content
        return $this->renderLayout($this->layout);
    }
}
