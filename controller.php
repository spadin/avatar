<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('single_page');

class AvatarPackage extends Package {

  protected $pkgHandle = 'avatar';
  protected $appVersionRequired = '5.4.2.2';
  protected $pkgVersion = '0.9.3';

  public function getPackageDescription() {
    return t("User avatars through a simple URL scheme. <a href='http://github.com/spadin/avatar'>View documentation</a>.");
  }

  public function getPackageName() {
    return t("Avatar");
  }

  public function install() {
    $pkg  = parent::install();
    $page = SinglePage::add('avatar', $pkg);

    $page->setAttribute('exclude_nav', true);
    $page->setAttribute('exclude_page_list', true);
    $page->setAttribute('exclude_search_index', true);
    $page->setAttribute('exclude_sitemapxml', true);
  }
}

?>
