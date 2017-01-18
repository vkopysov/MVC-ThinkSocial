<?php
namespace App\Controllers;

use App\Models\{User, UserAvatarComment, UserCity,UserGroup, Friend,
    Message, City, AlbumUser, Album, UserNews, AlbumPhotoComment, News, NewsComment, Comment};

class AlbumsController extends PageController
{
    public function actionIndex($id='')
    {
        $result = parent::actionIndex($id);
        $result['templateNames'] = [
            'head',
            'navbar',
            'leftcolumn',
            'middlecolumnalbums',
            'rightcolumn',
            'footer',
        ];
        return $result;
    }
}
