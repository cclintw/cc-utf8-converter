# CC UTF8 Converter

CC UTF8 Converter 是一款輕量級 WordPress 外掛，能將使用者上傳的純文字檔案（例如 Big5、SJIS、GB2312 等編碼）轉換為 UTF-8 並自動下載，特別適合研究者、檔案整理人員、數位人文工作者以及需要處理舊式編碼文字檔的使用者。外掛可自動判斷常見編碼，包括 UTF-8、BIG5、SJIS、GB2312，並安全轉換為 UTF-8，同時將無法識別或不支援的字元以可見符號（■）替代，以方便後續校對與資料處理。此工具支援 WordPress 後台工具頁與前端 shortcode `[cc_utf8_converter]` 使用，使用者可直接在頁面或文章中上傳檔案並立即下載轉換後的結果，所有暫存檔案在下載後會自動刪除，以確保安全並避免佔用主機空間。支援的檔案格式包含 .txt、.csv、.html、.htm、.xhtml、.xml、.md 等常見純文字檔。安裝方式為將外掛上傳至 `/wp-content/plugins/`，啟用後即可於「工具 → UTF-8 Converter」使用，或在頁面中加入 shortcode。外掛同時支援 i18n 多語系，使用 `cc-utf8-converter` text domain，並遵循 WordPress 外掛開發標準，適合用於正式網站與數位人文文本前處理工作流程。

## 功能特色

- 文字檔轉換為 UTF-8
- 自動判斷編碼（UTF-8 / BIG5 / SJIS / GB2312）
- 無法識別字元以 ■ 取代
- 拖曳上傳介面
- 轉換完成自動下載
- 暫存檔自動刪除
- 支援後台與 shortcode
- 支援 i18n 多語系

## Shortcode


```
[cc_utf8_converter]
```

## 支援格式

- .txt
- .csv
- .html
- .htm
- .xhtml
- .xml
- .md

## 安裝方式

1. 上傳至 `/wp-content/plugins/`
2. 啟用外掛
3. 前往 `工具 → UTF-8 Converter`
4. 或使用 shortcode `[cc_utf8_converter]`

## 更新紀錄

### 1.0.0

- 初版發布