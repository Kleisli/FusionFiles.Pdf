# Kleisli.FusionFiles.Pdf
Generate pdf files with Neos.Fusion using wkhtmltopdf

## Requirements
* [wkhtmltopdf](https://github.com/wkhtmltopdf/wkhtmltopdf)

## Fusion Document
To render a node `My.DocumentType` as pdf, create a fusion prototype `My.DocumentType.Pdf` that
extends `Kleisli.FusionFiles:File.Pdf`.

### Properties of `Kleisli.FusionFiles:File.Pdf`
* `showAsHtml`, boolean, to debug the response, default = false
* `disposition`, either 'inline' (default value, to display the pdf file in the brower) or 'attachment' (to download the pdf file directly)
* `filename`, string, with disposition='attachment' this defines the filename of the file to be downlaoded
* `cssRessourcePaths`, Neos.Fusion:DataStructure, the paths to the css files needed to render the html for the pdf
* `htmlContent`, Neos.Fusion:DataStructure with fixed keys
    * `header`, string, the html code to be repeated on top of every page of the pdf document
    * `body`, string, the html code containing the content
    * `footer`, string, the html code to be repeated in the bottom of every page of the pdf document

### Configuration
```
Kleisli:
    FusionFiles:
        Pdf:
            pathToWkhtmltopdf: '/usr/local/bin/wkhtmltopdf'
```

## Fusion Content
Rendering a Document node as Pdf using `Neos.Neos:ContentCollection` displays the Content nodes
with the same Markup as for the HTML web page.

### pdf specific markup for content
While the same markup might be fine for many content elements, there might also be elements (especially
elements relying on javascript), that need a pdf specific markup. This can be achieved by just creating a
prototype with appended `.Pdf`, e.g. to change markup for `My.ContentType` in the pdf, create `My.ContentType.Pdf`

### hide content in pdf or render in pdf only
To render content elements in pdf only and hide them in other formats, or skip them in the pdf document, create a node
property `pdfVisibility` and set it to either 'hideInPdf' or 'pdfOnly'.

Or add the mixin `Kleisli.FusionFiles.Pdf:Mixin.PdfVisibility` to the node type to be able to define visibility
in the backend.

## Kudos
The development of this package has significantly been funded by [Profolio](https://www.profolio.ch/) - a digital platform for career choice & career counseling

