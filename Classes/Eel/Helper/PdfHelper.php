<?php
namespace Kleisli\FusionFiles\Pdf\Eel\Helper;

use Knp\Snappy\Pdf;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Fusion\Core\Cache\ContentCache;

class PdfHelper implements ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected ConfigurationManager $configurationManager;

    #[Flow\Inject]
    protected ContentCache $contentCache;


    public function render(string $body, string $header='', string $footer=''): string
    {
        $header = $this->contentCache->processCacheSegments($header);
        $body = $this->contentCache->processCacheSegments($body);
        $footer = $this->contentCache->processCacheSegments($footer);

        $snappy = new Pdf($this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, "Kleisli.FusionFiles.Pdf.pathToWkhtmltopdf"));
        $snappy->setOption('encoding', 'UTF-8');
        $snappy->setOption('margin-left', '0');
        $snappy->setOption('footer-spacing', '0');
        $snappy->setOption('header-spacing', '0');
        $snappy->setOption('margin-top', '27');
        $snappy->setOption('margin-bottom', '22');
        $snappy->setOption('load-error-handling', 'ignore');
        $snappy->setOption("enable-local-file-access",true);

        $temporaryPathAndFilename = sys_get_temp_dir() .'/'. uniqid('', true);

        $headerFile = fopen($temporaryPathAndFilename.'-header.html', 'w');
        fwrite($headerFile, $header);
        $snappy->setOption('header-html', $temporaryPathAndFilename.'-header.html');

        $footerFile = fopen($temporaryPathAndFilename.'-footer.html', 'w');
        fwrite($footerFile, $footer);
        $snappy->setOption('footer-html', $temporaryPathAndFilename.'-footer.html');

        return $snappy->getOutputFromHtml($body);
    }

    public function replaceRelativeStaticResourcesPathWithAbsolutePath(string $input): string
    {
        return str_replace('/_Resources/Static/Packages', 'https://'.$_SERVER['SERVER_NAME'].'/_Resources/Static/Packages', $input);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
