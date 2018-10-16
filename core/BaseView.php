<?php
namespace core;

use \core\dto\cssFile;
use \core\dto\jsFile;

class BaseView
{
    public $basePath = '';
    public $layoutPath = '';
    public $contentTemplate = '';
    public $language = 'en';
    public $codePage = 'utf-8';
    public $css = [];
    public $topJs = [];
    public $bottomJs = [];
    public $bodyText = '';

    public function __construct($basePath)
    {
        $basePath = trim($basePath, ' /\\');
        $this->basePath = $basePath.DIRECTORY_SEPARATOR;
        $this->layoutPath = $this->basePath.'_layout'.DIRECTORY_SEPARATOR;
    }

    public function render($fileName = '', $params = [])
    {
        if (is_file($this->basePath.$fileName.'.php'))
        {
            $this->contentTemplate = $this->basePath.$fileName.'.php';
        }

        foreach($params as $name => $value)
        {
            $this->{$name} = $value;
        }

        if (!empty($this->contentTemplate)) {
            ob_start();
            include $this->contentTemplate;
            $this->bodyText = ob_get_contents();
            ob_end_clean();
        }

        if (is_file($this->layoutPath.'main.php'))
        {
            include $this->layoutPath.'main.php';
        }
        else
        {
            $this->renderHead();
            $this->renderBody();
            $this->renderFooter();
        }
    }

    private function renderHead()
    {
        if (count($this->css) > 0)
        {
            foreach ($this->css as $css)
            {
                echo '<link href="'.$css->getLink().'" rel="stylesheet">'."\n";
            }
        }
        if (count($this->topJs) > 0)
        {
            foreach ($this->topJs as $js)
            {
                echo '<script type="text/javascript"'.($js->hasBody() ? '' : ' src="'.$js->getLink().'"').'>'.$js->getBody().'</script>'."\n";
            }
        }
    }

    private function renderBody()
    {
        echo $this->bodyText;
    }

    private function renderFooter()
    {
        if (count($this->bottomJs) > 0)
        {
            foreach ($this->bottomJs as $js)
            {
                echo '<script type="text/javascript"'.($js->hasBody() ? '' : ' src="'.$js->getLink().'"').'>'.$js->getBody().'</script>'."\n";
            }
        }
    }

    public function registerCss($link, $needVersion = false)
    {
        $this->css[] = new cssFile($link, $needVersion);
    }

    public function registerJs($link, $inBottom = false, $needVersion = false)
    {
        if ($inBottom)
        {
            $this->bottomJs[] = new jsFile($link, $needVersion);
        }
        else
        {
            $this->topJs[] = new jsFile($link, $needVersion);
        }
    }

    public function embedJs($text, $inBottom = false)
    {
        if (strlen($text) > 0)
        {
            $js = new jsFile('');
            $js->body = $text;
            if ($inBottom)
            {
                $this->bottomJs[] = $js;
            }
            else
            {
                $this->topJs[] = $js;
            }
        }
    }

}