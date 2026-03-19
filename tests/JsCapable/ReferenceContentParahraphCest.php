<?php

namespace Tests\Js_capable;

use Tests\Support\JSCapableTester;
use Codeception\Util\Drupal\FormField;
use Codeception\Util\Drupal\ParagraphFormField;
use Codeception\Util\Drupal\MTOFormField;
use Codeception\Util\Fixtures;
use Codeception\Util\Locator;
use Exception;

/**
 * Class ReferenceContentParagraphCest
 *
 * @package Tests\Js_capable
 */
class ReferenceContentParagraphCest
{
    /**
     * Setup environment before each test.
     *
     * @param JSCapableTester $I
     */
    protected function login(JSCapableTester $I)
    {
        $I->amOnPage('/user');
        $url = $I->getLoginUri('admin');
        $I->amOnPage($url);
    }

    /**
     * Create content.
     *
     * @param JSCapableTester $I
     * @throws Exception
     * 
     * @before login
     */
    public function addTestContent(JSCapableTester $I)
    {
        $I->wantTo('Create content.');
        $I->amOnPage('/node/add/blog_post/');
        $I->seeVar(MTOFormField::title());
        $I->fillTextField(FormField::title(), 'Reference content test page');
        $I->fillCk5WysiwygEditor(FormField::field_blog_teaser(), 'Test content');
        $I->click('#gin-sticky-edit-submit');
        $I->waitAjaxLoad($I, 30);
        $url = $I->grabFromCurrentUrl();
        $I->comment($url);
        Fixtures::add('test_content_url', $url);
    }

  /**
   * Test if I can add reference content to Content Page.
   *
   * @param JSCapableTester $I
   * @throws Exception
   * 
   * @before login
   */
    public function addReferenceContentToContentPage(JSCapableTester $I)
    {
        $I->wantTo('Add reference content to new content page.');
        $I->amOnPage('/node/add/content_page/');
        $I->seeVar(MTOFormField::title());
        $I->fillTextField(FormField::title(), 'Page to be referenced');
        $I->click(Locator::contains('strong', 'Page Sections'));
        $page_elements = ParagraphFormField::field_page_section();
        $I->seeVar($page_elements);
        $I->click('.dropbutton-toggle button');
        $I->addNewParagraph('d_p_reference_content', $page_elements);
        $I->fillTextField(FormField::field_d_main_title($page_elements), 'Reference content component');
        $I->click(MTOFormField::field_d_media_icon($page_elements)->__get('open-button'));
        $I->attachImage($I, 'mask.png');
        $I->click(
            "//*[contains(@data-drupal-selector, 'edit-field-page-section-0')]
             //*[contains(@class, 'horizontal-tab-button-1')] //a[contains(@href, '#edit-group-items')]"
        );
        $page_item = ParagraphFormField::field_d_p_reference_content($page_elements);
        $I->seeVar($page_item);
        $selector = "//*[contains(@data-drupal-selector, " .
                    "'edit-field-page-section-0-subform-field-d-p-reference-content-0')]";
        $I->fillField($selector, 'Reference content test page');
        $I->click('#gin-sticky-edit-submit');
        $I->waitAjaxLoad($I, 30);
        $url = $I->grabFromCurrentUrl();
        $I->comment($url);
        Fixtures::add('reference_content_url', $url);
    }

  /**
   * Test if I can see the added reference content.
   *
   * @param JSCapableTester $I
   */
    public function seeCreatedReferenceContentAsRandomUser(JSCapableTester $I)
    {
        $I->wantTo('see if the reference content is created');
        $I->amOnPage(Fixtures::get('reference_content_url'));
        $I->see('Reference content component');
        $src_icon = $I->grabAttributeFrom('.d-p-reference-content__content .media-icon img', 'src');
        $I->seeVar($src_icon);
        $I->assertStringContainsString('mask', $src_icon);
        $I->see('Reference content test page');
        $I->see('LEARN MORE');
        $I->click('Learn more');
        $I->moveBack();
    }

    /**
     * Removing added reference content and checking if it's deleted.
     *
     * @param JSCapableTester $I
     * 
     * @before login
     */
    public function removeReferenceContent(JSCapableTester $I)
    {
        $I->wantTo('Clear up after the Reference Content test.');

        // Delete reference content
        $I->deleteContentPage($I, Fixtures::get('reference_content_url'));

        // Delete test content
        $I->deleteContentPage($I, Fixtures::get('test_content_url'));
    }
}
