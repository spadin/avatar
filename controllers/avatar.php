<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::library('phpthumb/ThumbLib.inc', 'avatar');

class AvatarController extends Controller {
  protected $width, $height, $user, $format, $content_type;
  protected $ui, $originalAvatar, $newAvatar;

  public function view($param1 = null, $param2 = null) {
    // The URL /avatar does not exist, redirect to homepage.
    if($param1 === null) {
      $this->redirect("/");
      exit;
    }
    $this->setContentType("text/plain");
    $this->setProperties($param1, $param2);
    $this->setImageFileProperties();
    $this->checkCacheHeaders();

    $image = $this->getImage();
    if(!$image) {
      if($this->generateImage()) {
        $image = $this->getImage();
      }
      else {
        die("Could not generate image.");
      }
    }

    $this->setContentType($this->content_type);
    $this->setCacheHeaders();
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
  protected function setProperties($param1, $param2) {
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

    switch($this->format) {
      case "png":
        $this->content_type = "image/png";
        break;
      case "gif":
        $this->content_type = "image/gif";
        break;
      default:
        $this->content_type = "image/jpeg";
    }
  }
  protected function setImageFileProperties() {
    if(!is_numeric($this->user)) {
      $ui = UserInfo::getByUserName($this->user);
      if($ui) $this->user = $ui->getUserId();
      else $this->user = -1;
    }
    else {
      $ui = UserInfo::getById($this->user);
      if($ui) $this->user = $ui->getUserId();
      else $this->user = -1;
    }

    $avatarImagePath = $this->getImagePath();
    
    if($avatarImagePath == null) {
      $avatarImagePath = "/packages/avatar/images/default.png";
      if($this->user < 0) {
        $avatarImagePath = "/packages/avatar/images/notfound.png";
      }
    }

    if(strpos($avatarImagePath, DIR_BASE) === false) {
      $avatarImagePath = DIR_BASE . $avatarImagePath;
    }
    $this->originalAvatar = $avatarImagePath;
    $filename = md5(DIR_BASE .':'. $this->user .':'. $this->width .':'. $this->height .':'. filemtime($this->originalAvatar)) . '.' . $this->format;
    $filename = DIR_BASE ."/files/cache/". $filename;
    $this->newAvatar = $filename;

    return;
  }
  protected function getImagePath() {
    $src = null;
    if(file_exists(DIR_FILES_AVATARS . '/' . $this->user . '.jpg')) {
      $src = DIR_FILES_AVATARS . '/' . $this->user . '.jpg';
    }
    elseif(file_exists(DIR_FILES_AVATARS . '/' . $this->user . '.gif')) {
      $src = DIR_FILES_AVATARS . '/' . $this->user . '.gif';
    }
    
    return $src;
  }
  protected function setCacheHeaders() {
    header("Cache-Control: private, max-age=10800, pre-check=10800");
    header("Pragma: private");
    header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($this->originalAvatar)) . ' GMT');
  }
  protected function checkCacheHeaders() {
    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($this->originalAvatar))) {
      $this->setCacheHeaders();
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($this->originalAvatar)).' GMT', true, 304);
      exit;
    }
  }
  public function setContentType($contentType) {
    header("Content-type: $contentType");
  }
}
?>
