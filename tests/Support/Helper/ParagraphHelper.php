<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Util\Drupal\FormField;
use Codeception\Util\Drupal\ParagraphFormField;
use Codeception\Util\IdentifiableFormFieldInterface;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use GuzzleHttp\Client;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ParagraphHelper extends \Codeception\Module
{
    /**
     * Add paragraph element.
     */
    public function addNewParagraph(string $type, ParagraphFormField $field)
    {
        /** @var \Codeception\Module\WebDriver $I */
        $I = $this->getModule('WebDriver');

        $I->click('[name=field_page_section_' . $type . '_add_more]');
        $I->waitForElementClickable($field->getCurrent());
    }

    /**
     * Wait ajax load.
     */
    public function waitAjaxLoad(\Tests\Support\JsCapableTester $I, $timeout = 10)
    {
        $I->waitForJS('return !!window.jQuery && window.jQuery.active == 0;', $timeout);
        $I->waitForJS('return document.readyState === "complete";', $timeout);
    }

    /**
     * Attach Image.
     */
    public function attachImage(\Tests\Support\JsCapableTester $I, string $image)
    {
        $I->waitAjaxLoad($I,30);
        $I->attachFile('[id^=edit-upload-upload--]', $image);
        $I->waitForElementVisible('[id^=edit-media-0-fields-field-media-image-0-alt--]', 30);
        $I->waitAjaxLoad($I,30);
        $I->fillField('[id^=edit-media-0-fields-field-media-image-0-alt--]', 'image alt');
        $I->click('.form-actions button');
        $I->waitAjaxLoad($I,30);
        $I->click('.ui-dialog-buttonpane button');
        $I->waitAjaxLoad($I,30);
    }

    /**
     * Fill text field.     
     */
    public function fillCk5WysiwygEditor(IdentifiableFormFieldInterface $field, $content): void
    {
        /** @var \Codeception\Module\WebDriver $I */
        $I = $this->getModule('WebDriver');

        $selector = $I->grabAttributeFrom($field->value, 'id');
        $script = "
         window.Drupal.CKEditor5Instances.forEach((instance) => {
           if (instance.sourceElement.id === '$selector') {
             instance.setData('$content');
           }
         });";
        $I->executeInSelenium(function (RemoteWebDriver $webDriver) use ($script) {
            $webDriver->executeScript($script);
        });
        $I->wait(1);
    }

    /**
     * Fill link field.
     *
     * @param \Tests\Support\JsCapableTester $I
     * @param \Codeception\Util\IdentifiableFormFieldInterface $field
     * @param string $uri
     */
    public function fillSingleLinkField(\Tests\Support\JsCapableTester $I, IdentifiableFormFieldInterface $field, $uri)
    {
        $I->fillField($field->uri, $uri);
    }

        /**
     * Delete content page using Gin UI.
     *
     * @param \Tests\Support\JsCapableTester $I
     * @param string $url URL of the content to delete
     */
    public function deleteContentPage(\Tests\Support\JsCapableTester $I, string $url): void
    {
        $I->amOnPage($url);
        $I->goEditPage($I);
        $I->scrollTo('#edit-delete', 0, -200);
        $I->click('.gin-more-actions__trigger');
        $I->moveMouseOver('#edit-more-actions-items #edit-delete');
        $I->makeScreenshot('hover_delete');
        $I->click('#edit-more-actions-items #edit-delete');
        $I->waitForElementVisible('#drupal-modal');
        $I->makeScreenshot('delete-content-page');
        $I->click('//button[contains(@class, "button--primary") and contains(text(), "Delete")]');
        $I->waitForElement('.alert-success');
        $I->seeElement('.alert-success');
    }
}
