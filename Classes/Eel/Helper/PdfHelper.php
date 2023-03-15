<?php
namespace Kleisli\FusionFiles\Pdf\Eel\Helper;

/*
 * This file is part of the Neos.Eel package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Knp\Snappy\Pdf;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Utility\Environment;
use Neos\Fusion\Core\Cache\ContentCache;

class PdfHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @Flow\Inject
     */
    protected ContentCache $contentCache;


    /**
     *
     * @param string $body
     * @param string $header
     * @param string $footer
     * @return string
     */
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
        $snappy->setOption('load-error-handling', 'ignore');

        $temporaryPathAndFilename = sys_get_temp_dir() .'/'. uniqid('', true);

        $headerFile = fopen($temporaryPathAndFilename.'-header.html', 'w');
        fwrite($headerFile, $header);
        $snappy->setOption('header-html', $temporaryPathAndFilename.'-header.html');

        $footerFile = fopen($temporaryPathAndFilename.'-footer.html', 'w');
        fwrite($footerFile, $footer);
        $snappy->setOption('footer-html', $temporaryPathAndFilename.'-footer.html');

        return $snappy->getOutputFromHtml($body);
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
