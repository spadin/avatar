<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::tool('phpthumb/ThumbLib.inc', null, 'avatar');

class AvatarController extends Controller {
  protected $width, $height, $user, $format, $content_type, $ui;
  protected $originalAvatar, $newAvatar;

  public function view($param1, $param2 = null) {
    $this->setContentType("text/plain");
    $this->apply($param1, $param2);

    $image = $this->getImage();
    if(!$image) {
      if($this->generateImage()) {
        $image = $this->getImage();
      }
    }

    $this->setContentType($this->content_type);
    $image->show();
    exit;
  }
  protected function generateImage() {
    $thumb = PhpThumbFactory::create($this->originalAvatar, array(
      'resizeUp' => true
    ));
    $thumb->adaptiveResize($this->width, $this->height);
    $thumb->resize($this->width, $this->height);
    return $thumb->save($this->newAvatar, $this->format);
  }
  protected function getImage() {
    $image = null;

    if(file_exists($this->newAvatar)) {
      $image = PhpThumbFactory::create($this->newAvatar);
    }

    return $image;
  }
  protected function apply($param1, $param2) {
    $size = $param1;
    $info = $param2;

    if(!$param2) {
      $size = "50x50";
      $info = $param1;
    }

    list($width,$height) = explode("x", strtolower($size));
    list($user,$format)  = explode(".", $info);
    if(!$format) $format = 'png';
    $format = strtolower($format);

    $this->width  = $width;
    $this->height = $height;
    $this->user   = $user;
    $this->format = $format;

    switch($format) {
      case "png":
        $this->content_type = "image/png";
        break;
      case "gif":
        $this->content_tyep = "image/gif";
        break;
      default:
        $this->content_type = "image/jpeg";
    }

    if(is_numeric($this->user)) {
      $this->ui = UserInfo::getById($this->user);
    }
    else {
      $this->ui = UserInfo::getByUserName($this->user);
    }

    if(!$this->ui) {
      die('User does not exist.');
    }

    $ih = Loader::helper('image');
    $av = Loader::helper('concrete/avatar');

    $avatarImagePath = $av->getImagePath($this->ui, false);
    if(!$avatarImagePath) {
      $avatarImagePath = "/packages/avatar/images/default.png";
    }

    if(strpos($avatarImagePath, DIR_BASE) === false) {
      $avatarImagePath = DIR_BASE . $avatarImagePath;
    }
    $this->originalAvatar = $avatarImagePath;

    $filename = md5(DIR_BASE .':'. $this->ui->getUserId() .':'. $this->width .':'. $this->height .':'. filemtime($this->originalAvatart)) . '.' . $this->format;
    $filename = DIR_BASE ."/files/cache/". $filename;
    $this->newAvatar = $filename;

    return;
  }
  public function setContentType($contentType) {
    header("Content-type: $contentType");
  }
}
?>
