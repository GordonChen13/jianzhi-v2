# 基于Laravel5.5与vue2前后端分离的兼职平台

### 项目上线运行地址

[贝兼兼职，最懂你的兼职平台](http://www.qianpaisanhuali.com)
体验账号：test@test.com  密码：123456

### 项目运行步骤

1. `git clone https://github.com/GordonChen13/jianzhi-v2.git`
2. `composer install`
3. `把.env.example重命名为.env,并设置相应参数,消息推送用pusher,需要pusher帐号`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan queue:work`
8. `sudo chgrp -R www-data storage bootstrap/cache`
9. `sudo chmod -R ug+rwx storage bootstrap/cache`
10. `php artisan serve`

### 页面功能展示

#### 用户页面

##### 基于redis缓存实现的用户首页动态
![](http://owbbycifk.bkt.clouddn.com/image/gif/userHome.png?imageView2/2/w/888)

##### 可排序的热门兼职、个性兼职推荐页面
![](http://owbbycifk.bkt.clouddn.com/image/gif/userHot.png?imageView2/2/w/888)

##### 可收藏、申请、咨询、分享、邀请好友、举报的兼职Item
![](http://owbbycifk.bkt.clouddn.com/image/gif/workItem.gif)

##### 基于pusher实现的即时在线聊天窗口
![](http://owbbycifk.bkt.clouddn.com/image/gif/userChat.gif)

##### 带有标签、评分统计、评论图片、评论回复的评价页面
![](http://owbbycifk.bkt.clouddn.com/image/gif/userReviewMini.gif)

##### 能够新建团队，管理成员，更改团队信息的团队页面
![](http://owbbycifk.bkt.clouddn.com/image/gif/userTeamMini(1).gif)

 #### 雇主页面
 
 ##### 可管理兼职状态，邀请好友、查看兼职信息的兼职管理页面
 ![](http://owbbycifk.bkt.clouddn.com/image/gif/employerWorks.gif?imageView2/2/w/888)
 
 ##### 可同步更新录用人数的申请人管理页面
 ![](http://owbbycifk.bkt.clouddn.com/image/gif/employerManage.gif?imageView2/2/w/888)
 
 ##### 回复用户咨询页面
![](http://owbbycifk.bkt.clouddn.com/image/gif/employerAnswer.gif?imageView2/2/w/888)

 ##### 基于pusher在线推redis离线拉的消息提醒功能
![](http://owbbycifk.bkt.clouddn.com/image/gif/employerNotifications.gif?imageView2/2/w/888)
![](http://owbbycifk.bkt.clouddn.com/image/gif/employerNotifications2.gif?imageView2/2/w/888)
 
 
 ##### 可打分、可选标签、可上传图片的评价页面
 ![](http://owbbycifk.bkt.clouddn.com/image/gif/employerReviewMini.gif)
 
