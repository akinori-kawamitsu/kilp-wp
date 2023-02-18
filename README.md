# kilp-wp v1.0.0
シンプルなLP用WordPressテーマ  
バージョン表記：v（破壊的な変更）.（機能追加）.（デバッグ）

## コンセプト
- WordPressに多少不慣れな人でも取り扱える
- 親テーマ直接編集でも問題ないように公式リポジトリへの登録は目指さない方針（WordPressのコーディングルールから逸脱しても気にしないことにする）
- 便利系ショートコードをたくさん作る
- 取説を作りながら開発する

## 仕様
- トップページの指定：固定ページ
- 数種類の固定ページテンプレート
  - シンプル
  - マルチコンテンツ（子固定ページを親ページコンテンツの後に続ける。アイキャッチ画像をviewportいっぱいに表示。）※廃止
- レスポンシブ対応
- アイキャッチ画像のレスポンシブ対応（スマートフォン向け画像の登録・切り替え機能）

### 後ほど拡張する機能
- 問合せページ（thanks画面への切り替え機能を付けておく）
- ブロックエディタ対応　※廃止
- マルチコンテンツ＋パララックス　※廃止
- お客様の声とか実績とかの一覧を出力するやつ

## ファイル構成
- /font/  サブセットフォントを格納
  - NotoSansJP-*　ゴシック体
  - ZenOldMincho-*　明朝体
- /js/
  - aos.css, aos.js　スクロールでアニメーション表示される効果のjsプラグイン　https://michalsnik.github.io/aos/
  - luxy.min.js　パララックス効果のjsプラグイン　https://min30327.github.io/luxy.js/
- /scss/　Sassファイル
  - _csshead.scss　style.cssの冒頭部分。テーマとCSS変数
  - _reset.scss　リセットスタイル。sanitize.cssを微修正。一応、修正不可とする。
  - _default.scss　要素の初期デザイン。投稿ページでのスタイルを記述する。プロジェクトによって修正する。
  - _layout.scss　グリッドやコンテナなどのレイアウトに関するコード
  - _utility.scss　マージンやパディング、文字スタイルなど
  - _project.scss　各プロジェクト専用のスタイル
- editor_style.scss　_reset.scssと_default.scssを読み込み
- style.scss　scssフォルダを全部読み込み。
- header.php
- footer.php
- index.php
- front-page.php
- test.html　スタイルの適用状態を確認するために作ったもの。

## コンポーネント
### grid-half
spサイズで縦並び、pcサイズで横並びの2分割構成を作る。

親要素にgrid-halfを付ける。子要素のクラスは以下の通り
- grid-left, grid-right はcontainerサイズを超えた場合にはコンテナサイズの範囲に収まる。
- grid-left-wide, grid-right-wide　は、コンテナサイズを超えた時、画面の端までを占める。
- grid-left-overarea, grid-right-overarea　は、コンテナサイズの外側の領域を占める。画面サイズがコンテナサイズ未満の時は幅が0になる。

## 改訂履歴
### v1.0.0
- ファイル追加
- ウェブフォント　Noto Sans　追加
- reset.scss CSSの初期化にsanitize.css　を採用
- layout.scssにgridレイアウトを追加（grid-half）
- layout.scssにcontent, container を追加
- utility.scssにmargin, padding の設定を追加
- default.scssに見出し要素のスタイリングを追加