<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About MINI-TEAM_DEV-85

MINI-TEAM_DEV-85 は、日記を共有できるWebアプリケーションです。
投稿の作成・編集・削除だけでなく、友人の投稿に「いいね」やコメントを残すこともできます。
さらに、投稿を検索・並び替えする機能もあり、まるでシンプルなInstagramのように使うことができます。
このアプリを通じて、自分の近況をシェアしたり、友人と交流したりすることができます。

本アプリケーションは、Rinto と Hikaru によって、わずか10日間で開発されました。
Rintoは主にサーバーサイドを担当し、Hikaruは主にフロントエンドを担当しました。

開発の中で特に難しかった点は、一覧画面の改善の繰り返しと**「いいね」した投稿を絞り込む機能**でした。
いずれの場合も、CSSが正しく読み込まれない不具合に悩まされましたが、
Bootstrapの設定やBladeファイルの記述を細かく見直すことで、原因を突き止め解決することができました。

また、コメント機能の実装にも苦労しました。
当初はコメントを送信しても受け付けられず、ページがリロードされてしまう問題が発生しました。
この不具合を解決するまでに多くの時間を費やし、ChatGPTも活用しましたが、時にはさらに問題を複雑にしてしまうこともありました。

さらに、GitHub上でのコンフリクトにも苦戦しました。しかし、その経験を通じて、GitHubの使い方やChatGPTの効果的な活用方法について多くを学ぶことができました。

- Registration and login functions
- Posting (title, body, photo)
- Deleting and editing own posts
- Liking posts
- Commenting on posts
- Retrieving posts
- Sorting posts
