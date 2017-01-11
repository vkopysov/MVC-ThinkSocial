<?php
namespace App\Controllers;
use App\Models\{User, UserAvatarComment, UserCity,UserGroup, Friend,
    Message, City, AlbumUser, Album, UserNews, AlbumPhotoComment, News, NewsComment, Comment};


/**
 * Created by PhpStorm.
 * User: bond
 * Date: 12.12.16
 * Time: 13:31
 */

Class PageController Extends BaseController
{
    protected function getTemplateNames()
    {
        $this->templateInfo['templateNames'] = ['head', 'navbar', 'leftcolumn', 'rightcolumn', 'footer'];
    }
    protected function getTitle()
    {
        $this->templateInfo['title'] = 'ThinkSocial';
    }
    protected function getControllerVars()
    {
        $userId = User::checkLogged();

        User::join('id', 'App\Models\UserAvatar', 'userId', " AND status='active'");
        $user = User::getByID($userId);
        if (isset ($user->userAvatar->id)) {
            $commentAvatarNum = UserAvatarComment::count(['userAvatarId' => $user->userAvatar->id]);
        } else {
            $commentAvatarNum = 0;
        }

        UserCity::join('cityId', 'App\Models\City', 'id');
        City::join('countryId', 'App\Models\Country', 'id');
        $userCities = UserCity::getByCondition(['userId' => $userId], " ORDER BY created_at");

        UserGroup::join('groupId', 'App\Models\Group', 'id');
        $userGroups = UserGroup::getByCondition(['userId' => $userId]);

        AlbumUser::join('albumId', 'App\Models\Album', 'id');

        Album::join('id', 'App\Models\AlbumPhoto', 'albumId', " AND status='active'");
        $userAlbums = AlbumUser::getByCondition(['userId' => $userId]);
        $commentPhotosNum = 0;
        foreach ($userAlbums as $userAlbum) {
            foreach ($userAlbum->album->albumPhoto as $albumPhoto) {
                $commentPhotosNum += AlbumPhotoComment::count(['albumPhotoId' => $albumPhoto->id]);
            }
        }

        UserNews::join('newsId', 'App\Models\News', 'id');
        News::join('id', 'App\Models\NewsComment', 'newsId', ' LIMIT 3');
        NewsComment::join('commentId', 'App\Models\Comment', 'id', " AND status='active'");
        Comment::join('userId', 'App\Models\User', 'id');
        $userNews = UserNews::getByCondition(['userId' => $userId]);

        $commentNewsNum = 0;
        foreach ($userNews as $oneUserNews) {
            $commentNewsNum += NewsComment::count(['newsId' => $oneUserNews->newsId]);
        }

        Friend::join('userSender', 'App\Models\User', 'id');
        $friendReqs = Friend::getByCondition(['userReceiver' => $userId, 'status' => 'unapplied'], " ORDER BY created_at DESC");

        $unreadMessagesNum = Message::count(['receiverId' => $userId, 'status' => 'unread']);

        $result = ["unreadMessagesNum" => $unreadMessagesNum,
                   "commentPhotosNum" => $commentPhotosNum,
                   "commentNewsNum" => $commentNewsNum,
                   "commentAvatarNum" => $commentAvatarNum,
                   "user" => $user,
                   "userAvatar" => $user->userAvatar,
                   "userCities" => $userCities,
                   "userGroups" => $userGroups,
                   "userAlbums" => $userAlbums,
                   "userNews" => $userNews,
                   "friendReqs" => $friendReqs];
        return $result;
    }
}
