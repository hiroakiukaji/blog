<?php
/**
* アプリ用のControllerの親クラス
*/

require(Config::get('CORE_DIR') . 'controller.php');  // HTMLの便利関数群

abstract class AppController extends Controller
{

  /**
  * 出力結果を装飾
  */
  protected function beforeRender()
  {
    $html = $this->output;

    // cssとjsを置き換える
    $css_html = Html::getCSSHtml();
    $js_html = Html::getJSHtml();

    // cssとjsを置き換え
    $html = str_replace(array($this->includeCSS(), $this->includeJS()), array($css_html, $js_html), $html);

    // 編集後のHTMLを出力結果に代入
    $this->output = $html;
  }

  /**
  * CSSの置き換え引数
  */
  public function includeCSS()
  {
    return '<!-- ' . "\xFF" . 'CSS_INCLUDE -->';
  }

  /**
  * Javascriptの置き換え引数
  */
  public function includeJS()
  {
    return '<!-- ' . "\xFF" . 'JS_INCLUDE -->';
  }

  /**
  * blog_idからブログ情報を取得
  */
  public function getBlog($blog_id)
  {
    return Model::load('Blogs')->findById($blog_id);
  }

  /**
  * デバイスタイプを取得する
  */
  protected function getDeviceType()
  {
    return Config::get('DeviceType');
  }

  /**
  * token発行
  */
  protected function setToken($key=null, $name='token')
  {
    if ($key===null) {
      // 適当な値をトークンに設定
      $key = md5(time());
    }
    Session::set($name, $key);
  }

  /**
  * tokenチェック
  */
  protected function tokenValidate($name='token')
  {
    $request = Request::getInstance();
    $value = $request->get($name, '');
    $value = mb_convert_kana($value, 'n');
    return Session::remove($name) == $value ? null : __('Token authentication is invalid');
  }

  /**
  * Debug表示画面
  */
  public function debug()
  {
    if (Config::get('DEBUG')!=2 && Config::get('DEBUG') !=3) {
      return $this->error404();
    }
    $key = Request::getInstance()->get('key');

    if (!is_file(Config::get('TEMP_DIR') . 'debug_html/' . $key)) {
      exit;
    }
    include(Config::get('TEMP_DIR') . 'debug_html/' . $key);
    exit;
  }

}

