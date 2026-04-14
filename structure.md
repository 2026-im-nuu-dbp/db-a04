## 登入及註冊
- 一開始進入 login.html

    if(沒註冊){
        點連結進入 register.html
        註冊資料由 regieter.php 寫入 dbusers.sql
    }else{
        login.php 搜尋 dbusers.sql 有無資料

        if(success){
            進入首頁 home.html
        }else{
            進入註冊頁 register.html
        }
    }

## 首頁 home.html
1. header 左邊顯示 user [暱稱]，右邊有連結可以顯示登入資料 login_records.html
1. 能以捲動的方式查看之前新增的 memo，由 get_memo.php 從 dbmemo.sql 讀取資料
2. 頁面最上方有「新增」按鈕，按下進入 add_memo.html
3. 每一個以前新增的 memo 都有 垃圾桶、修改 按鍵

## 新增圖文記事
1. add_memo.html 輸入資料
2. 由 add_memo.php 寫入 dbmemo.sql

## dblog.sql
1. 四欄：id, username, login_time, success
2. Key attribute：

## dbmemo.sql
1. 五欄：memo_id, user_id, title, content, image_path
2. Key attribute：

## dbusers.sql
1. 五欄：account, nickname, password, gender, interest
2. Key attribute：