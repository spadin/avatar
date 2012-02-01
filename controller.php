<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('single_page');

class AvatarPackage extends Package {

  protected $pkgHandle = 'avatar';
  protected $appVersionRequired = '5.4.2.2';
  protected $pkgVersion = '0.9';

  public function getPackageDescription() {
    return t("User avatars through a simple URL scheme. <a href='http://github.com/spadin/avatar'>View documentation</a>.");
  }

  public function getPackageName() {
    return t("Avatar");
  }

  public function install() {
    $pkg  = parent::install();
    SinglePage::add('avatar', $pkg);
  }
}

?>
